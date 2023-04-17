<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Scopes\StoreScope;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\CategoryStoreScope;

class Category extends Model
{
    protected $table = 'category';
    protected $hidden = ['store_id'];
    protected $fillable = ['slack', 'parent','store_id', 'category_code', 'label', 'label_ar', 'description', 'description_ar', 'category_applied_on', 'category_applicable_stores', 'status', 'created_by', 'updated_by','category_image','zid_category_id','zid_parent_category_id'];

    protected static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new StoreScope);
        // static::addGlobalScope(new CategoryStoreScope);
    }

    /* Accessors and Mutators */

    public function scopeActive($query){
        return $query->where('category.status', 1);
    }

    public function scopeSortLabelAsc($query){
        return $query->orderBy('category.label', 'asc');
    }

    public function scopeWithCategoryType($query){
        return $query->selectRaw('"Sub Category" AS category_type');
    }

    public function scopeCategoryStore($query)
    {
        $store_id = isset(request()->logged_user_store_id) ? request()->logged_user_store_id : session('store_id');   
        return $query->whereRaw('FIND_IN_SET(?, category_applicable_stores)', [$store_id]);
    }

    public function scopeParentCategory($query){
        return $query->where('parent',0)->orWhere('parent',null);
    }

    public function scopeChildCategory($query){
        return $query->where('parent','!=',0);
    }

    /* Joins */

    public function scopeStatusJoin($query){
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'category.status');
            $join->where('master_status.key', '=', 'CATEGORY_STATUS');
        });
    }

    public function scopeProductJoin($query){
        return $query->leftJoin('products', function ($join) {
            $join->on('products.category_id', '=', 'category.id');
        });
    }

    public function scopeOrderProductJoin($query){
        return $query->leftJoin('order_products', function ($join) {
            $join->on('order_products.product_id', '=', 'products.id');
        });
    }

    public function scopeOrderJoin($query){
        return $query->leftJoin('orders', function ($join) {
            $join->on('orders.id', '=', 'order_products.order_id');
        });
    }

    public function scopeCreatedUser($query){
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'category.created_by');
        });
    }

    public function scopeUpdatedUser($query){
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'category.updated_by');
        });
    }

    public function scopeQoyodCategoryJoin($query){
        return $query->leftJoin('qoyod_categories', function ($join) {
            $join->on('qoyod_categories.category_id', '=', 'category.id');
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'CATEGORY_STATUS');
    }

    public function parseDate($date){
        return ($date != null)?Carbon::parse($date)->format(config("app.date_time_format")):null;
    }

    public function mainCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent');
    }

    public function subCategories()
    {
        return $this->hasMany('App\Models\Category', 'parent', 'id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id', 'id');
    }

    public function productsSlacks()
    {
        return $this->products->pluck('slack');
    }    

}
