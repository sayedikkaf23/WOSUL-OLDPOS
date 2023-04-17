<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\Order as OrderModel;

class Store extends Model
{
    protected $table = 'stores';
    protected $hidden = ['store_id', 'discount_code_id', 'tax_code_id', 'created_by', 'updated_by'];
    protected $fillable = [
        'id', 'slack', 'store_code', 'name', 'tax_number', 'vat_number', 'tax_code_id', 'discount_code_id', 'address', 'country_id', 'pincode', 'primary_contact', 'secondary_contact', 'primary_email', 'secondary_email', 'invoice_type', 'currency_name', 'currency_code', 'restaurant_mode', 'restaurant_waiter_role_id', 'restaurant_billing_type_id', 'enable_customer_popup', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at', 'bank_name', 'bank_account_number', 'bank_ifsc_code', 'bank_upi_code', 'pos_invoice_policy_information', 'invoice_policy_information', 'store_invoice_color', 'store_logo', 'zid_store_api_token', 'zid_store_id', 'building_number', 'street_name', 'district', 'city', 'other_seller_id', 'store_opening_time',
        'store_closing_time',
        'is_store_closing_next_day', 'tax_registration_name', 'idle_time', 'idle_time_status','platform_type','platform_mode','price_id','is_price_enabled'
    ];

    public function scopeActive($query)
    {
        return $query->where('stores.status', 1);
    }

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'stores.status');
            $join->where('master_status.key', '=', 'STORE_STATUS');
        });
    }

    public function scopeTaxcodeJoin($query)
    {
        return $query->leftJoin('tax_codes', function ($join) {
            $join->on('tax_codes.id', '=', 'stores.tax_code_id');
        });
    }

    public function scopeDiscountcodeJoin($query)
    {
        return $query->leftJoin('discount_codes', function ($join) {
            $join->on('discount_codes.id', '=', 'stores.discount_code_id');
        });
    }

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'stores.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'stores.updated_by');
        });
    }

    public function scopeUserStores($query)
    {
        return $query->leftJoin('user_stores', function($join) {
            $join->on('stores.id', '=', 'user_stores.store_id');
        })->where('user_stores.user_id', '=', request()->logged_user_id);
    }

    public function scopeQoyodInventoryJoin($query){
        return $query->leftJoin('qoyod_inventory', function ($join) {
            $join->on('qoyod_inventory.store_id', '=', 'stores.id');
        });
    }

    public function getStoreLogoPathAttribute()
    {
        if(Storage::disk('store')->has($this->store_logo)){
            return Storage::disk('store')->url($this->store_logo);
        }else{
            return asset('images/logo-icon.png');
        }
    }

    public function getLastReferenceNumberAttribute()
    {
        $last_reference_number = OrderModel::withoutGlobalScopes()->select('reference_number')->where('store_id', $this->id)->orderBy('id', 'DESC')->first();
        if (isset($last_reference_number)) {
            return (int) $last_reference_number->reference_number;
        } else {
            return 0;
        }
    }

    public function getStoreOpeningTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getStoreClosingTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
    
    public function getIdleTimeInMinutesAttribute($value)
    {
        if ($this->attributes['idle_time'] > 0){
            $seconds =  $this->attributes['idle_time'] % 60;
            return ($this->attributes['idle_time'] - $seconds) / 60;
        }else{
            return 0;
        }
    }
    
    public function getIdleTimeInSecondsAttribute($value)
    {
        if ($this->attributes['idle_time'] > 0){
            return $this->attributes['idle_time'] % 60;
        }else{
            return 0;
        }
    }

    /* For view files */

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'STORE_STATUS');
    }

    public function tax_code()
    {
        return $this->hasOne('App\Models\Taxcode', 'id', 'tax_code_id')->where('status', 1);
    }

    public function discount_code()
    {
        return $this->hasOne('App\Models\Discountcode', 'id', 'discount_code_id')->where('status', 1);
    }

    public function invoice_print_type()
    {
        return $this->hasOne('App\Models\MasterInvoicePrintType', 'print_type_value', 'invoice_type')->where('status', 1);
    }

    public function waiter_role_data()
    {
        return $this->hasOne('App\Models\Role', 'id', 'restaurant_waiter_role_id')->where('roles.status', 1);
    }

    public function zid_store()
    {
        return $this->hasOne('App\Models\ZidStore', 'store_id', 'id');
    }

    public function restaurant_billing_type()
    {
        return $this->hasOne('App\Models\MasterBillingType', 'id', 'restaurant_billing_type_id')->where('master_billing_type.status', 1);
    }

    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id')->where('status', 1);
    }

    public function createdUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }
}
