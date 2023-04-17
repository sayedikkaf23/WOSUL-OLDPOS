<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\Resource;
use App\Models\OrderProductModifierOption as OrderProductModifierOptionModel;


use App\Models\Product as ProductModel;
use App\Models\Category as CategoryModel;

use App\Models\ReturnOrdersProducts;
use App\Models\DamageReportModel;
use Carbon\Carbon;


class OrderProductResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    public function toArray($request)
    {

        $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options','modifier_options.id','order_product_modifier_options.modifier_option_id')
                ->join('modifiers','modifiers.id','modifier_options.modifier_id')
                ->where('order_product_modifier_options.order_product_id',$this->id)
                ->select('modifiers.label as modifier_label','modifier_options.label as modifier_option_label','order_product_modifier_options.modifier_option_price as modifier_option_price')
                ->get();

        if(isset($this->order_product_modifier_options))
        {
            $order_product_modifier_options = ($this->order_product_modifier_options!="")?json_decode($this->order_product_modifier_options):[];
        }

        $product_detail = ProductModel::where('slack',$this->product_slack)->select('category_id','name_ar', 'description', 'description_ar','product_thumb_image', 'show_description_in')->first();

        // $master_details = ProductModel::where('slack',$this->product_slack)->get();
        // $master_details = $master_details[0]??[];
        $main_category_id = '';
        $product_name_ar = '';
        $product_description = '';
        $product_description_ar = '';
        $product_thumb_image = '';
        $show_description_in = 0;

        if(isset($product_detail)){
            $main_category_id = $product_detail->category_id;
            $product_name_ar = $product_detail->name_ar;
            $product_description = $product_detail->description;
            $product_description_ar = $product_detail->description_ar;
            $product_thumb_image = $product_detail->product_thumb_image;
            $show_description_in = $product_detail->show_description_in;
        }

        $product_category_id = '';
        $product_sub_category_id = '';
        if($main_category_id != ''){
            $category = CategoryModel::find($main_category_id);

            if($category->parent == 0 || $category->parent == null){
                $product_category_id = $category->id;
            }else{
                $product_category_id = $category->parent;
                $product_sub_category_id = $category->id;
            }
            
        }
        $is_already_returned = false;
        //print_r($this->order_id);die;
        $return_products = ReturnOrdersProducts::select('*')->where('product_slack', $this->slack)->get();
        $returned_quantity = 0;
        foreach($return_products as $return_product)
        {
          $returned_quantity += $return_product->quantity;
        }
        if(count($return_products)>0)
        {   
               $is_already_returned = true;
        }
    
        return [
            'slack' => $this->slack,
            'product_id'=>$this->product_id,
            'product_slack' => $this->product_slack,
            'product_code' => $this->product_code,
            'is_already_returned'=>$is_already_returned,
            'return_quantity'=>$returned_quantity,
            'name' => $this->name,
            'name_ar' => $product_name_ar,
            'description' => $product_description,
            'description_ar' => $product_description_ar,
            'show_description_in' => $show_description_in,
            'product_thumb_image' => $product_thumb_image,
            'quantity' => $this->quantity,
            'max_quantity' => $this->quantity,
            'purchase_amount_excluding_tax' => $this->purchase_amount_excluding_tax,
            'price' => $this->sale_amount_excluding_tax,
            'discount_code' => $this->discount_code,
            'discount_percentage' => $this->discount_percentage,
            'tax_code_id' => $this->tax_code_id,
            'tax_code' => $this->tax_code,
            'tax_percentage' => $this->tax_percentage,
            'tax_components' => json_decode($this->tax_components),
            'tobacco_tax_components' => json_decode($this->tobacco_tax_components),
            'sub_total_purchase_price_excluding_tax' => $this->sub_total_purchase_price_excluding_tax,
            'sub_total' => $this->sub_total_sale_price_excluding_tax,
            'discount_amount' => $this->discount_amount,
            'total_after_discount' => $this->total_after_discount,
            'tax_amount' => $this->tax_amount,
            'total_price' => $this->total_amount,
            'is_ready_to_serve' => $this->is_ready_to_serve,
            'status' => new MasterStatusResource($this->status_data),
            'created_at_label' => ($this->created_at != null)?Carbon::parse($this->created_at)->format(config("app.date_time_format")):null,
            'updated_at_label' => ($this->updated_at != null)?Carbon::parse($this->updated_at)->format(config("app.date_time_format")):null,
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'modifier_option_id' => $this->modifier_option_id,
            'modifier_option_amount' => $this->modifier_option_amount,
            'total_modifier_option_amount' => $this->total_modifier_option_amount,
            'order_product_modifier_options' => ( !empty($order_product_modifier_options) ) ? $order_product_modifier_options : [],
            'total_modifier_amount' => $this->total_modifier_amount,
            'note' => $this->note,
            'product_category_id' => $product_category_id,
            'product_sub_category_id' => $product_sub_category_id,
            'bonat_discount' => $this->bonat_discount,
            'is_gifted' => $this->is_gifted,
            'combo_id' => $this->combo_id,
            'combo_cart_id' => $this->combo_cart_id,
            'combo_name' => $this->combo_name,
            // 'product_master_details' => $master_details, 
        ];
    }
}