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
use App\Models\Supplier as SupplierModel;
use App\Models\Invoice as InvoiceModel;
use App\Models\Store as StoreModel;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\MasterStatus as MasterStatusModel;

class SupplierInvoiceReport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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
        $supplier_id = ($this->data['supplier'] == '') ? null : SupplierModel::where('slack',$this->data['supplier'])->first()->id; 

        $headings = [
            trans('Invoice Number'),
            trans('Reference No.'),
            trans('Supplier'),
            trans('Tax'),
            trans('Issue Date'),
            trans('Due Date'),
            trans('Total'),
            trans('Total Tax'),
            trans('Status')
        ];

        return $headings;
    }

    public function collection()
    {
        $this->from_created_date = $this->data['from_created_date'];
        $this->to_created_date = $this->data['to_created_date'];
        $supplier_id = ($this->data['supplier'] == '') ? null : SupplierModel::where('slack',$this->data['supplier'])->first()->id; 

        $query = InvoiceModel::withoutGlobalScopes()->select('invoices.*')
        ->where('invoices.bill_to','SUPPLIER')
        ->when($supplier_id != null, function($query) use($supplier_id) {
            $query->where('invoices.bill_to_id',$supplier_id);
        });

        if($this->from_created_date != ''){
            // $this->from_created_date = strtotime($this->from_created_date);
            // $this->from_created_date = date(config('app.sql_date_format'), $this->from_created_date);
            // $this->from_created_date = $this->from_created_date . ' 00:00:00';
            $query = $query->where('invoices.invoice_date', '>=', $this->from_created_date);
        }
        if($this->to_created_date != ''){
            // $this->to_created_date = strtotime($this->to_created_date);
            // $this->to_created_date = date(config('app.sql_date_format'), $this->to_created_date);
            // $this->to_created_date = $this->to_created_date . ' 23:59:59';
            $query = $query->where('invoices.invoice_date', '<=', $this->to_created_date);
        }
        
        $invoices = $query->get();

        $rows = [];
        $total_tax_amount = 0;
        $total_amount = 0;
        foreach($invoices as $invoice){

            $dataset = [];

            $supplier_name = "";
            $supplier_tax_number = "";
            if($invoice->bill_to_id != null){
                $supplier = SupplierModel::find($invoice->bill_to_id);
                if(isset($supplier)){
                    $supplier_name = $supplier->name;
                    $supplier_tax_number = $supplier->tax_number;
                }else{
                    $supplier_name = 'N/A';
                    $supplier_tax_number = 'N/A';
                }
            }else{
                $supplier_name = 'N/A';
                $supplier_tax_number = 'N/A';
            }
            $invoice_status = MasterStatusModel::select('label')->where('key','INVOICE_STATUS')->where('value',$invoice->status)->first();
            
            $dataset['invoice_number'] = $invoice->invoice_number;
            $dataset['reference_number'] = $invoice->invoice_reference;
            $dataset['supplier'] = $supplier_name;
            $dataset['tax'] = $supplier_tax_number;
            $dataset['issue_date'] =  (!is_null($invoice->invoice_date)) ? Carbon::parse($invoice->invoice_date)->format('d-m-Y') : 'N/A';
            $dataset['due_date'] =  (!is_null($invoice->invoice_due_date)) ? Carbon::parse($invoice->invoice_due_date)->format('d-m-Y') : 'N/A';
            $dataset['total'] = $invoice->total_order_amount;
            $dataset['total_tax'] = $invoice->subtotal_including_tax - $invoice->subtotal_excluding_tax ;
            $dataset['status'] = (isset($invoice_status)) ? $invoice_status->label : 'N/A';

            $total_tax_amount += $dataset['total_tax']; 
            $total_amount += $dataset['total']; 

            array_push($rows, $dataset);

        }

        $footer_row = [
            '',
            '',
            '',
            '',
            '',
            '',
            $total_amount,
            $total_tax_amount,
            '',
        ];
        array_push($rows,$footer_row);

        return collect($rows);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            
            
            
        ];
    }

}
