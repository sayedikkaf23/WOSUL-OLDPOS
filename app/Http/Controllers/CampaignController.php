<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignVisit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

use Validator;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class CampaignController extends Controller
{
    public function index()
    {
        $data['title'] = 'Create Campaign';

        if(request()->pt != ''){
            CampaignVisit::firstOrCreate([
                'pt' => request()->pt,
                'ip' => request()->getClientIp()
            ]);
        }
        return view('campaign', compact('data'));
    }

    public function store(Request $request)
    {

       
        try {

            $this->validate_request($request);

            DB::beginTransaction();

            $campaign = [
                'full_name' => $request->full_name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'created_at' => Carbon::now()->format('Y-m-d h:i'),
                'pt' => $request->pt
            ];

            Campaign::create($campaign);

            $this->append_to_google_sheet(array_values($campaign));

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
            'full_name.required' => trans('Full name is required'),
            'phone_number.required' => trans('Phone Number is required'),
            'phone_number.regex' => trans('Phone Number must be 10 digits'),
            'email.regex' => trans("Email should be in 'example@example.com' format")
        ];

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
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
        $data['title'] = 'Campaign Response';
        $data['campaign_results'] = Campaign::orderBy('id', 'DESC')->get();
        $data['campaign_visits'] = DB::select('SELECT pt,count(*) as hits FROM campaign_visit GROUP BY pt ORDER BY hits DESC');
       
        return view('campaign-response', compact('data'));
    }

    public function change_status(Request $request){

        DB::select('UPDATE campaign SET status='.$request->status.' WHERE id='.$request->campaign_id);
        return json_encode(array('status'=>true));
    }

    public function append_to_google_sheet($values)
    {
        $document_id  = env('CAMPAIGN_SHEET_ID');

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
        $range = 'A:E';

        $body = new ValueRange(['values' => [$sheet_data]]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];
        $result = $service->spreadsheets_values->append($document_id, $range, $body, $params);

        return $result;
    }
}
