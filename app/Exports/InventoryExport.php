<?php

namespace App\Exports;

use App\Http\Controllers\API\InventoryReport;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Http\Resources\CategoryResource;

use Carbon\Carbon;

class InventoryExport implements FromCollection, WithHeadings
{
    use Exportable;
    
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $products = $this->data['products'];

        $inventory_report = new InventoryReport();
        $inventory_data = $inventory_report->generate_inventory_report($products,$from_created_date,$to_created_date);
        
        return collect($inventory_data) ;
    }

    public function headings(): array
    {
        return [
            trans('Product Name'),
            trans('Branch'),
            trans('Opening Quantity'),
            trans('Purchase Quantity'),
            trans('Transfer From Quantity'),
            trans('Trasnfer to Quantity'),
            trans('Sold Quantity'),
            trans('Returned Quantity'),
            trans('Damaged Quantity'),
            trans('Adjustment Quantity'),
            trans('Stock Returned Quantity'),
            trans('Variance Quantity'),
            trans('Available Quantity'),
        ];
    }

}
