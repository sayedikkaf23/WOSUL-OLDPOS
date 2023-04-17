<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

use Validator;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class SurveyController extends Controller
{
    public function index()
    {
        $data['titke'] = 'Create Survey';
        return view('qr', compact('data'));
    }

    public function store(Request $request)
    {

        try {

            $this->validate_request($request);

            DB::beginTransaction();

            $survey = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'created_at' => Carbon::now()->format('Y-m-d h:i'),
            ];

            Survey::create($survey);

            $this->append_to_google_sheet(array_values($survey));

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
            'full_name.required' => trans('Full name is required'),
            'phone_number.required' => trans('Phone Number is required'),
            'phone_number.digits' => trans('Phone Number must be 10 digits'),
            'phone_number.unique' => trans('Phone Number is already registered with Us')
        ];

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone_number' => 'required|unique:survey|digits:10'
        ], $messages);

        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }
    public function response()
    {
        $data['title'] = 'Survey Response';
        $data['survey_results'] = Survey::orderBy('id', 'DESC')->get();

        return view('response', compact('data'));
    }

    public function append_to_google_sheet($values)
    {
        $document_id  = env('SURVEY_SHEET_ID');

        $client = new Client();
        $client->setApplicationName('Wosul');
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig('../credentials.json');
        $client->setAccessType('online');

        $sheet_data = [];
        foreach ($values as $value) {
            if ($value == null) {
                $sheet_data[] = ' ';
            } else {
                $sheet_data[] = $value;
            }
        }

        $service = new Sheets($client);
        $range = 'A:D';

        $body = new ValueRange(['values' => [$sheet_data]]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];
        $result = $service->spreadsheets_values->append($document_id, $range, $body, $params);

        return $result;
    }
}
