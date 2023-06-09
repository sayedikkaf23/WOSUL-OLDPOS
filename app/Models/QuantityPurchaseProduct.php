<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class QuantityPurchaseProduct extends Model
{
    protected $table = 'quantity_purchase_products';
    protected $hidden = ['id', 'purchase_order_id'];
    protected $fillable = ['slack', 'purchase_order_id', 'product_id', 'product_slack', 'product_code', 'name', 'quantity', 'amount_excluding_tax', 'discount_percentage', 'tax_percentage', 'discount_amount', 'total_after_discount', 'tax_amount', 'total_amount', 'stock_update', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'];

    protected $appends = ['product_detail'];

    public function scopeProduct($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'quantity_purchase_products.product_id');
        });
    }

    public function scopeActive($query){
        return $query->where('quantity_purchase_products.status', 1);
    }

    /* For view files */
    
    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'QUANTITY_PURCHASE_PRODUCT_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function product(){
        return $this->belongsTo('App\Models\Product','product_id','id');
    }


}
