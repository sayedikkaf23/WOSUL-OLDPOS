<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ReturnOrdersProducts extends Model
{
    protected $table = 'order_return_product';
    protected $fillable = ['slack','order_product_modifier_options', 'return_order_id', 'product_id','order_id','order_slack', 'product_slack', 'product_code', 'name', 'quantity', 'purchase_amount_excluding_tax', 'sale_amount_excluding_tax', 'sub_total_purchase_price_excluding_tax', 'sub_total_sale_price_excluding_tax', 'tax_code_id', 'tax_code', 'tax_percentage', 'tax_amount', 'tax_components','tobacco_tax_components', 'discount_code_id', 'discount_code', 'discount_percentage', 'discount_amount', 'total_after_discount', 'total_amount', 'status', 'return_type','created_by', 'updated_by', 'created_at', 'updated_at','is_wastage','is_ready_to_serve','branch','branch_reference','added_by','order_reference','time','reason','combo_id','combo_cart_id'];

    public function scopeProduct($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'order_return_product.product_id');
        });
    }

    public function scopeActive($query){
        return $query->where('order_return_product.status', 1);
    }

    public function scopeOrderJoin($query){
        return $query->leftJoin('orders', function ($join) {
            $join->on('orders.id', '=', 'order_return_product.return_order_id');
        });
    }

    public function scopeProductJoin($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'order_return_product.product_id');
        });
    }

    public function scopeCategoryJoin($query){
        return $query->leftJoin('category', function ($join) {
            $join->on('category.id', '=', 'products.category_id');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'order_return_product.created_by');
        });
    }

    public function scopeComboJoin($query){
        return $query->leftJoin('combos', function ($join) {
            $join->on('combos.id', '=', 'order_return_product.combo_id');
        });
    }


    /* For view files */
    
    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'ORDER_PRODUCT_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function returnOrder(){
        return $this->hasOne('App\Models\ReturnOrders', 'id', 'return_order_id')->select(['id', 'customer_id', 'return_order_number'])->with('customer_data');
    }

    public function order(){
        return $this->hasOne('App\Models\ReturnOrders', 'id', 'return_order_id');
    }

}