<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;

class AccCoa extends Model
{
    
    protected $table = 'acc_coa';
    protected $fillable = ['HeadCode','HeadName','PHeadName','HeadLevel','IsActive','IsTransaction','IsGL','HeadType','IsBudget','IsDepreciation','DepreciationRate','CreateBy','CreateDate','UpdateBy','UpdateDate'];
    public $timestamps = false;
}


