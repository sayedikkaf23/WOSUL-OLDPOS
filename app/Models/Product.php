<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class Product extends Model
{


    protected $table = 'products';
    protected $fillable = ['id', 'slack', 'store_id', 'product_code', 'name', 'name_ar', 'description', 'description_ar', 'category_id', 'supplier_id',  'discount_code_id',
    'quantity', 'alert_quantity', 'tax_code_id','is_tobacco_tax','tobacco_tax_percentage','purchase_amount_excluding_tax', 'sale_amount_excluding_tax', 'sale_amount_including_tax',
    'price_type','is_ingredient', 'shows_in', 'show_description_in', 'is_ingredient_price', 'status', 'created_by', 'updated_by', 'barcode', 'brand_id', 'measurement_id', 
    'product_thumb_image', 'product_border_color', 'product_manufacturer_date', 'product_expiry_date', 'is_taxable', 'sales_price_including_boolean_and_price', 'zid_product_id','zid_parent_product_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeActive($query)
    {
        return $query->where('products.status', 1);
    }

    public function scopeSortNameAsc($query)
    {
        return $query->orderBy('products.name', 'asc');
    }

    public function scopeProductTypeNowhere($query)
    {
        return $query->where('products.shows_in', 0);
    }

    public function scopeAllExceptNoWhere($query)
    {
        return $query->where('products.shows_in', '!=', 0);
    }

    public function scopeProductTypePos($query)
    {
        return $query->whereIn('products.shows_in', [1, 3]);
    }

    public function scopeProductTypeInvoice($query)
    {
        return $query->whereIn('products.shows_in', [2, 3]);
    }

    public function scopeNoSupplierProduct($query)
    {
        return $query->where('products.supplier_id', 0);
    }

    public function scopeSupplierProduct($query)
    {
        return $query->where('products.supplier_id', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('products.quantity <= products.alert_quantity');
    }

    

    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'products.status');
            $join->where('master_status.key', '=', 'PRODUCT_STATUS');
        });
    }

    public function scopeCategoryJoin($query)
    {
        return $query->leftJoin('category', function ($join) {
            $join->on('category.id', '=', 'products.category_id');
        });
    }

    // public function scopeMainCategoryJoin($query){
    //     return $query->leftJoin('main_category', function ($join) {
    //         $join->on('main_category.id', '=', 'products.main_category_id');
    //     });
    // }

    public function scopeSupplierJoin($query)
    {
        return $query->leftJoin('suppliers', function ($join) {
            $join->on('suppliers.id', '=', 'products.supplier_id');
        });
    }

    public function scopeTaxcodeJoin($query)
    {
        return $query->leftJoin('tax_codes', function ($join) {
            $join->on('tax_codes.id', '=', 'products.tax_code_id');
        });
    }

    public function scopeDiscountcodeJoin($query)
    {
        return $query->leftJoin('discount_codes', function ($join) {
            $join->on('discount_codes.id', '=', 'products.discount_code_id');
        });
    }

    public function scopeCategoryActive($query)
    {
        return $query->where('category.status', 1);
    }

    public function scopeSupplierActive($query)
    {
        return $query->where('suppliers.status', 1);
    }
    
    public function getIsNoTaxAttribute()
    {
        $value = false;
        if($this->tax_code_id != ''){
            $tax_code = Taxcode::where('id',$this->tax_code_id)->first();
            if($tax_code->tax_code == 'NO_TAX'){
                $value = true;
            }
        }
        return $value;
    }

    public function scopeTaxcodeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('tax_codes.status', 1)
                ->orWhereNull('tax_codes.status');
        });
    }

    public function scopeDiscountcodeActive($query)
    {
        return $query->where('discount_codes.status', 1);
    }

    public function scopeMainProduct($query)
    {
        return $query->where('products.is_ingredient', 0);
    }

    public function scopeIsIngredient($query)
    {
        return $query->where('products.is_ingredient', 1);
    }

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'products.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'products.updated_by');
        });
    }

    public function scopeQuantityCheck($query, $quantity = '')
    {
        if ($quantity != "") {

            $query->where('products.quantity', '>=', $quantity);
            $query->orWhere('products.quantity', '=', -1);
            return $query;
        } else {
            return $query->where('products.quantity', '>', 0);
        }
    }

    public function scopeRemoveUnlimitedQuantity($query)
    {
       return $query->where('products.quantity', '>', 0);
    }

    public function scopeOrderProduct($query)
    {
        return $query->leftJoin('order_products', function ($join) {
            $join->on('order_products.product_id', '=', 'products.id');
        });
    }

    public function scopeOrderJoin($query)
    {
        return $query->leftJoin('orders', function ($join) {
            $join->on('orders.id', '=', 'order_products.order_id');
        });
    }

    // public function scopeModifierJoin($query){
    //     return $query->leftJoin('modifiers', function ($join) {
    //         $join->on('modifiers.id','products.modifier_id');
    //     });
    // }

    public function scopeProductImage($query)
    {
        return $query->leftJoin('product_images', function ($join) {
            $join->on('product_images.product_id', '=', 'products.id');
        });
    }
    public function scopeQoyodProductJoin($query)
    {
        return $query->leftJoin('qoyod_products', function ($join) {
            $join->on('qoyod_products.product_id', '=', 'products.id');
        });
    }

    public function getMeasurementUnitsAttribute(){
        $data = null;
        if($this->measurement_id != '' || !is_null($this->measurement_id)){
            $measurement_category_id = Measurement::find($this->measurement_id)->measurement_category_id;
            $measurements = Measurement::where('measurement_category_id',$measurement_category_id)->active()->get();
            if(isset($measurements)){
                $data = $measurements;
            }
        }
        return $data;
    }

    /* For view files */

    public function product_prices(){
        return $this->hasMany('App\Models\ProductPrice','product_id','id');
    }

    public function createdUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function updatedUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by')->select(['slack', 'fullname', 'email', 'user_code']);
    }

    public function status_data()
    {
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'PRODUCT_STATUS');
    }

    public function supplier()
    {
        return $this->hasOne('App\Models\Supplier', 'id', 'supplier_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function tax_code()
    {
        return $this->hasOne('App\Models\Taxcode', 'id', 'tax_code_id');
    }

    public function discount_code()
    {
        return $this->hasOne('App\Models\Discountcode', 'id', 'discount_code_id');
    }

    public function product_images()
    {
        return $this->hasMany('App\Models\ProductImages', 'product_id', 'id')->active();
    }

    public function storeData()
    {
        return $this->hasOne('App\Models\Store', 'id', 'store_id');
    }

    public function ingredients()
    {
        return $this->hasMany('App\Models\ProductIngredient', 'product_id', 'id');
    }

    public function measurements()
    {
        return $this->hasOne('App\Models\Measurement', 'id', 'measurement_id');
    }

    public function order_products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'product_id', 'id');
    }

    public function product_modifiers()
    {
        return $this->hasMany('App\Models\ProductModifier', 'product_id', 'id');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }

    public function parseDateOnly($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_format")) : null;
    }
}
