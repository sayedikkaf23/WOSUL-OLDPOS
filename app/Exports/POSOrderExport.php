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

class POSOrderExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->total_order_amount = 0;
        $this->rows_count = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $status = $this->data['order_status'];
        $store_id = $this->data['store_id'];

        /* order amount */
        $query = Order::closed()->withoutGlobalScopes();

        if ($from_created_date != '') {
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            // $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('orders.value_date', '>=', $from_created_date);
        }
        if ($to_created_date != '') {
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            // $to_created_date = $to_created_date . ' 23:59:59';
            $query =$query->where('orders.value_date', '<=', $to_created_date);
        }
        if ($status != "") {
            $query = $query->where('orders.status', '=', $status);
        }

        if ($store_id != "") {
            $query = $query->where('orders.store_id', $store_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        $total_order_amount = $orders->sum('total_order_amount');

        /* return order amount */
        $query = Order::withoutGlobalScopes();

        if ($from_created_date != '') {
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            // $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('orders.value_date', '>=', $from_created_date);
        }
        if ($to_created_date != '') {
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            // $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('orders.value_date', '<=', $to_created_date);
        }
        if ($status != "") {
            $query = $query->where('orders.status', '=', $status);
        }

        if ($store_id != "") {
            $query = $query->where('orders.store_id', $store_id);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        //$return_order_amount = $orders->sum('return_order_amount');

        $this->total_order_amount = $total_order_amount;

        $this->rows_count = $query->count() + 3;
        return $orders;
    }

    public function headings(): array
    {
        return [
            trans('TRANSACTION ID'),
            trans('ORDER NUMBER'),
            trans('CUSTOMER PHONE'),
            trans('CUSTOMER EMAIL'),
            trans('TOTAL AMOUNT'),
            trans('STATUS'),
            trans('CREATED AT'),
            trans('UPDATED AT'),
            trans('CREATED BY'),
        ];
    }

    public function map($order): array
    {
        $order = collect(new OrderResource($order));
        return [
            (isset($order['id'])) ? $order['id'] : '',
            (isset($order['order_number'])) ? $order['order_number'] : '',
            (isset($order['customer_phone'])) ? $order['customer_phone'] : '',
            (isset($order['customer_email'])) ? $order['customer_email'] : '',
            (isset($order['total_order_amount'])) ? $order['total_order_amount'] : '',
            (isset($order['status']['label'])) ? $order['status']['label'] : '',
            (isset($order['created_at_label'])) ? $order['created_at_label'] : '',
            (isset($order['updated_at_label'])) ? $order['updated_at_label'] : '',
            (isset($order['created_by']['fullname'])) ? $order['created_by']['fullname'] : '',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setCellValue("A$this->rows_count", trans('Total'));
                $event->sheet->getDelegate()->setCellValue("E$this->rows_count", $this->total_order_amount);
            },
        ];
    }
}
