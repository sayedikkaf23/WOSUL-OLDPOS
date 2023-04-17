<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slack' => $this->slack,
            'id' => $this->id,
            'parent' => $this->parent,
            'mainCategory' => $this->mainCategory,
            'category_type' => ($this->parent == 0) ? 'Category' : 'Sub Category',
            'category_code' => $this->category_code,
            'label' => $this->label,
            'label_ar' => $this->label_ar,
            'description' => $this->description,
            'category_applied_on' => $this->category_applied_on,
            'category_applicable_stores' => $this->category_applicable_stores, 
            'sort_order'=>$this->sort_order,
            'image'=>$this->category_image!=null?Storage::disk('category')->url($this->category_image):null,
            'status' => new MasterStatusResource($this->status_data),
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser),
            'zid_category_id' => $this->zid_category_id,
        ];
    }
}
