<?php

namespace App\Http\Controllers\API\Website;

use Exception;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DemoRequest;

class Home extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $this->validate_request($request);

            DB::beginTransaction();


            DemoRequest::create($request->all());

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("We have received your requset for the demo, We will get back to you soon. Thank You"),
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
            'email' => 'Entered email :attribute is already registered with us, Thank You.',
        ];

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email|unique:demo_requests,email',
            'city' => 'required',
            'domain' => 'required'
        ], $messages);

        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }
}
