<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\Measurement as MeasurementModel;
class ProductIngredientResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $ingredient_product = new ProductResource($this->ingredient_product);
        $low_stock = ($ingredient_product->quantity < $ingredient_product->alert_quantity && $ingredient_product->quantity != '-1.00')?1:0;
        if(isset($this->measurement_id) && $this->measurement_id!="")
        {
          $measurement_label = MeasurementModel::where("id",$this->measurement_id)->first();
          if(isset($measurement_label->label))
          {
            $measurement_label = $measurement_label->label;
          }
          else
          {
            $measurement_label = "-";
          }
        }
        else
        {
            $measurement_label = "-";
        }
        return [
            'slack' => $this->slack,
            'ingredient_product' => $ingredient_product,
            'quantity' => $this->quantity,
            'low_stock' => $low_stock,
            'measurement_id' => $this->measurement_id,
            'measurement_label' => $measurement_label,
            'created_at_label' => $this->parseDate($this->created_at),
            'updated_at_label' => $this->parseDate($this->updated_at),
            'created_by' => new UserResource($this->createdUser),
            'updated_by' => new UserResource($this->updatedUser)
        ];
    }
}
