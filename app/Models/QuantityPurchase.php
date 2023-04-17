<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class QuantityPurchase extends Model
{
    protected $table = 'quantity_purchases';
    protected $hidden = [ 'store_id', 'supplier_id'];
    protected $fillable = ['id','slack', 'store_id', 'po_number', 'business_account_id', 'discount_type','discount_rate','po_reference', 'order_date', 'order_due_date', 'supplier_id', 'supplier_code', 'supplier_name', 'supplier_address', 'currency_name', 'currency_code', 'tax_option_id', 'subtotal_excluding_tax', 'total_discount_amount', 'total_after_discount', 'total_tax_amount', 'shipping_charge', 'packing_charge', 'total_order_amount', 'terms', 'update_stock', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at','transaction_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeActive($query){
        return $query->where('status', 1);
    }

    public function scopeClosed($query){
        return $query->where('status', 4);
    }

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'quantity_purchases.status');
            $join->where('master_status.key', '=', 'QUANTITY_PURCHASE_STATUS');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'quantity_purchases.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'quantity_purchases.updated_by');
        });
    }

    /* For view files */

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function storeData(){
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }
    
    public function supplier(){
        return $this->hasOne('App\Models\Supplier', 'id', 'supplier_id');
    }

    public function products(){
        return $this->hasMany('App\Models\QuantityPurchaseProduct', 'purchase_order_id', 'id')->where('quantity_purchase_products.status', 1);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'QUANTITY_PURCHASE_STATUS');
    }

    public function tax_option_data(){
        return $this->hasOne('App\Models\MasterTaxOption', 'id', 'tax_option_id')->where('master_tax_option.status', 1);
    }

    public function parseDateOnly($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_format")):null;
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }
}
