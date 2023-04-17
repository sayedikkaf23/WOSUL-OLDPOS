<?php

namespace App\Exports;

use App\Models\ReturnOrders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\Product as ProductModel;
use App\Models\Store as StoreModel;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\ReturnOrderResource;

use Carbon\Carbon;
use Mpdf\Tag\B;

class StockStatusExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    use Exportable;
    
    public $from_created_date, $to_created_date, $store_ids = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function headings(): array
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        $store_id = $this->data['store'];

        if($store_id != ''){
            $query = StoreModel::select('id','name')->where('id',$store_id)->active()->get();
            array_push($this->store_ids, (int) $store_id);
            $headings = $query->pluck('name')->toArray();        
        }else{
            $query = StoreModel::select('id','name')->active()->get();
            $this->store_ids = $query->pluck('id')->toArray();
            $headings = $query->pluck('name')->toArray();        
        }
        
        array_unshift($headings,trans('PRODUCT/STORE'));
        array_push($headings,trans('TOTAL QUANTITY'));

        return $headings;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        $store_id = $this->data['store'];

        $stores = StoreModel::select('id','name')->active()->get();

        $query = ProductModel::withoutGlobalScopes()->select('products.name')->distinct();

        if($this->from_created_date != ''){
            // $this->from_created_date = strtotime($this->from_created_date);
            // $this->from_created_date = date(config('app.sql_date_format'), $this->from_created_date);
            // $this->from_created_date = $this->from_created_date . ' 00:00:00';
            $query = $query->where('products.created_at', '>=', $this->from_created_date);
        }
        if($this->to_created_date != ''){
            // $this->to_created_date = strtotime($this->to_created_date);
            // $this->to_created_date = date(config('app.sql_date_format'), $this->to_created_date);
            // $this->to_created_date = $this->to_created_date . ' 23:59:59';
            $query = $query->where('products.created_at', '<=', $this->to_created_date);
        }
        if($store_id != ''){
            $query = $query->where('products.store_id', '<=', $store_id);
        }
        if(isset($status)){
            $query = $query->where('products.status', $status);
        }

        $products = $query->get()->pluck('name')->toArray();
        
        $rows = [];
        foreach($products as $product){
            
            $dataset = [];
            array_push($dataset,$product);
            $total_product_qty = 0; 
            foreach($this->store_ids as $store){
                $store_product_quantity = ProductModel::withoutGlobalScopes()->select('quantity')->where('name',$product)->where('store_id',$store)->first();
                if(isset($store_product_quantity)){
                    $qty = $store_product_quantity->quantity;
                    $total_product_qty += $qty;
                }else{
                    $qty = 0;
                }
                array_push($dataset, strval($qty));
            }
            array_push($dataset, strval($total_product_qty));
            $rows[] = $dataset;

        }

        return collect($rows);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            
            'A'   => [
                    'font' => [
                        'italic' => true
                    ], 
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    ]
            ],
            
            'A1'   => [
                    'font' => [
                        'bold' => true
                    ]
            ],
            
            'B:I'   => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            
            'I1'   => [
                'font' => [
                    'bold' => true
                ]
            ],

        ];
    }

}
