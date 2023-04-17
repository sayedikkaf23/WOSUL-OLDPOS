<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductBarcodeDetail extends Model
{
    protected $fillable = ['product_id','barcode_no','product_slack','is_ingredient','quantity', 'show_barcode_value', 'show_item_name', 'show_item_price_with_vat',
                            'show_store_name','store_id','show_manufacturing_date','manufacturing_date','show_expiry_date','expiry_date',
                            'show_notes','notes','status','created_by','updated_by'
                        ];
                
    protected static function boot()
    {
        parent::boot();
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'product_barcode_details.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'product_barcode_details.updated_by');
        });
    }

    public function scopeIsIngredient($query)
    {
        return $query->where('product_barcode_details.is_ingredient', 1);
    }

    public function createdUser(){
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function products(){
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function storeData()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    public function updatedUser(){
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function parseDateOnly($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_format")) : null;
    }
}
