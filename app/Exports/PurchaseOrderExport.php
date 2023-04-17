<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Resources\PurchaseOrderResource;

use Carbon\Carbon;

class PurchaseOrderExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->subtotal_excluding_tax = 0;
        $this->total_discount_amount = 0;
        $this->total_after_discount = 0;
        $this->shipping_charge = 0;
        $this->packing_charge = 0;
        $this->total_tax_amount = 0;
        $this->total_order_amount = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];
        

        $query = PurchaseOrder::query();

        if($from_created_date != ''){
            $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('purchase_orders.created_at', '>=', $from_created_date);
        }
        if($to_created_date != ''){
            $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('purchase_orders.created_at', '<=', $to_created_date);
        }
        if(isset($status)){
            $query = $query->where('purchase_orders.status', $status);
        }

        $purchase_orders = $query->get();


        $this->subtotal_excluding_tax = $purchase_orders->sum('subtotal_excluding_tax');
        $this->total_discount_amount = $purchase_orders->sum('total_discount_amount');
        $this->total_after_discount = $purchase_orders->sum('total_after_discount');
        $this->total_tax_amount = $purchase_orders->sum('total_tax_amount');
        $this->total_order_amount = $purchase_orders->sum('total_order_amount');
        $this->shipping_charge = $purchase_orders->sum('shipping_charge');
        $this->packing_charge = $purchase_orders->sum('packing_charge');
        
        $this->rows_count = $query->count() + 3;
        return $purchase_orders;
    }

    public function headings(): array
    {
        return [
            trans('PO NUMBER'),
            trans('PO REFERENCE'),
            trans('ORDER DATE'),
            trans('ORDER DUE DATE'),
            trans('SUPPLIER CODE'),
            trans('SUPPLIER NAME'),
            trans('SUPPLIER ADDRESS'),
            trans('CURRENCY NAME'),
            trans('CURRENCY CODE'),
            trans('SUBTOTAL EXCLUDING TAX'),
            trans('TOTAL DISCOUNT AMOUNT'),
            trans('TOTAL AFTER DISCOUNT'),
            trans('TOTAL TAX AMOUNT'),
            trans('SHIPPING CHARGE'),
            trans('PACKING CHARGE'),
            trans('TOTAL ORDER AMOUNT'),
            trans('STATUS'),
            trans('CREATED AT'),
            trans('CREATED BY'),
            trans('UPDATED AT'),
            trans('UPDATED BY')
        ];
    }

    public function map($order): array
    {
        $order = collect(new PurchaseOrderResource($order));
        return [
            (isset($order['po_number']))?$order['po_number']:'',
            (isset($order['po_reference']))?$order['po_reference']:'',
            (isset($order['order_date']))?$order['order_date']:'',
            (isset($order['order_due_date']))?$order['order_due_date']:'',
            (isset($order['supplier_code']))?$order['supplier_code']:'',
            (isset($order['supplier_name']))?$order['supplier_name']:'',
            (isset($order['supplier_address']))?$order['supplier_address']:'',
            (isset($order['currency_name']))?$order['currency_name']:'',
            (isset($order['currency_code']))?$order['currency_code']:'',
            (isset($order['subtotal_excluding_tax']))?$order['subtotal_excluding_tax']:'',
            (isset($order['total_discount_amount']))?$order['total_discount_amount']:'',
            (isset($order['total_after_discount']))?$order['total_after_discount']:'',
            (isset($order['total_tax_amount']))?$order['total_tax_amount']:'',
            (isset($order['shipping_charge']))?$order['shipping_charge']:'',
            (isset($order['packing_charge']))?$order['packing_charge']:'',
            (isset($order['total_order_amount']))?$order['total_order_amount']:'',
            (isset($order['status']['label']))?$order['status']['label']:'',
            (isset($order['created_at_label']))?$order['created_at_label']:'',
            (isset($order['created_by']['fullname']))?$order['created_by']['fullname']:'',
            (isset($order['updated_at_label']))?$order['updated_at_label']:'',
            (isset($order['updated_by']['fullname']))?$order['updated_by']['fullname']:''
        ];
    }
    public function registerEvents(): array
    {
        return [            
            AfterSheet::class => function(AfterSheet $event) {
                    $event->sheet->getDelegate()->setCellValue("A$this->rows_count",trans('Total'));
                    $event->sheet->getDelegate()->setCellValue("J$this->rows_count",  $this->subtotal_excluding_tax);    
                    $event->sheet->getDelegate()->setCellValue("K$this->rows_count", $this->total_discount_amount);    
                    $event->sheet->getDelegate()->setCellValue("L$this->rows_count", $this->total_after_discount);    
                    $event->sheet->getDelegate()->setCellValue("N$this->rows_count", $this->shipping_charge);    
                    $event->sheet->getDelegate()->setCellValue("O$this->rows_count", $this->packing_charge);    
                    $event->sheet->getDelegate()->setCellValue("M$this->rows_count", $this->total_tax_amount);    
                    $event->sheet->getDelegate()->setCellValue("P$this->rows_count", $this->total_order_amount);    

                },
        ];
    }
}
