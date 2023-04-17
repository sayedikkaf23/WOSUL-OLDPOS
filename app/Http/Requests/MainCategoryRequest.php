<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MainCategory;
use Illuminate\Validation\Rule;

class MainCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => ['required',Rule::unique('main_category')->where(function($query){
                $query->where('deleted_at',NULL);
             })],
            'category_code' => ['required',Rule::unique('main_category')->where(function($query){
                $query->where('deleted_at',NULL);
             })],
            'status' => 'required',
        ];
    }

    public function messages(){
        return [
            'label.required' => 'Please enter a category label',
            'label.unique' => 'Category label already existed, please enter a different name',
            'category_code.required' => 'Please enter a category code',
            'category_code.unique' => 'Category code already existed, please enter a different code',
            'status.required' => 'Please select a status',
        ];
    }

    public function filters(){
        return [
            'label' => 'trim'
        ];
    }
}
