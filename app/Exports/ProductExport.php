<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Taxcode;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Discountcode;
use App\Models\OrderProduct;


use App\Models\ReturnOrdersProducts;
use App\Http\Resources\ProductResource;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductExport implements FromCollection, WithMapping, WithHeadings, WithEvents
{
    use Exportable;

    public $sold_quantities, $total_sold_quantity = 0, $total_returned_quantity = 0, $total_quantity = 0;

    public function __construct(array $data = [])
    {
        $this->data = $data;
        $this->rows_count = 0;
        $this->sale_amount_excluding_tax = 0;
        $this->purchase_amount_excluding_tax = 0;
    }

    public function collection()
    {
        $from_created_date = $this->data['from_created_date'];
        $to_created_date = $this->data['to_created_date'];
        $supplier = $this->data['supplier'];
        $product = $this->data['product'];
        $category = $this->data['category'];
        $tax_code = $this->data['tax_code'];
        $discount_code = $this->data['discount_code'];
        $product_type = $this->data['product_type'];
        $status = $this->data['status'];


        $query = Product::query()
            ->select('products.*', 'category.id', 'suppliers.id', 'tax_codes.id', 'discount_codes.id', 'products.id as product_id')
            ->categoryJoin()
            ->supplierJoin()
            ->taxcodeJoin()
            ->discountcodeJoin();

        if ($from_created_date != '') {
            // $from_created_date = strtotime($from_created_date);
            // $from_created_date = date(config('app.sql_date_format'), $from_created_date);
            $from_created_date = $from_created_date . ' 00:00:00';
            $query = $query->where('products.created_at', '>=', $from_created_date);
        }
        if ($to_created_date != '') {
            // $to_created_date = strtotime($to_created_date);
            // $to_created_date = date(config('app.sql_date_format'), $to_created_date);
            $to_created_date = $to_created_date . ' 23:59:59';
            $query = $query->where('products.created_at', '<=', $to_created_date);
        }
        if ($supplier != '') {
            $query = $query->where('suppliers.slack', $supplier);
        }
        if ($product != '') {
            $query = $query->where('products.slack', $product);
        }
        if ($category != '') {
            $query = $query->where('category.slack', $category);
        }
        if ($tax_code != '') {
            $query = $query->where('tax_codes.slack', $tax_code);
        }
        if ($discount_code != '') {
            $query = $query->where('discount_codes.slack', $discount_code);
        }
        if (isset($status)) {
            $query = $query->where('products.status', $status);
        }

        $query = $query->when($product_type == 'billing_products', function ($query) {
            $query->mainProduct();
        });

        $query = $query->when($product_type == 'ingredients', function ($query) {
            $query->isIngredient();
        });

        $products = $query->get();

        $this->sold_quantities = [];
        $this->returned_quantities = [];

        if (isset($products)) {
            foreach ($products as $product) {

                $this->sold_quantities[$product['slack']] = OrderProduct::active()
                    ->join('orders', 'orders.id', 'order_products.order_id')
                    ->where('order_products.product_id', $product['product_id'])
                    ->sum('order_products.quantity');
                $this->total_sold_quantity += $this->sold_quantities[$product['slack']];

                $this->returned_quantities[$product['slack']] = ReturnOrdersProducts::active()
                    ->join('order_return', 'order_return.id', 'order_return_product.return_order_id')
                    ->where('order_return_product.product_id', $product['product_id'])
                    ->sum('order_return_product.quantity');
                $this->total_returned_quantity += $this->returned_quantities[$product['slack']];

                if ($product->quantity != "-1.00" && $product->quantity > 0) {
                    $this->total_quantity += $product->quantity;
                }
            }
        }

        $this->sale_amount_excluding_tax = $products->sum('sale_amount_excluding_tax');
        $this->purchase_amount_excluding_tax = $products->sum('purchase_amount_excluding_tax');
        $this->rows_count = $query->count() + 3;
        return $products;
    }

    public function headings(): array
    {
        $product_data = Product::where('slack', $this->data['product'])->first();
        $product = $product_data != null ? new ProductResource($product_data) : null;
        $supplier_data = Supplier::where('slack', $this->data['supplier'])->first();
        $category_data = Category::where('slack', $this->data['category'])->first();
        $discount_data = Discountcode::where('slack', $this->data['discount_code'])->first();
        $tax = Taxcode::where('slack', $this->data['tax_code'])->first();       
        return [
            [
            trans('Product Type: ') . ucfirst($this->data['product_type']),
            trans('Created Date: ') . $this->data['to_created_date'],
            trans('Supplier: ') . ($supplier_data !=null ? $supplier_data->name : ''),
            trans('Product: ') . ($product != null ? $product->name : ''),
            trans('Category: ') . ($category_data != null ? $category_data->label : ''),
            trans('Tax code: ') . ($tax != null ? $tax->label : ''),
            trans('Discount code: ') . ($discount_data != null ? $discount_data->label : ''),
            // trans('TAX CODE'),
            // trans('TAX PERCENTAGE'),
            // trans('DISCOUNT CODE'),
            // trans('DISCOUNT PERCENTAGE'),
            trans('Status: ') . ($this->data['status'] == 1 || $this->data['status'] == null ? 'Active' : 'Inactive'),
            // trans('SOLD QUANTITY'),
            // trans('RETURNED QUANTITY'),
            // trans('PURCHASE PRICE WITHOUT TAX'),
            // trans('SALE PRICE WITHOUT TAX'),
            trans('Printed at: ') . now(),
            // trans('CREATED AT'),
            // trans('CREATED BY'),
            // trans('UPDATED AT'),
            // trans('UPDATED BY'),
            ],
            [
                trans('PRODUCT CODE'),
                trans('NAME'),
                trans('BARCODE'),
                trans('SUPPLIER CODE'),
                trans('SUPPLIER NAME'),
                trans('CATEGORY CODE'),
                trans('CATEGORY NAME'),
                // trans('TAX CODE'),
                // trans('TAX PERCENTAGE'),
                // trans('DISCOUNT CODE'),
                // trans('DISCOUNT PERCENTAGE'),
                trans('QUANTITY'),
                // trans('SOLD QUANTITY'),
                // trans('RETURNED QUANTITY'),
                // trans('PURCHASE PRICE WITHOUT TAX'),
                // trans('SALE PRICE WITHOUT TAX'),
                trans('STATUS'),
                trans('CREATED AT'),
                trans('CREATED BY'),
                trans('UPDATED AT'),
                trans('UPDATED BY'),
            ]
        ];
    }

    public function map($product): array
    {
        $product = collect(new ProductResource($product));
        if(app()->getLocale() == 'ar'){
            $product_name = $product['name_ar']!=null?$product['name_ar']:$product['name'];
            $cat_name = $product['category']['label_ar']!=null?$product['category']['label_ar']:$product['category']['label'];
        }else{
            $product_name = $product['name'];
            $cat_name = $product['category']['label'];
        }
        return [
            (isset($product['product_code'])) ? $product['product_code'] : '',
            (isset($product_name)) ? $product_name : '',
            (isset($product['barcode'])) ? $product['barcode'] : '',

            (isset($product['supplier']['supplier_code'])) ? $product['supplier']['supplier_code'] : '',
            (isset($product['supplier']['name'])) ? $product['supplier']['name'] : '',

            (isset($product['category']['category_code'])) ? $product['category']['category_code'] : '',
            (isset($cat_name)) ? $cat_name : '',

            // (isset($product['tax_code']['tax_code']))?$product['tax_code']['tax_code']:'',
            // (isset($product['tax_code']['total_tax_percentage']))?$product['tax_code']['total_tax_percentage']:'',

            // (isset($product['discount_code']['discount_code']))?$product['discount_code']['discount_code']:'',
            // (isset($product['discount_code']['discount_percentage']))?$product['discount_code']['discount_percentage']:'',

            (isset($product['quantity'])) ? $product['quantity'] : '',
            // (isset($product['slack']) && $this->sold_quantities[$product['slack']] != null) ? $this->sold_quantities[$product['slack']] : '0',
            // (isset($product['slack']) && $this->returned_quantities[$product['slack']] != null) ? $this->returned_quantities[$product['slack']] : '0',
            // (isset($product['purchase_amount_excluding_tax'])) ? $product['purchase_amount_excluding_tax'] : '',
            // (isset($product['sale_amount_excluding_tax'])) ? $product['sale_amount_excluding_tax'] : '',

            (isset($product['status']['label'])) ? $product['status']['label'] : '',
            (isset($product['created_at_label'])) ? $product['created_at_label'] : '',
            (isset($product['created_by']['fullname'])) ? $product['created_by']['fullname'] : '',
            (isset($product['updated_at_label'])) ? $product['updated_at_label'] : '',
            (isset($product['updated_by']['fullname'])) ? $product['updated_by']['fullname'] : '',

        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle("A1:N1")->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle("A2:N2")->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue("A$this->rows_count", trans('Total'));
                $event->sheet->getDelegate()->getStyle("A$this->rows_count")->getFont()->setBold(true);
                $event->sheet->getDelegate()->setCellValue("H$this->rows_count", $this->total_quantity);
                // $event->sheet->getDelegate()->setCellValue("I$this->rows_count", $this->total_sold_quantity);
                // $event->sheet->getDelegate()->setCellValue("J$this->rows_count", $this->total_returned_quantity);
                // $event->sheet->getDelegate()->setCellValue("K$this->rows_count", $this->purchase_amount_excluding_tax);
                // $event->sheet->getDelegate()->setCellValue("L$this->rows_count", $this->sale_amount_excluding_tax);
            },
        ];
    }
}