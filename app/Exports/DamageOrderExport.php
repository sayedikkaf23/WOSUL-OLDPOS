<?php

namespace App\Exports;

use App\Models\ReturnOrdersProducts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Resources\DamageResource;

class DamageOrderExport implements FromCollection, WithMapping, WithHeadings, WithEvents
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
        $store_code = $this->data['store_code'];
        $total_amount = 0;
        $total_quantity = 0;
        $query = ReturnOrdersProducts::select("*")->where("return_type","Damage")->createdUser();
        $orders = $query->whereRaw("order_return_product.created_at between '".$from_created_date."' and '".$to_created_date."'")
                // ->where('order_return_product.branch_reference', $store_code)
                ->get();
        foreach($orders as $order){
            $total_amount+=(float)$order->total_amount;
            $total_quantity += (float)$order->quantity;
        }
       
        $this->total_amount = $total_amount;
        $this->total_quantity = $total_quantity;
        $this->rows_count = count($orders)+2;
        return $orders;
    }

    public function headings(): array
    {
        return [
            trans('PRODUCT'),
            trans('BRANCH'),
            trans('BRANCH REFERENCE'),
            trans('ORDER TYPE'),
            trans('ADDED BY'),
            trans('ORDER REFERENCE'),
            trans('TIME'),
            trans('QUANTITY'),
            trans('AMOUNT'),
            trans('REASON')
        ];
    }

    public function map($order): array
    {
        $order = collect(new DamageResource($order));
        return [
            (isset($order['product'])) ? $order['product'] : '',
            (isset($order['branch'])) ? $order['branch'] : '',
            (isset($order['branch_reference'])) ? $order['branch_reference'] : '',
            (isset($order['order_type'])) ? $order['return_type'] : '',
            (isset($order['added_by'])) ? $order['added_by'] : '',
            (isset($order['order_reference'])) ? $order['order_reference'] : '',
            (isset($order['time'])) ? $order['time'] : '',
            (isset($order['quantity'])) ? $order['quantity'] : '',
            (isset($order['amount'])) ? $order['amount'] : '',
            (isset($order['reason'])) ? $order['reason'] : '',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setCellValue("A$this->rows_count", trans('Total'));
                $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->total_quantity);
                $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->total_amount);
            },
        ];
    }
}