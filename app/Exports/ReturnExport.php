<?php

namespace App\Exports;

use App\Models\ReturnOrders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Models\ReturnOrdersProducts;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\ReturnOrderResource;

use Carbon\Carbon;

class ReturnExport implements FromCollection, WithMapping, WithHeadings,WithEvents,WithStyles
{
    use Exportable;
    
    public $from_created_date, $to_created_date, $from_date_show, $to_date_show;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->from_date_show = $data['from_date_show'];
        $this->to_date_show = $data['to_date_show'];
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        
        //dd($this->data, $this->from_date_show , $this->to_date_show);
        $query = ReturnOrdersProducts::query()->with('order.storeData')->createdUser()->ProductJoin();
        if($this->from_created_date != ''){
            // $this->from_created_date = strtotime($this->from_created_date);
            // $this->from_created_date = date(config('app.sql_date_format'), $this->from_created_date);
            $this->from_created_date = $this->from_created_date . ' 00:00:00';
            $query = $query->where('order_return_product.created_at', '>=', $this->from_created_date)->with('returnOrder');
        }
        if($this->to_created_date != ''){
            // $this->to_created_date = strtotime($this->to_created_date);
            // $this->to_created_date = date(config('app.sql_date_format'), $this->to_created_date);
            $this->to_created_date = $this->to_created_date . ' 23:59:59';
            $query = $query->where('order_return_product.created_at', '<=', $this->to_created_date)->with('returnOrder');
        }
        if(isset($status)){
            $query = $query->where('order_return_product.status', $status);
        }
        
        $order_return = $query->get();
        $this->sale_amount_excluding_tax = $order_return->sum('sale_amount_excluding_tax');
        $this->discount_amount = $order_return->sum('discount_amount');
        $this->total_amount = $order_return->sum('total_amount');
        $this->tax_amount = $order_return->sum('tax_amount');
        $this->rows_count = $query->count() + 3;
        return $order_return;
    }

    public function headings(): array
    {
        $headings = [];
        $headings[0] = [
            'Title', 'Product-Wise Return Report'
        ];
        $headings[1] = [
            'Date Range', $this->from_date_show." - ".$this->to_date_show,
        ];
        $headings[2] = [
            ''
        ];
        $headings[3] = [
            trans('PRODUCT NAME'),
            trans('BRANCH'),
            trans('BRANCH REFERENCE'),
            trans('USER'),
            trans('ORDER REFERENCE'),
            trans('TIME'),
            trans('QUANTITY'),
            trans('AMOUNT(EXCLUDE TAX)'),
            trans('TAX AMOUNT'),
            trans('TOTAL AMOUNT'),
            trans('REASON'),
            trans('IS WASTAGE'),
        ];
        return $headings;
    }

    public function map($return_order): array
    {
        $date = $return_order->created_at != null?Carbon::parse($return_order->created_at)->format('m/d/Y'):null;
        if(app()->getLocale() == 'ar'){
            $product_name = $return_order['name_ar']!=null?$return_order['name_ar']:$return_order['name'];
        }else{
            $product_name = $return_order['name'];
        }
        return [
            (isset($product_name))?$product_name:'',
            (isset($return_order->order->storeData['name']))?$return_order->order->storeData['name']:'',
            (isset($return_order->order->storeData['store_code']))?$return_order->order->storeData['store_code']:'',
            (isset($return_order['fullname']))?$return_order['fullname']:'',
            (isset($return_order->order['order_number']))?$return_order->order['order_number']:'',
            (isset($return_order['created_at']))? Carbon::parse($return_order['created_at'])->format('F d, h:i a') :'',
            (isset($return_order['quantity']))?$return_order['quantity']:'',
            (isset($return_order['sub_total_purchase_price_excluding_tax']))?$return_order['sub_total_purchase_price_excluding_tax']:'',
            (isset($return_order['tax_amount']))?$return_order['tax_amount']:'',
            (isset($return_order['total_amount']))?$return_order['total_amount']:'',
            (isset($return_order->order['reason']))?$return_order->order['reason']:'',
            ($return_order['return_type'] == 'Damage') ? 'Yes': 'No'
        ];
    }

    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                // $event->sheet->getDelegate()->setCellValue("A$this->rows_count",trans('Total'));
                // $event->sheet->getDelegate()->setCellValue("G$this->rows_count", $this->sale_amount_excluding_tax);    
                // $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->discount_amount);    
                // $event->sheet->getDelegate()->setCellValue("J$this->rows_count", $this->total_amount);
                // $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->tax_amount);
            }
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            2    => ['font' => ['bold' => true]],
            4    => ['font' => ['bold' => true]],
        ];
    }
}
