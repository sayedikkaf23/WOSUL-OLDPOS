<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Category as CategoryModel;
use App\Models\ProductPrice;
use App\Models\Store;
use Illuminate\Support\Facades\App;

class ProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $price_id = Store::find($request->logged_user_store_id)->price_id;

        $product_price = ProductPrice::where('price_id',$price_id)->where('product_id',$this->id)->first();
        
        $base_sale_amount_excluding_tax = $this->sale_amount_excluding_tax;
        $base_sale_amount_including_tax = $this->sale_amount_including_tax;
        $price_tag = '';
        
        if(isset($product_price)){
            $product_price = new ProductPriceResource($product_price);
            $this->sale_amount_excluding_tax = $product_price['sale_amount_excluding_tax'];
            $this->sale_amount_including_tax = $product_price['sale_amount_including_tax'];
            $price_tag = ( isset($product_price['price']) ) ? $product_price['price']['name'] : '';
        }
        
        $ingredients = ProductIngredientResource::collection($this->ingredients);

        $ingredients_collection = collect($ingredients);
        $low_ingredient_stock = $ingredients_collection->map(function ($item, $key) {
            return $item['low_stock'];
        })->toArray();
        $low_ingredient_stock = (!empty($low_ingredient_stock)) ? in_array(1, $low_ingredient_stock) : false;

        $category_id = 0;
        $subcategory_id = 0;

        if (isset($this->category_id) && $this->category_id != 0) {

            $category = CategoryModel::find($this->category_id);

            if($category != null)
            {
                if ($category->parent == 0 || $category->parent == null) {
                    $category_id = $category->id;
                    $subcategory_id = 0;
                } else {
                    $category_id = $category->parent;
                    $subcategory_id = $category->id;
                }    
            }
            else
            {
                $category_id = $this->category_id;
            }
        }

        return [
            'id' => $this->id,
            'slack' => $this->slack,
            'product_code' => $this->product_code,
            'name' => $this->name,
            'name_ar' => $this->name_ar,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'description_ar' => $this->description_ar,
            'quantity' => ($this->quantity == "-1.00") ? 'Unlimited' : $this->quantity,
            'alert_quantity' => $this->alert_quantity,
            'purchase_amount_excluding_tax' => $this->purchase_amount_excluding_tax,
            'base_sale_amount_excluding_tax' => $base_sale_amount_excluding_tax,
            'sale_amount_excluding_tax' => $this->sale_amount_excluding_tax,
            'base_sale_amount_including_tax' => $base_sale_amount_including_tax,
            'sale_amount_including_tax' => $this->sale_amount_including_tax,
            'price_tag' => $price_tag,
            'price_type' => $this->price_type,
            'category' =>  $this->category,
            'category_id' =>  $category_id,
            'subcategory_id' => $subcategory_id,
            'supplier' => new SupplierResource($this->supplier),
            'tax_code_id' => $this->tax_code_id,
            'is_tobacco_tax' => $this->is_tobacco_tax,
            'tobacco_tax_percentage' => $this->tobacco_tax_percentage,
            'tax_code_label' => $this->tax_code_label,
            'tax_percentage' => $this->total_tax_percentage,
            'tax_code' => new TaxcodeResource($this->tax_code),
            'is_taxable' => $this->is_taxable,
            'is_no_tax' => $this->is_no_tax,
            'discount_code' => new DiscountcodeResource($this->discount_code),
            'discount_code_id'=>$this->discount_code_id,
            'images' => ProductImageResource::collection($this->product_images),
            'is_ingredient' => $this->is_ingredient,
            'is_ingredient_price' => $this->is_ingredient_price,
            'ingredients' => $ingredients,
            'ingredient_low_stock' => $low_ingredient_stock,
            'status' => new MasterStatusResource($this->status_data),
            'detail_link' => (check_access(['A_DETAIL_PRODUCT'], true)) ? route('product', ['slack' => $this->slack]) : '',
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'inventory_type' => $this->inventory_type,
            'shows_in' => $this->shows_in,
            'show_description_in' => $this->show_description_in,                       
            'barcode' => $this->barcode,
            'brand_id' => $this->brand_id,
            'measurement_id' => $this->measurement_id,
            'measurements' => $this->measurements,
            'measurement_units' => $this->measurement_units,
            'product_thumb_image' => $this->product_thumb_image,
            'product_border_color' => $this->product_border_color,
            'product_manufacturer_date' => $this->parseDateOnly($this->product_manufacturer_date),
            'product_manufacturer_date_raw' => $this->product_manufacturer_date,
            'product_expiry_date' => $this->parseDateOnly($this->product_expiry_date),
            'product_expiry_date_raw' => $this->product_expiry_date,
            'modifier_id' => $this->modifier_id,
            'product_modifiers' => ProductModifierOptionResource::collection($this->product_modifiers),
            'zid_product_id' => $this->zid_product_id,
            'bonat_discount' => isset($this->bonat_discount) ? $this->bonat_discount : 0,
            'bonat_price' =>  isset($this->bonat_price) ? $this->bonat_price : 0,
            'bonat_coupon' =>  isset($this->bonat_coupon) ? $this->bonat_coupon : null,
            'product_prices' => ProductPriceResource::collection($this->product_prices),
        ];
    }
}
