<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Resources\OrderResource;

use Carbon\Carbon;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;

class OrderExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->sale_amount_subtotal_excluding_tax = 0;
        $this->total_discount_amount = 0;
        $this->total_after_discount = 0;
        $this->total_tax_amount = 0;
        $this->total_order_amount = 0;
        $this->rows_count = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        

        $query = Order::query();

        if($from_created_date != ''){
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            //$from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('orders.created_at', '>=', $from_created_date);
        }
        if($to_created_date != ''){
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            //$to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('orders.created_at', '<=', $to_created_date);
        }
        if(isset($status)){
            $query = $query->where('orders.status', $status);
        }

        $transaction_income=0;

        if(isset($status) && $status==1){//for status closed
        $transaction_type_data = MasterTransactionTypeModel::select('id')
        ->where('transaction_type_constant', '=', trim('INCOME'))
        ->first();

        if($from_created_date!="" && $to_created_date==""){
            $from_created_date=$from_created_date;
            $to_created_date=$from_created_date;
        }
        if($from_created_date=="" && $to_created_date!=""){
            $from_created_date=$to_created_date;
            $to_created_date=$to_created_date;
        }
        if($from_created_date!="" && $to_created_date!=""){
            $from_created_date=$from_created_date;
            $to_created_date=$to_created_date;
        }

     
        $transaction_income = TransactionModel::select('id')->where([
            ['transaction_type', '=', $transaction_type_data->id],
        ])
        ->whereIn('bill_to', ['INVOICE', 'CUSTOMER', 'SUPPLIER'])
        ->whereBetween('transaction_date', [$from_created_date,$to_created_date])
        ->sum('amount');
        }

       

        $orders = $query->get();

        $this->sale_amount_subtotal_excluding_tax = $orders->sum('sale_amount_subtotal_excluding_tax');
        $this->total_discount_amount = $orders->sum('total_discount_amount');
        $this->total_after_discount = $orders->sum('total_after_discount');
        $this->total_tax_amount = $orders->sum('total_tax_amount');
        $this->total_order_amount = $orders->sum('total_order_amount')+$transaction_income;
       
        $this->rows_count = $query->count() + 3;
        return $orders;
    }

    public function headings(): array
    {
        
        $headings = [
            trans('ORDER NUMBER'),
            trans('CUSTOMER PHONE'),
            trans('CUSTOMER EMAIL'),
            trans('SALE AMOUNT SUBTOTAL EXCLUDING TAX'),
            trans('TOTAL DISCOUNT AMOUNT'),
            trans('TOTAL AFTER DISCOUNT'),
            trans('TOTAL TAX AMOUNT'),
            trans('TOTAL ORDER AMOUNT'),
            trans('PAYMENT METHOD'),
            trans('STATUS'),
            trans('CREATED AT'),
            trans('CREATED BY'),
            trans('UPDATED AT'),
            trans('UPDATED BY')
        ];

        return $headings;
    }

    public function map($order): array
    {
        $order = collect(new OrderResource($order));
        $data = [
            (isset($order['order_number']))?$order['order_number']:'',
            (isset($order['customer_phone']))?$order['customer_phone']:'',
            (isset($order['customer_email']))?$order['customer_email']:'',
            (isset($order['sale_amount_subtotal_excluding_tax']))?$order['sale_amount_subtotal_excluding_tax']:'',
            (isset($order['total_discount_amount']))?$order['total_discount_amount']:'',
            (isset($order['total_after_discount']))?$order['total_after_discount']:'',
            (isset($order['total_tax_amount']))?$order['total_tax_amount']:'',
            (isset($order['total_order_amount']))?$order['total_order_amount']:'',
            (isset($order['payment_method']))?$order['payment_method']:'',
            (isset($order['status']['label']))?$order['status']['label']:'',
            (isset($order['created_at_label']))?$order['created_at_label']:'',
            (isset($order['created_by']['fullname']))?$order['created_by']['fullname']:'',
            (isset($order['updated_at_label']))?$order['updated_at_label']:'',
            (isset($order['updated_by']['fullname']))?$order['updated_by']['fullname']:''
        ];

        return $data;
    }
    public function registerEvents(): array
    {
        $cells = [
            "A$this->rows_count" => trans('Total'),
            "D$this->rows_count" => $this->sale_amount_subtotal_excluding_tax,
            "E$this->rows_count" => $this->total_discount_amount,
            "F$this->rows_count" => $this->total_after_discount,
            "G$this->rows_count" => $this->total_tax_amount,
            "H$this->rows_count" => $this->total_order_amount
        ];
        return [            
            AfterSheet::class => function(AfterSheet $event)use($cells) {
                    foreach ($cells as $cell_pos => $cell_value) {
                        $event->sheet->getDelegate()->setCellValue($cell_pos, $cell_value);
                    }  
                },
        ];
    }

    public function array_push($array,$position,$value)
    {
        return array_merge(array_slice($array, 0, $position), array($value), array_slice($array, $position));
    }
}
