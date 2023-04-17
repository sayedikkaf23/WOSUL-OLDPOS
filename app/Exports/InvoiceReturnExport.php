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
use App\Models\InvoiceReturnProducts;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

use App\Http\Resources\ReturnOrderResource;

use Carbon\Carbon;

class InvoiceReturnExport implements FromCollection, WithMapping, WithHeadings,WithEvents,WithStyles
{
    use Exportable;
    
    public $from_created_date, $to_created_date;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $status = $this->data['status'];

        $query = InvoiceReturnProducts::query()->with('returnInvoice.storeData')->createdUser();
        if($this->from_created_date != ''){
            // $this->from_created_date = strtotime($this->from_created_date);
            // $this->from_created_date = date(config('app.sql_date_format'), $this->from_created_date);
            // $this->from_created_date = $this->from_created_date . ' 00:00:00';
            $query = $query->where('invoice_return_products.created_at', '>=', $this->from_created_date)->with('returnInvoice');
        }
        if($this->to_created_date != ''){
            // $this->to_created_date = strtotime($this->to_created_date);
            // $this->to_created_date = date(config('app.sql_date_format'), $this->to_created_date);
            // $this->to_created_date = $this->to_created_date . ' 23:59:59';
            $query = $query->where('invoice_return_products.created_at', '<=', $this->to_created_date)->with('returnInvoice');
        }
        if(isset($status)){
            $query = $query->where('invoice_return_products.status', $status);
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
            'Title',
            'Value',
        ];
        $headings[1] = [
            'Date Range',
            Carbon::parse($this->from_created_date)->format('m/d/Y')." - ". Carbon::parse($this->to_created_date)->format('m/d/Y'),
        ];
        $headings[2] = [
            ''
        ];
        $headings[3] = [
            trans('PRODUCT NAME'),
            trans('BRANCH'),
            trans('BRANCH REFERENCE'),
            trans('USER'),
            trans('INVOICE REFERENCE'),
            trans('TIME'),
            trans('QUANTITY'),
            trans('AMOUNT'),
            trans('REASON'),
            trans('IS WASTAGE'),
        ];
        return $headings;
    }

    public function map($invoice_return): array
    {

        $date = $invoice_return->created_at != null?Carbon::parse($invoice_return->created_at)->format('m/d/Y'):null;
        return [
            (isset($invoice_return['name']))?$invoice_return['name']:'',
            (isset($invoice_return->returnInvoice->storeData['name']))?$invoice_return->returnInvoice->storeData['name']:'',
            (isset($invoice_return->returnInvoice->storeData['store_code']))?$invoice_return->returnInvoice->storeData['store_code']:'',
            (isset($invoice_return['fullname']))?$invoice_return['fullname']:'',
            (isset($invoice_return->returnInvoice['invoice_number']))?$invoice_return->returnInvoice['invoice_number']:'',
            (isset($invoice_return['created_at']))? Carbon::parse($invoice_return['created_at'])->format('F d, h:i a') :'',
            (isset($invoice_return['quantity']))?$invoice_return['quantity']:'',
            (isset($invoice_return['total_amount']))?$invoice_return['total_amount']:'',
            (isset($invoice_return->returnInvoice['reason']))?$invoice_return->returnInvoice['reason']:'',
            (isset($invoice_return['is_wastage']) && $invoice_return['is_wastage'] == true) ? 'Yes':'No'
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
