<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\StoreScope;

class MainCategory extends Model
{
    protected $table = 'main_category';
    protected $hidden = ['store_id'];
    protected $fillable = ['slack', 'store_id', 'category_code', 'label', 'label_ar', 'description', 'description_ar', 'category_applied_on', 'category_applicable_stores', 'status', 'created_by', 'updated_by', 'category_image', 'zid_category_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StoreScope);
    }

    public function scopeActive($query)
    {
        return $query->where('main_category.status', 1);
    }

    public function scopeSortLabelAsc($query)
    {
        return $query->orderBy('main_category.label', 'asc');
    }


    public function scopeStatusJoin($query)
    {
        return $query->leftJoin('master_status', function ($join) {
            $join->on('master_status.value', '=', 'main_category.status');
            $join->where('master_status.key', '=', 'MAIN_CATEGORY_STATUS');
        });
    }

    public function scopeProductJoin($query)
    {
        return $query->leftJoin('products', function ($join) {
            $join->on('products.category_id', '=', 'main_category.id');
        });
    }

    public function scopeOrderProductJoin($query)
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

    public function scopeCreatedUser($query)
    {
        return $query->leftJoin('users AS user_created', function ($join) {
            $join->on('user_created.id', '=', 'main_category.created_by');
        });
    }

    public function scopeUpdatedUser($query)
    {
        return $query->leftJoin('users AS user_updated', function ($join) {
            $join->on('user_created.id', '=', 'main_category.updated_by');
        });
    }

    public function scopeWithCategoryType($query)
    {
        return $query->selectRaw('"Category" AS category_type');
    }

    /* For view files */
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
        return $this->hasOne('App\Models\MasterStatus', 'value', 'status')->where('key', 'MAIN_CATEGORY_STATUS');
    }

    public function parseDate($date)
    {
        return ($date != null) ? Carbon::parse($date)->format(config("app.date_time_format")) : null;
    }
}
