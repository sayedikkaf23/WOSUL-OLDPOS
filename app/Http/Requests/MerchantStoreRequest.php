<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MerchantStoreRequest extends FormRequest
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
        if (request('from_payment') == null) {
            $rules =  [
                'name' => 'required|string|max:50',
                'phone_number' => 'required',
                'email' => ['required', 'email', Rule::unique('merchants')->where(function ($query) {
                    $query->where('deleted_at', NULL);
                })],
                'company_name' => 'required',
                'company_url' => ['required', Rule::unique('merchants')->where(function ($query) {
                    $query->where('deleted_at', NULL);
                })],
            ];
        } else {
            $rules =  [
                'email' => ['required', 'email', Rule::unique('merchants')->where(function ($query) {
                    $query->where('deleted_at', NULL);
                })],
                'subscription_id' => 'required',
                'company_name' => 'required',
                'company_url' => ['required', Rule::unique('merchants')->where(function ($query) {
                    $query->where('deleted_at', NULL);
                })],
            ];
        }

        return $rules;
    }

    public function messages()
    {
        $lang = request('lang') != 'null' ? request('lang') : 'en';
        return [
            'name.required' => 'Please enter a name',
            'phone_number.required' => trans('validation.required', [], $lang),
            'email.required' => trans('validation.required', [], $lang),
            //   'password.required' => trans('validation.required',[],$lang),
            'company_name.required' => trans('validation.required', [], $lang),
            'company_url.required' => trans('validation.required', [], $lang),
        ];
    }
}
