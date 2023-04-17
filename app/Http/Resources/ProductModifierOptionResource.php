<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductModifierOptionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(!is_null($this->modifier))
        {
          $modifier_resource = new ModifierResource($this->modifier->makeVisible(['id']));
        }
        else
        {
            $modifier_resource = [];
        }
        if(isset($modifier_resource->modifier_options))
        {
          $modifier_option_arr = [];

          foreach($modifier_resource->modifier_options as $modifier_option){
            $modifier_option_arr[] = [
                'id' => $modifier_option->id,
                'label' => $modifier_option->label,
                'price' => (string)$modifier_option->price,
            ];
          }

          return [
            'id' => $modifier_resource->id,
            'label' => $modifier_resource->label,
            'is_multiple' => $modifier_resource->is_multiple,
            'modifier_options' => $modifier_option_arr
          ];
        }
        else
        {
            return [];
        }
    }
}
