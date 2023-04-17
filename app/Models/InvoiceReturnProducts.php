<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvoiceReturnProducts extends Model
{
    protected $table = 'invoice_return_products';
    protected $hidden = ['id', 'return_invoice_id'];
    protected $fillable = ['slack', 'return_invoice_id','return_invoice_slack', 'product_id', 'product_slack', 'product_code', 'name', 'quantity', 'amount_excluding_tax', 'subtotal_amount_excluding_tax', 'discount_percentage', 'tax_code_id', 'tax_percentage', 'discount_amount', 'total_after_discount', 'tax_amount', 'tax_components', 'total_amount', 'measurement_id', 'status', 'product_type', 'created_by', 'updated_by', 'created_at', 'updated_at','is_wastage'];

    public function scopeProduct($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.id', '=', 'invoice_return_products.product_id');
        });
    }

    public function scopeActive($query){
        return $query->where('invoice_return_products.status', 1);
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'invoice_return_products.created_by');
        });
    }

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data(){
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'INVOICE_PRODUCT_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function returnInvoice(){
        return $this->hasOne('App\Models\InvoiceReturn', 'id', 'return_invoice_id')->select(['id', 'bill_to_id','store_id', 'return_invoice_number'])->with('customer');
    }

    public function Invoice(){
        return $this->hasOne('App\Models\InvoiceReturn', 'id', 'return_invoice_id');
    }


}
