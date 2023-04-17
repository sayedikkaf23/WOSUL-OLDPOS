<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CategoryStoreScope implements Scope
{
    
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table = $model->getTable();
        $store_id = isset(request()->logged_user_store_id) ? request()->logged_user_store_id : session('store_id');   
        // $builder->whereRaw('FIND_IN_SET(' . $store_id. ', ' . $table.'.store_id');
        // $builder->where($table.'.store_id', '=', $store_id);
        $builder->where($table . '.category_applied_on', "all_stores")->orWhere($table . '.category_applied_on', "specific_stores")->whereRaw('FIND_IN_SET(?, ' . $table . '.category_applicable_stores)', [$store_id]);
    }
}