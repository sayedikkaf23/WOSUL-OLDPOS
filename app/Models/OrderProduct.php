<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'order_products';
    protected $hidden = ['id', 'order_id'];
    protected $fillable = ['slack', 'order_id', 'product_id', 'product_slack', 'product_code', 'name', 'quantity', 'purchase_amount_excluding_tax', 
        'sale_amount_excluding_tax', 'sub_total_purchase_price_excluding_tax', 'sub_total_sale_price_excluding_tax', 'tax_code_id', 'tax_code', 'tax_percentage',
        'tax_amount', 'tax_components', 'tobacco_tax_components', 'discount_code_id', 'discount_code', 'discount_percentage', 'discount_amount', 
        'total_after_discount', 'total_amount', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','total_modifier_amount','note','bonat_discount','bonat_discount_price','bonat_coupon','is_gifted','combo_id','combo_cart_id'];

    public function scopeProduct($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'order_products.product_id');
        });
    }

    public function scopeActive($query){
        return $query->where('order_products.status', 1);
    }

    public function scopeOrderJoin($query){
        return $query->leftJoin('orders', function ($join) {
            $join->on('orders.id', '=', 'order_products.order_id');
        });
    }

    public function scopeProductJoin($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'order_products.product_id');
        });
    }

    public function scopeCategoryJoin($query){
        return $query->leftJoin('category', function ($join) {
            $join->on('category.id', '=', 'products.category_id');
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

    public function modifier_option(){
        return $this->belongsTo('App\Models\ModifierOption');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order','order_number','order_number');
    }
    
    public function getComboNameAttribute(){

        if(isset($this->combo_id) && $this->combo_id != '' && $this->combo_id!=0){
            return Combo::find($this->combo_id)->name;
        }else{
            return '';
        }
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
