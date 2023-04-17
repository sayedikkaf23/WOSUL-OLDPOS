<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use App\Http\Resources\UserResource;

/* Models */
use App\Models\User as UserModel;
use App\Models\Order as OrderModel;;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\ReturnOrders as ReturnOrdersModel;;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;

use Carbon\Carbon;
use DB;

class UserWiseSalesExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithStyles
{
    use Exportable;

    public  $from_created_date, 
            $to_created_date, 
            $total_sales_quantity = 0,
            $total_sales = 0,
            $total_returned_quantity = 0,
            $total_returned = 0,
            $total_net_sales_quantity = 0,
            $total_net_sales = 0,
            $total_discount = 0;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->rows_count = 0;
    }

    public function collection()
    {

        // $from_created_date = strtotime($this->data['from_created_date']);
        // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
        $this->from_created_date = Carbon::parse($this->data['from_created_date'])->format('Y-m-d') ;
        
        // $to_created_date = strtotime($this->data['to_created_date']);
        // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
        $this->to_created_date = Carbon::parse($this->data['to_created_date'])->format('Y-m-d');

        $status = $this->data['status'];
        $store_id = $this->data['store'];
     
        $user_ids = UserModel::active()->get()->pluck('id');

        $result = [];
        foreach($user_ids as $user_id){

            $dataset = [];
            $user = UserModel::find($user_id);
            $dataset['user_code'] = $user['user_code'];
            $dataset['user_name'] = $user['fullname'];

            $sales = OrderModel::withoutGlobalScopes()
            ->where('orders.status',1) // closed orders only
            ->where('orders.created_by',$user_id)
            ->when($store_id != null || $store_id != '', function($query) use($store_id) {
                $query->where('orders.store_id',$store_id);
            })
            ->whereBetween('orders.value_date',[$this->from_created_date,$this->to_created_date])
            ->select( 
                DB::Raw('IFNULL (SUM(orders.total_order_amount), 0) as total_order_amount'))
                // DB::Raw('IFNULL (SUM(orders.return_order_amount), 0) as partial_return_amount'))
            ->first()->toArray();
            $dataset['sales'] = $sales['total_order_amount'];
            
            if($dataset['sales'] > 0){

                $sales_quantity = OrderProductModel::query()
                ->join('orders','orders.id','order_products.order_id')
                ->where('orders.status',1) // closed orders only
                ->where('orders.created_by',$user_id)
                ->when($store_id != null || $store_id != '', function($query) use($store_id) {
                    $query->where('orders.store_id',$store_id);
                })
                ->whereBetween('orders.value_date',[$this->from_created_date,$this->to_created_date])
                ->select( 
                    DB::Raw('IFNULL (SUM(order_products.quantity), 0) as total_quantity'))
                ->first()->toArray();
                
                $dataset['sales_quantity'] = $sales_quantity['total_quantity'];
    
                $sales_return = ReturnOrdersModel::withoutGlobalScopes()
                ->where('order_return.status',1) // closed order only
                ->where('order_return.created_by',$user_id)
                ->when($store_id != null || $store_id != '', function($query) use($store_id) {
                    $query->where('order_return.store_id',$store_id);
                })
                ->whereBetween('order_return.value_date',[$this->from_created_date,$this->to_created_date])
                ->select( 
                    DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as total_order_amount')) 
                ->first()->toArray();
                $dataset['sales_return'] = $sales_return['total_order_amount'];
    
                $sales_return_quantity = ReturnOrdersProductsModel::query()
                ->join('order_return','order_return.id','order_return_product.return_order_id')
                ->where('order_return.status',1) // closed order only
                ->where('order_return.created_by',$user_id)
                ->when($store_id != null || $store_id != '', function($query) use($store_id) {
                    $query->where('order_return.store_id',$store_id);
                })
                ->whereBetween('order_return.value_date',[$this->from_created_date,$this->to_created_date])
                ->select( 
                    DB::Raw('IFNULL (SUM(order_return_product.quantity), 0) as total_quantity'))
                ->first()->toArray();
                $dataset['sales_return_quantity'] = $sales_return_quantity['total_quantity'];
    
                $dataset['net_sales_quantity'] = $dataset['sales_quantity'] - $dataset['sales_return_quantity'];
                $dataset['net_sales'] = $dataset['sales'] - $dataset['sales_return'];
    
                $discount = OrderProductModel::query()
                ->join('orders','orders.id','order_products.order_id')
                ->where('orders.status',1) // closed orders only
                ->where('orders.created_by',$user_id)
                ->when($store_id != null || $store_id != '', function($query) use($store_id) {
                    $query->where('orders.store_id',$store_id);
                })
                ->whereBetween('orders.value_date',[$this->from_created_date,$this->to_created_date])
                ->select( DB::Raw('IFNULL (SUM(order_products.discount_amount), 0) as total_discount'))
                ->first()->toArray();
                $dataset['discount'] = $discount['total_discount'];
    
                // GRAND TOTAL
                $this->total_sales_quantity += $dataset['sales_quantity'];
                $this->total_sales += $dataset['sales'];
                $this->total_returned_quantity += $dataset['sales_return_quantity'];
                $this->total_returned += $dataset['sales_return'];
                $this->total_net_sales_quantity += $dataset['net_sales_quantity'];
                $this->total_net_sales += $dataset['net_sales'];
                $this->total_discount += $dataset['discount'];
    
                $result[] = $dataset;
            }
            

        }

        $this->rows_count = count($result) + 3;
        return collect($result);
    }

    public function headings(): array
    {
        return [
            trans('USER CODE'),
            trans('USER NAME'),
            trans('SALES QUANTITY'),
            trans('SALES'),
            trans('SALES RETURNED QUANTITY'),
            trans('SALES RETURNED'),
            trans('NET SALES QUANTITY'),
            trans('NET SALES'),
            trans('DISCOUNT'),
        ];
    }

    public function map($user): array
    {
        return [
            $user['user_code'],
            $user['user_name'],
            $user['sales_quantity'],
            $user['sales'],
            $user['sales_return_quantity'],
            $user['sales_return'],
            $user['net_sales_quantity'],
            $user['net_sales'],
            $user['discount']
        ];
    }

    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                    $event->sheet->getDelegate()->setCellValue("A$this->rows_count",trans('Total'));
                    $event->sheet->getDelegate()->setCellValue("C$this->rows_count", $this->total_sales_quantity);
                    $event->sheet->getDelegate()->setCellValue("D$this->rows_count", $this->total_sales);
                    $event->sheet->getDelegate()->setCellValue("E$this->rows_count", $this->total_returned_quantity);
                    $event->sheet->getDelegate()->setCellValue("F$this->rows_count", $this->total_returned);
                    $event->sheet->getDelegate()->setCellValue("G$this->rows_count", $this->total_net_sales_quantity);
                    $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->total_net_sales);
                    $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->total_discount);
                },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
           // Style the first row as bold text.
           1    => ['font' => ['bold' => true]],
        ];
    }
}
