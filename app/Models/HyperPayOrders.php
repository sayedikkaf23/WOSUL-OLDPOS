<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HyperPayOrders extends Model
{
    protected $table = 'orders';
    protected $fillable = ['id', 'order_number', 'merchant_id','delivery_address_line_1', 'delivery_address_line2','delivery_address_city','delivery_address_zipcode','delivery_address_country','order_amount','tax_rate','tax_amount','discount_rate','discount_amount','total_amount','status','payment_status','payment_method_id','updated_at','created_at','country'];

    public function scopePaymentTypeJoin($query){
        return $query->leftJoin('payment_methods', function ($join) {
            $join->on('payment_methods.id', '=', 'orders.payment_method_id');
        });
    }
}
