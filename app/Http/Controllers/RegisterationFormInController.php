<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\RegistrationForm;

use Validator;

class RegisterationFormInController extends Controller
{
    public function index()
    {
        $data['title'] = 'Registration Form';

        return view('registration_form_in', compact('data'));
    }

    public function store(Request $request)
    {

        // dd($request);
        try {

            $this->validate_request($request);

            DB::beginTransaction();

            $form_data = [
                'type' => $request->type,
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'business_type' => $request->business_type,
                // 'number_of_branch' => $request->number_of_branch,
                'created_at' => Carbon::now()->format('Y-m-d h:i'),
            ];

            RegistrationForm::create($form_data);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Thank you for trusting us, we will contact you soon"),
                    "data"    => [],
                    "status_code" => 200
                )
            ));
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function validate_request($request)
    {

        $messages = [
            'name.required' => (app()->getLocale() == 'ar') ? trans('Full name is required') : 'Full name is required',
            'business_type.required' => (app()->getLocale() == 'ar') ? trans('Business Type is required') : 'Business Type is required',
            // 'number_of_branch.required' => (app()->getLocale() == 'ar') ? trans('Please select number of branches') : 'Please select number of branches',
            'phone_number.required' => (app()->getLocale() == 'ar') ? trans('Phone Number is required') : 'Phone Number is required',
            'phone_number.regex|phone_number.min|phone_number.max' => (app()->getLocale() == 'ar') ? trans('Phone Number must be 10 digits') : 'Phone Number must be 10 digits',
            'phone_number.min' => (app()->getLocale() == 'ar') ? trans('Phone Number must be 10 digits') : 'Phone Number must be 10 digits',
            'phone_number.max' => (app()->getLocale() == 'ar') ? trans('Phone Number must be 10 digits') : 'Phone Number must be 10 digits',
            'email.regex' => (app()->getLocale() == 'ar') ? trans("Email should be in 'example@example.com' format") : "Email should be in 'example@example.com' format"
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'business_type' => 'required',
            // 'number_of_branch' => 'required',
            'email' => 'nullable|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'phone_number' => 'required|regex:/[0-9]{10}/|min:10|max:10'
        ], $messages);

        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function response()
    {
        $data['title'] = 'Registration Form Response';
        $data['registration_form_results'] = RegistrationForm::where('type',2)->orderBy('id', 'DESC')->get();
        return view('registration_form_in-response', compact('data'));
    }

}
