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

use Illuminate\Support\Facades\DB;

use App\Http\Resources\ReturnOrderResource;

use Carbon\Carbon;

class ReturnOrderExport implements FromCollection, WithMapping, WithHeadings,WithEvents
{
    use Exportable;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->sale_amount_excluding_tax = 0;
        $this->discount_amount = 0;
        $this->total_amount = 0;
        $this->rows_count = 0;
        $this->tax_amount= 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];

        $query = ReturnOrdersProducts::query();
        if($from_created_date != ''){
            $from_created_date = strtotime($from_created_date);
            $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('order_return_product.created_at', '>=', $from_created_date)->with('returnOrder');
        }
        if($to_created_date != ''){
            $to_created_date = strtotime($to_created_date);
            $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('order_return_product.created_at', '<=', $to_created_date)->with('returnOrder');
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
        return [
            trans('VALUE DATE'),
            trans('RETURN ORDER NUMBER'),
            trans('CUSTOMER NAME'),
            trans('CUSTOMER EMAIL'),
            trans('ITEM NAME'),
            trans('QUANTITY'),
            trans('PRICE'),
            trans('DISCOUNT'),
            trans('TAX AMOUNT'),
            trans('SUB TOTAL'),

        ];
    }

    public function map($return_order): array
    {
        $date = $return_order->created_at != null?Carbon::parse($return_order->created_at)->format('m/d/Y'):null;
        return [
            (isset($return_order['created_at']))? $date :'',
            (isset($return_order->returnOrder['return_order_number']))?$return_order->returnOrder['return_order_number']:'',
            (isset($return_order->returnOrder->customer_data['name']))?$return_order->returnOrder->customer_data['name']:'',
            (isset($return_order->returnOrder->customer_data['email']))?$return_order->returnOrder->customer_data['email']:'',
            (isset($return_order['name']))?$return_order['name']:'',
            (isset($return_order['quantity']))?$return_order['quantity']:'',
            (isset($return_order['sale_amount_excluding_tax']))?$return_order['sale_amount_excluding_tax']:'',
            (isset($return_order['discount_amount']))?$return_order['discount_amount']:'',
            (isset($return_order['tax_amount']))?$return_order['tax_amount']:'',
            (isset($return_order['total_amount']))?$return_order['total_amount']:'',
        ];
    }

    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->setCellValue("A$this->rows_count",trans('Total'));
                $event->sheet->getDelegate()->setCellValue("G$this->rows_count", $this->sale_amount_excluding_tax);    
                $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->discount_amount);    
                $event->sheet->getDelegate()->setCellValue("J$this->rows_count", $this->total_amount);
                $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->tax_amount);
            }
        ];
    }
}
