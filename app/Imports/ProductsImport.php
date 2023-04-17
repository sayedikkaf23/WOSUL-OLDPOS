<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

use App\Models\Product as ProductModel;
use App\Models\Category as CategoryModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\Supplier as SupplierModel;

class ProductsImport implements ToModel, WithBatchInserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    private $row_count = 0;

    public function model(array $row)
    {   
        $row_count = ++$this->row_count;

        if($row_count > 1){
            
            $category = CategoryModel::where('category_code',$row[3])->first();

            if($row[6] != ''){
                $currentdate = date('Y-m-d H:i:sa');
                $discount = DiscountcodeModel::where('discount_code',$row[6])
                ->whereRaw("discounttype='code'")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
                ->active()
                ->first();
            }

            if($row[2] != ''){
                $supplier = SupplierModel::where('supplier_code',$row[2])->first();
            }

            $shows_in_values = [
                'POS' => 1,
                'INVOICE' => 2,
                'BOTH' => 3,
                'NOWHERE' => 4
            ];

            $shows_in_keys = array_keys($shows_in_values);

            if(empty($category)){
           
                echo "Entered Category [".$row[3]."] not found in our system on row number [".$row_count."]";
           
            }else if($row[6] != '' && empty($discount) ) {
                
                echo "Entered Discount Code [".$row[6]."] not found in our system on row number [".$row_count."]";

            }else if($row[2] != '' && empty($supplier) ) {
                
                echo "Entered Supplier Code [".$row[2]."] not found in our system on row number [".$row_count."]";

            }else if( $row[5] == "" || !in_array(strtoupper($row[5]), $shows_in_keys)) {

                echo "Entered Shows in [".$row[5]."] not found in our system on row number [".$row_count."], it must be from following values POS, INVOICE, BOTH, NOWHERE ";

            }else{

                $product = [
                    "slack" => generate_slack("products"),
                    "store_id" => session('store_id'),
                    "name" => $row[1],
                    "product_code" => strtoupper($row[0]),
                    "description" => $row[12],
                    "category_id" => $category->id,
                    "discount_code_id" => (isset($discount)) ? $discount->id : '',
                    "alert_quantity" => ($row[11] == '') ? 0 : $row[11],
                    "purchase_amount_excluding_tax" => $row[7],
                    "sale_amount_excluding_tax" => $row[8],
                    // "sale_amount_including_tax" => $row[9],
                    "status" => (strtoupper(trim($row[13])) == 'INACTIVE' || trim($row[13]) == '') ? 2 : 1,
                    "shows_in" => $shows_in_values[strtoupper(trim($row[5]))],
                    "created_by" => session('user_id'),
                    "barcode" => trim($row[4]),
                    "quantity" => ($row[10] == "UNLIMITED" ) ? -1 : $row[10],
                    "supplier_id" => (isset($supplier)) ? $supplier->id : '',
                    "sales_price_including_boolean_and_price"=>$row[9]
                ];

                return new ProductModel($product);

            }

        }

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

}
