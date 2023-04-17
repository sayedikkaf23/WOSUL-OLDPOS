<?php

namespace App\Http\Controllers;

use App\DemoRequest;
use App\Http\Requests\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\NewsletterSubscription as NewsletterSubscriptionModel;
use Exception;
use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Mail\ContactDetails;
use App\Models\WebsiteService;
use App\Models\WebsiteFeature;
use App\Models\WebsiteGallery;
use App\Models\WebsitePartner;
use App\Models\WebsiteClient;
use App\Models\WebsiteReview;
use App\Models\Subscription;
use Illuminate\Support\Facades\Validator;
use Session;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->info_email = 'info@wosul.sa';
        $this->support_email = 'support@wosul.sa';
        // $this->info_email = 'jollin21@gmail.com';
        // $this->support_email = 'jollin21@gmail.com';
    }

    public function index()
    {
        $data['website_services'] = WebsiteService::active()->get();
        $data['website_features'] = WebsiteFeature::active()->get();
        $data['website_gallery'] = WebsiteGallery::active()->get()->toArray();
        $data['website_gallery'] = array_chunk($data['website_gallery'], 2);
        $data['website_reviews'] = WebsiteReview::active()->get();
        $data['website_partners'] = WebsitePartner::active()->get()->chunk(2);
        $data['website_clients'] = WebsiteClient::active()->get()->chunk(2);

        $data['featured_subscriptions'] = Subscription::with('features')->whereIn('id', [7, 8])->get();

        return view('home', compact('data'));
    }

    public function add_newsletter_subscriber(NewsletterSubscriptionRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = [
                'slack' => generateSlack('App\Models\NewsletterSubscription'),
                'email' => $request->email,
                'status' => 1,
                'created_by' => 1,
                'created_on' => Carbon::now(),
            ];

            $NewsletterSubscriptionModel = new NewsletterSubscriptionModel;
            $NewsletterSubscriptionModel->insert($data);


            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

        session()->flash('success', 'Thanks for subscribing.');
        return redirect()->back();
    }


    public function add_contact_details(ContactRequest $request)
    {

        Mail::to($this->info_email)->send(new ContactDetails($request));
        Mail::to($this->support_email)->send(new ContactDetails($request));
        session()->flash('success', 'Thanks for contacting us.');
        return redirect()->back();
    }

    // public function demo_request(Request $request)
    // {
    //     try {

    //         $validator = Validator::make($request->all(), [
    //             'first_name' => 'required',
    //             'last_name' => 'required',
    //             'contact_number' => 'required',
    //             'email' => 'required|unique:demo_requests,email',
    //             'city' => 'required',
    //             'domain' => 'required'
    //         ]);

    //         $validation_status = $validator->fails();
    //         if ($validation_status) {
    //             throw new Exception($validator->errors());
    //         }

    //         DemoRequest::create($request->all());

    //         \Session::flash('message', 'This is a message!'); 

    //         session()->flash('success', 'We have received your requset for the demo, We will get back to you soon. Thank You');

    //         return redirect()->back()->withSuccess('asdasdasdasd');

    //     } catch (Exception $e) {
    //         return response()->json($this->generate_response(
    //             array(
    //                 "message" => $e->getMessage(),
    //                 "status_code" => $e->getCode()
    //             )
    //         ));
    //     }
    // }

    public function change_language(Request $request)
    {
        \App::setlocale($request->lang);

        $data = ['status' => true, 'data' => []];
        return json_encode($data);
    }

    public function wosul()
    {
        $data['title'] = 'Welcome to Wosul';
        return view('wosul', compact('data'));
    }
}
