<?php

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Resources\QuotationResource;

use Carbon\Carbon;

class QuotationExport implements FromCollection, WithMapping, WithHeadings, WithEvents

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
        

        $query = Quotation::query();

        if($from_created_date != ''){
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            // $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('quotations.quotation_date', '>=', $from_created_date);
        }
        if($to_created_date != ''){
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            // $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('quotations.quotation_date', '<=', $to_created_date);
        }
        if(isset($status)){
            $query = $query->where('quotations.status', $status);
        }

        $quotations = $query->get();

        $this->subtotal_excluding_tax = $quotations->sum('subtotal_excluding_tax');
        $this->total_discount_amount = $quotations->sum('total_discount_amount');
        $this->total_after_discount = $quotations->sum('total_after_discount');
        $this->total_tax_amount = $quotations->sum('total_tax_amount');
        $this->total_order_amount = $quotations->sum('total_order_amount');
        $this->shipping_charge = $quotations->sum('shipping_charge');
        $this->packing_charge = $quotations->sum('packing_charge');
        
        $this->rows_count = $query->count() + 3;
        return $quotations;
    }

    public function headings(): array
    {
        return [
            trans('BILL TO'),
            trans('QUOTATION NUMBER'),
            trans('QUOTATION REFERENCE'),
            trans('QUOTATION DATE'),
            trans('QUOTATION DUE DATE'),
            trans('BILL TO CODE'),
            trans('BILL TO NAME'),
            trans('BILL TO CONTACT'),
            trans('BILL TO EMAIL'),
            trans('BILL TO ADDRESS'),
            trans('CURRENCY NAME'),
            trans('CURRENCY CODE'),
            trans('SUBTOTAL EXCLUDING TAX'),
            trans('TOTAL DISCOUNT AMOUNT'),
            trans('TOTAL AFTER DISCOUNT'),
            trans('TOTAL TAX AMOUNT'),
            trans('SHIPPING CHARGE'),
            trans('PACKING CHARGE'),
            trans('TOTAL AMOUNT'),
            trans('STATUS'),
            trans('CREATED AT'),
            trans('CREATED BY'),
            trans('UPDATED AT'),
            trans('UPDATED BY')
        ];
    }

    public function map($order): array
    {
        $order = collect(new QuotationResource($order));
        return [
            (isset($order['bill_to']))?$order['bill_to']:'',
            (isset($order['quotation_number']))?$order['quotation_number']:'',
            (isset($order['quotation_reference']))?$order['quotation_reference']:'',
            (isset($order['quotation_date']))?$order['quotation_date']:'',
            (isset($order['quotation_due_date']))?$order['quotation_due_date']:'',
            (isset($order['bill_to_code']))?$order['bill_to_code']:'',
            (isset($order['bill_to_name']))?$order['bill_to_name']:'',
            (isset($order['bill_to_contact']))?$order['bill_to_contact']:'',
            (isset($order['bill_to_email']))?$order['bill_to_email']:'',
            (isset($order['bill_to_address']))?$order['bill_to_address']:'',
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
                    $event->sheet->getDelegate()->setCellValue("M$this->rows_count",  $this->subtotal_excluding_tax);    
                    $event->sheet->getDelegate()->setCellValue("N$this->rows_count", $this->total_discount_amount);    
                    $event->sheet->getDelegate()->setCellValue("O$this->rows_count", $this->total_after_discount);    
                    $event->sheet->getDelegate()->setCellValue("Q$this->rows_count", $this->shipping_charge);    
                    $event->sheet->getDelegate()->setCellValue("R$this->rows_count", $this->packing_charge);    
                    $event->sheet->getDelegate()->setCellValue("P$this->rows_count", $this->total_tax_amount);    
                    $event->sheet->getDelegate()->setCellValue("S$this->rows_count", $this->total_order_amount);    

                },
        ];
    }
}
