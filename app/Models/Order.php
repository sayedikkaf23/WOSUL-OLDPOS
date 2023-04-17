<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class Order extends Model
{
    protected $table = 'orders';
    // protected $hidden = ['id'];
    protected $fillable = ['slack', 'store_id', 'order_number', 'device_id', 'customer_id', 'customer_phone', 'customer_email', 'counter_slack', 'counter_name', 'register_id', 'store_level_discount_code_id', 'store_level_discount_code', 'store_level_total_discount_percentage', 'store_level_total_discount_amount', 'product_level_total_discount_amount', 'store_level_tax_code_id', 'store_level_tax_code', 'store_level_total_tax_percentage', 'store_level_total_tax_amount', 'store_level_total_tax_components', 'product_level_total_tax_amount', 'purchase_amount_subtotal_excluding_tax', 'sale_amount_subtotal_excluding_tax', 'total_discount_before_additional_discount', 'total_amount_before_additional_discount', 'additional_discount_percentage', 'additional_discount_amount', 'total_discount_amount', 'total_after_discount', 'total_tax_amount', 'total_order_amount', 'total_order_amount_rounded', 'payment_method_id', 'payment_method_slack', 'payment_method', 'currency_name', 'currency_code', 'business_account_id', 'order_type_id', 'order_type', 'restaurant_mode', 'table_id', 'table_number', 'waiter_id', 'bill_type_id', 'bill_type', 'status', 'kitchen_status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'discount_type', 'payment_option', 'cash_amount', 'change_amount', 'card_name', 'credit_amount', 'value_date', 'transaction_id', 'bonat_discount', 'reference_number','has_combo','nearpay_json'];
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeClosed($query)
    {
        return $query->where('orders.status', 1);
    }

    public function scopeHold($query)
    {
        return $query->where('orders.status', 2);
    }

    public function scopeInkitchen($query)
    {
        return $query->where('orders.status', 5);
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'orders.status');
            $join->where('master_status.key', '=', 'ORDER_STATUS');
        });
    }

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'orders.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'orders.updated_by');
        });
    }

    /* For view files */

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id')->where('order_products.status', 1);
    }

    public function storeData()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    public function createdUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function waiterUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'waiter_id')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Transaction', 'bill_to_id', 'id')->where('transactions.bill_to', 'POS_ORDER')->orderBy('transactions.transaction_date', 'desc');
    }

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'ORDER_STATUS');
    }

    public function kitchen_status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'kitchen_status')->where('key', 'ORDER_KITCHEN_STATUS');
    }

    public function order_type_data()
    {
        return $this->hasOne('App\Models\MasterOrderType', 'id', 'order_type_id');
    }

    public function customer_data()
    {
        return $this->hasOne('App\Models\Customer', 'id', 'customer_id');
    }

    public function restaurant_table_data()
    {
        return $this->hasOne('App\Models\Table', 'id', 'table_id');
    }

    public function billing_type_data()
    {
        return $this->hasOne('App\Models\MasterBillingType', 'id', 'bill_type_id');
    }
    
    public function tax_code()
    {
        return $this->hasOne('App\Models\Taxcode', 'id', 'tax_code_id')->where('status', 1);
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }
}
