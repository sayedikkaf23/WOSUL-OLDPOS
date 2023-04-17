<?php

namespace App\Http\Controllers\API;

use App\Models\ReturnOrders;
use Exception;
use Validator;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Discountcode as DiscountcodeModel;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderProductResource;
use App\Http\Resources\Collections\OrderListByDateCollection;

use App\Models\Order as OrderModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Product as ProductModel;
use App\Models\Customer as CustomerModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\Store as StoreModel;
use App\Models\TaxcodeType as TaxcodeTypeModel;
use App\Models\Taxcode as TaxCodeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Account as AccountModel;
use App\Models\MasterOrderType as MasterOrderTypeModel;
use App\Models\Table as TableModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\User as UserModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\ProductIngredient as ProductIngredientModel;
use App\Models\MeasurementConversion as MeasurementConversionModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\OrderProductModifierOption as OrderProductModifierOptionModel;

use App\Http\Resources\Collections\OrderCollection;
use App\Http\Resources\Collections\OrderDateCollection;


use App\Http\Controllers\API\Notification as NotificationAPI;
use App\Http\Resources\OrderDateResource;
use App\Http\Resources\ReturnOrderResource;
use App\Models\ReturnOrders as ReturnOrdersModel;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;
use App\Models\HRM\AccCoa as AccCoaModel;
use App\Models\HRM\AccTransaction as AccTransactionModel;
use App\Models\UserPointsSettings as UserPointsSettingsModel;
use App\Models\BonatUserPointsSettings as BonatUserPointsSettingsModel;
use App\Models\BonatStoreCounterPointsSettings as BonatStorePointsSettingsModel;

use Illuminate\Support\Facades\Log;
use App\Http\Traits\BonatApiTrait;

use Mpdf\Mpdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Arr;
use App\Http\Traits\ZidApiTrait;
use App\Http\Traits\CommonApiTrait;
use App\Models\Measurement;
use App\Models\ModifierOptionIngredient;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use App\Http\Resources\OrderListByDateResource;
use App\Http\Traits\QoyodApiTrait;
use App\Models\Invoice;

class Order extends Controller
{

    use ZidApiTrait, BonatApiTrait, CommonApiTrait,QoyodApiTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }


            $from_date = str_replace('T', ' ', $from_date);
            $to_date = str_replace('T', ' ', $to_date);

            // $from_date = $from_date . ' 00:00:00';
            // $to_date = $to_date . ' 23:59:59';

            $data['action_key'] = 'A_VIEW_ORDER_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }

            $item_array = array();

            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];
            $order_by_column =  $request->columns[$order_by]['name'];

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $query = OrderModel::select('orders.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->distinct('orders.id')
                ->leftJoin('transactions', 'transactions.bill_to_id', '=', 'orders.id')
                //->where('id')
                ->take($limit)
                ->skip($offset)
                // ->orderBy('transactions.bill_to_id', 'desc')
                ->statusJoin()
                ->createdUser()
                // ->groupBy('orders.id')

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('value_date', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })->whereBetween('orders.value_date', [$from_date, $to_date]);

            if ($request->status != "") {
                $query->where('orders.status', '=', $request->status);
            }
            $query = $query->get();

            $total_order_amount = $query->sum('total_order_amount');
            $total_amount = number_format($total_order_amount, 2, '.', '');

            $orders = OrderListResource::collection($query);

            $total_q = OrderModel::select("id")->whereBetween('orders.value_date', [$from_date, $to_date]);
            if ($request->status != "") {
                $total_q->where('orders.status', '=', $request->status);
            }

            $total_count = $total_q->get()->count();

            // $query_g = OrderModel::select("total_order_amount")
            // ->whereBetween('orders.created_at',[$from_date,$to_date]);
            // if($request->status!=""){
            //     $query_g->where('orders.status','=',$request->status);
            // }
            // $query_g=$query_g->get();    
            // $query_g1 = OrderListResource::collection($query_g);     
            // $grand_order_total_amount = $query_g1->sum('total_order_amount');   
            // $grand_total_amount = number_format($grand_order_total_amount,2, '.', '');  

            $item_array = [];

            $temp_total = 0;
            $temp_grand_total = 0;
            $temp_return_total = 0;

            $temp_return_total_amount = $orders->where('return_order_amount', '!=', NULL)->sum('return_order_amount');
            $temp_total_order_amount = $orders->where('total_order_amount', '!=', NULL)->sum('total_order_amount');
            $temp_total_amount = bcsub($temp_total_order_amount, $temp_return_total_amount, 2);
            $temp_grand_total = $temp_total_amount;
            //dd($orders[0]);
            foreach ($orders as $key => $order) {

                $order = $order->toArray($request);

                $item_array[$key][] = $order['id'];
                $item_array[$key][] = $order['reference_number'];
                $item_array[$key][] = $order['order_number'];
                $item_array[$key][] = (!empty($order['customer_phone'])) ? $order['customer_phone'] : '-';
                $item_array[$key][] = (!empty($order['customer_email'])) ? $order['customer_email'] : '-';
                $item_array[$key][] = $order['total_order_amount'];
                $item_array[$key][] = ($order['has_combo'] == 1) ? 'Yes' : '';
                $item_array[$key][] = (isset($order['status']['label'])) ? view('common.status', ['status_data' => ['label' => $order['status']['label'], "color" => $order['status']['color']]])->render() : '-';
                // $item_array[$key][] = Carbon::parse($order['value_date'])->format('d M, Y');
                $item_array[$key][] = $order['value_date'];
                $item_array[$key][] = $order['updated_at_label'];
                $item_array[$key][] = (isset($order['created_by']) && isset($order['created_by']['fullname'])) ? $order['created_by']['fullname'] : '-';
                $item_array[$key][] = view('order.layouts.order_actions', ['order' => $order])->render();
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array,
                'total_amount' => $temp_total_amount,
                'grand_total_amount' => $temp_grand_total,
                'total' => trans('Total'),
                'grand_total' => trans('Grand Total'),
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode(),
                )
            ));
        }
    }

    /**
     * method : generate_order_pdf
     * param  : start_date,end_date

     **/
    public function generate_order_pdf(Request $request)
    {


        try {
            if ($request->from_date != "" && $request->to_date == "") {
                $from_date = $request->from_date;
                $to_date = $from_date;
            }
            if ($request->to_date != "" && $request->from_date == "") {
                $to_date = $request->to_date;
                $from_date = $to_date;
            }
            if ($request->from_date != "" && $request->to_date != "") {
                $from_date = $request->from_date;
                $to_date = $request->to_date;
            }

            $from_date = $from_date . ' 00:00:00';
            $to_date = $to_date . ' 23:59:59';

            $query = OrderModel::select('orders.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->statusJoin()
                ->createdUser()
                ->orderBy('orders.id', 'asc')
                ->whereBetween('orders.created_at', [$from_date, $to_date]);
            if ($request->order_status != "") {
                $query->where('orders.status', '=', $request->order_status);
            }

            $query = $query->get();

            $orders = OrderResource::collection($query);


            if (isset($orders)) {

                $view_file = 'order.pdf.generate';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [100, 150];
                $print_logo_path = session('store_logo');

                $print_data = view($view_file, ['orders' => json_encode($orders), 'print_logo_path' => $print_logo_path, 'from_date' => $from_date, 'to_date' => $to_date])->render();

                $mpdf_config = [
                    'mode'          => 'utf-8',
                    'format'        => $format,
                    'orientation'   => 'P',
                    'margin_left'   => 7,
                    'margin_right'  => 7,
                    'margin_top'    => 7,
                    'margin_bottom' => 7,
                    'tempDir' => storage_path() . "/pdf_temp"
                ];

                $stylesheet = File::get(public_path($css_file));
                $mpdf = new Mpdf($mpdf_config);
                $mpdf->curlAllowUnsafeSslRequests = true;
                $mpdf->SetDisplayMode('real');
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($print_data);

                $pdf_filename = "orders_" . date('Y_m_d_h_i_s') . "_" . uniqid() . ".pdf";
                $view_path = Config::get('constants.upload.order.view_path');
                $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
                $mpdf->Output($upload_dir . $pdf_filename, \Mpdf\Output\Destination::FILE);

                return response()->json($this->generate_response(
                    array(
                        "message" => "Order pdf created successfully",
                        "data" => $orders,
                        'orders' => $orders,
                        "link" => '/storage/' . session('merchant_id') . '/order/' . $pdf_filename,
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No order found.'),
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function store(Request $request)
     {   
 
         // validate duplicate order and returned existing order
         $is_duplicate = $this->__check_duplicate_order($request->all());
         
         if($is_duplicate['status'] && isset($is_duplicate['data'])){
             
             return response()->json($this->generate_response(
                 array(
                     "message" => "Order already exists",
                     "data" => (string) $request->slack,
                     "device_slack" => (isset($request->device_slack)) ? $request->device_slack : 0,
                     "order" => $is_duplicate['data']
                 ),
                 'SUCCESS'
             ));
 
         }else{
 
             $store_status = $this->getStoreStatus($request->logged_user_store_id);
             $cashier_discounts_amount = isset($request->cashier_discount_amount_ids) ? json_decode($request->cashier_discount_amount_ids) : [];
             $cashier_discounts_percentage = isset($request->cashier_discount_percentage_ids) ? json_decode($request->cashier_discount_percentage_ids) : [];
             if (!$store_status['status']) {
                 throw new Exception($store_status['message'], 400);
             }
     
             $this->low_on_ingredient = 0;
     
             try {
     
                 $validation_required = ($request->order_status == "CLOSE") ? true : false;
     
                 if ($request->order_status != "SAVE" && $validation_required == true) {
                     $validator = Validator::make($request->all(), [
                         'order_status' => $this->get_validation_rules("order_status", true),
                         'payment_method' => $this->get_validation_rules("slack", $validation_required),
                         'business_account' => $this->get_validation_rules("slack", $validation_required),
                     ]);
                     $validation_status = $validator->fails();
     
                     if ($validation_status) {
                         throw new Exception($validator->errors());
                     }
                 }
     
     
                 if (isset($request->restaurant_order_type)) {
                     // $request->restaurant_order_type =$request->restaurant_order_type;
                 } else {
                     $request->restaurant_order_type = "TAKEWAY";
                 }
                 if (!isset($request->payment_method)) {
                     $request->payment_method = "CASH";
                     $payment_method = PaymentMethodModel::select('id', 'slack', 'label', 'payment_constant')
                         ->where([
                             ['payment_methods.payment_constant', '=', $request->payment_method]
                         ])
                         ->active()
                         ->first();
     
                     $request->payment_method_slack = $payment_method->slack;
                 }
     
                 if (!check_access(['A_ADD_ORDER'], true)) {
                     throw new Exception("Invalid request", 400);
                 }
     
                 $cart = json_decode($request->cart);
     
                 // dd($cart);
     
                 DB::beginTransaction();
     
                 if (!empty($cart)) {
     
                     $business_register_data = BusinessRegisterModel::select('id', 'slack')
                         ->where('user_id', '=', trim($request->logged_user_id))
                         ->whereNull('closing_date')
                         ->first();
     
                     if (empty($business_register_data)) {
                         throw new Exception("You dont have any register open", 400);
                     }

                    //  dd($request->payment_method_slack);
                     $order_data = $this->form_order_array($request);
                     
                    //  $order_value_date_detail = OrderModel::select('value_date')->where('store_id', $request->logged_user_store_id)->where('status', 5)->orWhere('status', 1)->orderBy('id', 'desc')->first();
     
                     if (!empty($order_data['order_data']) && !empty($order_data['order_products_data'])) {
                         if (!empty($order_data['order_data'])) {
     
                             $order = $order_data['order_data'];
                             //print_r($order_data['order_products_data']);die;
                             $order['slack'] = $this->generate_slack("orders");
                             $order['store_id'] = $request->logged_user_store_id;
                             $order['device_id'] = (isset($request->device_id)) ? $request->device_id : '';
                             $order['order_number'] = (isset($request->order_number) && $request->order_number != '') ? $request->order_number : $this->getNextOrderNumber($request->logged_user_store_id);
     
                             if (isset($request->reference_number) && $request->reference_number != '') {
                                 $reference_number = (int) $request->reference_number;
                             } else {
                                 $last_reference_number = OrderModel::select('reference_number')->where('store_id', $request->logged_user_store_id)->orderBy('id', 'DESC')->first();
                                 if (isset($last_reference_number)) {
                                     $reference_number = (int) $last_reference_number->reference_number + 1;
                                 } else {
                                     $reference_number = 1;
                                 }
                             }
                             $order['reference_number'] = $reference_number;
                             $order['register_id'] = $business_register_data->id;
                             if (isset($request->created_at) && $request->created_at != '') {
                                 $created_at = Carbon::createFromTimestamp($request->created_at)->toDateTimeString();
                             } else {
                                 $created_at = Carbon::now()->format('Y-m-d H:i:s');
                             }
                             $order['created_at'] = $created_at;
                             $order['created_by'] = $request->logged_user_id;                             
                             $order['nearpay_json'] = (isset($request->nearpay_json)) ? $request->nearpay_json : null;

                             if(isset($request->has_combo) && $request->has_combo){
                                 $order['has_combo'] = 1;
                             }
     
                             $order_item = OrderModel::create($order);
     
                             $order_id = $order_item->id;
     
                             // $code_start_config = Config::get('constants.unique_code_start.order');
                             // $code_start = (isset($code_start_config)) ? $code_start_config : 100;
     
                             // $order_number = [
                             //     "order_number" => $code_start + $order_id
                             // ];
                             // OrderModel::where('id', $order_id)
                             //     ->update($order_number);
                         }
     
                         if (!empty($order_data['order_products_data'])) {
     
                             $temp_order_ingredients = [];
                             foreach ($order_data['order_products_data'] as $op) {
                                 $dataset = [];
                                 $dataset = $this->__check_ingredient_stock($op['product_id'], $op['quantity']);
                                 $temp_order_ingredients[] = $dataset;
                             }
                             $all_order_ingredients = array();
                             if (!empty($temp_order_ingredients)) {
                                 foreach ($temp_order_ingredients as $k => $v) {
                                     $all_order_ingredients = array_merge_recursive($all_order_ingredients, $v);
                                 }
                                 $temp_set = [];
                                 foreach ($all_order_ingredients as $k => $v) {
                                     $temp_set = array_merge_recursive($temp_set, $v);
                                 }
                                 $ingredient_stocks = [];
                                 foreach ($temp_set as $key => $value) {
                                     if (is_array($temp_set[$key])) {
                                         $ingredient_stocks[$key] = array_sum($temp_set[$key]);
                                     } else {
                                         $ingredient_stocks[$key] = (float) $temp_set[$key];
                                     }
                                 }
                                 foreach ($ingredient_stocks as $key => $rs) {
                                     $available_ingredient_qty = ProductModel::where('slack', $key)->select('quantity', 'name')->first();
                                     if (isset($available_ingredient_qty) && $available_ingredient_qty->quantity < $rs) {
                                         throw new Exception($available_ingredient_qty->name . " ingredient went out of stock", 400);
                                     }
                                 }
                             }
     
                             $order_products = $order_data['order_products_data'];
     
                             
                             // to check product's modifier's ingredient's stock
                             $this->__check_and_update_modifier_ingredient_stock($order_products);
                             
                             array_walk($order_products, function (&$item, $key) use ($order_id, $request, $order_data) {
     
                                 $order_product_modifiers = $item['modifiers'];
                                 
                                 $item['slack'] = $this->generate_slack("order_products");
                                 $item['order_id'] = $order_id;
                                 $item['created_at'] = now();
                                 $item['created_by'] = $request->logged_user_id;
                                 
                                 
                                 unset($item['modifiers']);
     
     
                                 $order_product_id = OrderProductModel::create($item);
                                 $order_product_id = $order_product_id->id;
                                 // adding modifier in order product
     
                                 $total_modifier_amount = 0;
     
                                 if (isset($order_product_modifiers) && !empty($order_product_modifiers) && $order_product_modifiers != "[]") {
     
                                     foreach ($order_product_modifiers as $order_product_modifier) {
     
                                         $modifier_option_price = $order_product_modifier->price;
                                         if ($item['tax_percentage'] > 0) {
                                             $modifier_option_price = $this->calculate_reverse_percent_amount($order_product_modifier->price, $item['tax_percentage']);
     
                                             if ($item['is_tobacco_tax'] > 0) {
                                                 $modifier_option_price = $this->calculate_reverse_percent_amount($modifier_option_price, $item['tobacco_tax_percentage']);
                                             }
                                         }
     
                                         OrderProductModifierOptionModel::create([
                                             'order_product_id' => $order_product_id,
                                             'modifier_option_id' => $order_product_modifier->id,
                                             'modifier_option_price' => $modifier_option_price
                                         ]);
     
                                         
                                     }
                                     $total_modifier_amount += (float) $item['total_modifier_amount'];
                                 }
     
                                 OrderProductModel::where('id', $order_product_id)->update(['total_modifier_amount' => $total_modifier_amount]);
                                 if ($item['product_id'] != '' && $item['quantity'] > 0) {
                                     $product = ProductModel::find($item['product_id']);
                                     if ($product['quantity'] != -1.00) {
                                         $product->decrement('quantity', $item['quantity']);
                                         // Add quantity history
                                         $this->addQuantityHistory($this->generate_slack('quantity_history'),$product->id,$product['store_id'],'ORDER','DECREMENT',$item['quantity'],$product->id);
                                     }
                                     $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity']);
                                 }
                             });
                         }
                     }
                 }
     
                 if($request->selected_discount_code!=''){
                     DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where discount_code like '%{$request->selected_discount_code}%' and limit_on_discount>0");
                 }
     
                 /*DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where discount_code like '%{$request->selected_discount_code}%' and limit_on_discount>0");
                 if (count($cashier_discounts_amount) > 0) {
                     foreach ($cashier_discounts_amount as $id) {
                         DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where id=$id and limit_on_discount>0");
                     }
                 } else {
                     foreach ($cashier_discounts_percentage as $id) {
                         DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where id=$id and limit_on_discount>0");
                     }
                 }*/
     
                
     
                 if (isset($request->cart)) {
                     $cart = json_decode($request->cart);
                     foreach ($cart as $cart_item_key => $cart_item) {
                         if (isset($cart_item->discount_code_id)) {
                             DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where id like '{$cart_item->discount_code_id}' and limit_on_discount>0");
                         }
                     }
                 }

                 //Qoyod
                 if(Session('qoyod_status')){
                     $order_detail = OrderModel::where('id',$order_id)->first();
                     $this->qoyod_create_invoice($order_detail,'Order');
                 }
     
                 DB::commit();
     
                 //abkhas points
                 // $this->add_user_points($order_data['order_data']);
     
                 // //bonat points
                 // $this->send_bonat_order_details($order, $order_id);
                 // $bonat_coupon = null;
                 // $bonat_coupon = $order_data['bonat_coupon'];
     
                 // if ($bonat_coupon != null) {
                 //     $order['bonat_coupon'] = $bonat_coupon;
                 //     $this->set_use_coupon_details($order);
                 // }
     
                 /*-------------------------------*/
                 /* UPDATE ZID's PRODUCT QUANTITY*/
                 /*-------------------------------*/
     
                 // if (isset($request->cart)) {
     
                 //     $cart = json_decode($request->cart);
     
                 //     foreach ($cart as $cart_item_key => $cart_item) {
                 //         $product = ProductModel::where('slack', $cart_item->product_slack)->first();
                 //         if ($product->zid_product_id != "") {
     
                 //             $this->zid_update_product_quantity($product->quantity, $product->zid_product_id);
                 //         }
                 //     }
                 // }
     
                 $forward_link = '';
     
                 if ($request->order_status == "CLOSE") {
                  
                     if (isset($request->save_order) && $request->save_order) {
                         $forward_link = route('order_receipt', ['slack' => $order['slack']]);
                     } else {
                         $forward_link = route('order_summary', ['slack' => $order['slack']]);
                     }
                     $this->record_order_payment_transaction($order['slack']);
     
                     // $this->record_hrm_transaction($order['slack'], $forward_link);
     
                     $notification_api = new NotificationAPI();
                     $notification_api->send_sms('POS_SALE_BILL_MESSAGE', $order['slack']);
                 } else if (in_array($request->order_status, ['HOLD', 'IN_KITCHEN','PARTIAL_PAYMENT'])) {
                    $forward_link = route('order_summary', ['slack' => $order['slack']]);
                    if(in_array($request->order_status, ['IN_KITCHEN'])){
                        $this->record_order_payment_transaction($order['slack']);
                    }else if($request->order_status == 'PARTIAL_PAYMENT'){
                        $paid_amount = isset($request->paid_amount)?$request->paid_amount:0;
                        $this->record_order_payment_transaction($order['slack'], $paid_amount);
                    }
                 }
                 $item_data = new OrderResource($order_item);
     
                 $store_detail = StoreModel::find($request->logged_user_store_id);
     
                 $qr_code = \Salla\ZATCA\GenerateQrCode::fromArray([
                     new \Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),
                     new \Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                     new \Salla\ZATCA\Tags\InvoiceDate(Carbon::now()->format('Y-m-d h:i')),
                     new \Salla\ZATCA\Tags\InvoiceTotalAmount($item_data['total_order_amount']),
                     new \Salla\ZATCA\Tags\InvoiceTaxAmount($item_data['total_tax_amount'])
                 ])->toBase64();
                $separate_api = 0;
                $transaction_data = $this->get_order_pending_payment_data($order['slack'], $separate_api);
                 $this->__terminate_duplicate_orders();
     
                 return response()->json($this->generate_response(
                     array(
                         "message" => "Order created successfully",
                         "data" => (string) $order['slack'],
                         "link" => $forward_link,
                         "qr_code" => $qr_code,
                         "device_slack" => (isset($request->device_slack)) ? $request->device_slack : 0,
                         "order" => $item_data,
                         "transaction_data" => $transaction_data,
                     ),
                     'SUCCESS'
                 ));
             }
            //  catch (\GuzzleHttp\Exception $e) {
            //      return [
            //          'status' => false,
            //          'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
            //      ];
            //  }
             catch (Exception $e) {
                 return response()->json($this->generate_response(
                     array(
                         "message" => $e->getMessage(),
                         "status_code" => $e->getCode()
                     )
                 ));
             }
             
         }
 
        
     }

    function searchById($id, $array) {
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
                return $key;
            }
        }
        return null;
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_ORDER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $item = OrderModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new OrderResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Order loaded successfully",
                    "data"    => $item_data
                ),
                'SUCCESS'
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

    /**
     * list all the specified resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_ORDER_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $list = new OrderCollection(OrderModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Orders loaded successfully",
                    "data"    => $list
                ),
                'SUCCESS'
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {
        // dd($slack);
        try {

            $validation_required = ($request->order_status == "CLOSE") ? true : false;
            $cashier_discounts_amount = isset($request->cashier_discount_amount_ids) ? json_decode($request->cashier_discount_amount_ids) : [];
            $cashier_discounts_percentage = isset($request->cashier_discount_percentage_ids) ? json_decode($request->cashier_discount_percentage_ids) : [];
            // dd($cashier_discounts_amount, $cashier_discounts_percentage);
            if ($request->order_status != "SAVE" && $validation_required == true) {
                $validator = Validator::make($request->all(), [
                    'order_status' => $this->get_validation_rules("order_status", true),
                    'payment_method' => $this->get_validation_rules("slack", $validation_required),
                    'business_account' => $this->get_validation_rules("slack", $validation_required),
                ]);

                $validation_status = $validator->fails();
                if ($validation_status) {
                    throw new Exception($validator->errors());
                }
            }

            if (!check_access(['A_EDIT_ORDER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $order_details = OrderModel::where('slack', $slack)->first();
            $prev_order_status = $order_details->status;
            $order_id = $order_details->id;
            $cart = json_decode($request->cart);
            // dd($order_details->status);
            DB::beginTransaction();

            // For checking the partial payment settings
            if($request->order_status == "PARTIAL_PAYMENT"){
                $order['updated_at'] = now();
                $order['updated_by'] = $request->logged_user_id;

                OrderModel::where('slack', $slack)->update($order);
            }elseif (!empty($cart)) {

                $order_data = $this->form_order_array($request, $slack, $prev_order_status);

                if (!empty($order_data['order_data']) && !empty($order_data['order_products_data'])) {
                    if (!empty($order_data['order_data'])) {

                        $order = $order_data['order_data'];

                        $order['updated_at'] = now();
                        $order['updated_by'] = $request->logged_user_id;

                        $action_response = OrderModel::where('slack', $slack)->update($order);
                    }

                    

                    if (!empty($order_data['order_products_data'])) {
                        $order_products = $order_data['order_products_data'];
                        $current_order_products = OrderProductModel::where('order_id', $order_id)->get()->makeVisible(['id'])->toArray();
                        //dd($current_order_products);
                        array_walk($current_order_products, function (&$item, $key) use ($order_id, $request){
                            if($item['product_id'] != '' && $item['quantity']>0){
                                if($item['total_modifier_amount'] > 0){

                                    $order_product_modifier_options = OrderProductModifierOptionModel::select('order_product_modifier_options.modifier_option_id as id')
                                        ->where('order_product_modifier_options.order_product_id',$item['id'])->get();
                                    if(isset($order_product_modifier_options) && count($order_product_modifier_options) > 0){
                                        foreach($order_product_modifier_options as $product_modifier_option){
                                            $product_modifier_options_arr[] = [
                                                "id" =>  $product_modifier_option->id,
                                            ];
                                        }
                                        $item['modifiers'] = $product_modifier_options_arr;
                                    }

                                    if($this->__update_modifier_single_ingredient_stock($item) == true){
                                        OrderProductModifierOptionModel::where('order_product_id', $item['id'])->delete();
                                    }
                                }
                                $product = ProductModel::find($item['product_id']);
                                if ($product['quantity'] != -1.00) {
                                    $product->increment('quantity', $item['quantity']);
                                }
                                $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity'], 'increment');
                                if(isset($item['discount_code_id']) && !empty($item['discount_code_id'])){
                                    $item_discount_code_id = $item['discount_code_id'];
                                    DB::update("update discount_codes set limit_on_discount = limit_on_discount+1 where id = $item_discount_code_id and limit_on_discount>0");
                                }
                            }
                            
                        });
                        if(isset($request->discount_code_id) && !empty($request->discount_code_id)){
                            DB::update("update discount_codes set limit_on_discount = limit_on_discount+1 where id=$request->discount_code_id and limit_on_discount>0");
                        }
                        
                        if (count($order_products) > 0) {

                            OrderProductModel::where('order_id', $order_id)->delete();
                        }

                        $temp_order_ingredients = [];
                        foreach ($order_products as $op) {
                            $dataset = [];
                            $dataset = $this->__check_ingredient_stock($op['product_id'], $op['quantity']);
                            $temp_order_ingredients[] = $dataset;
                        }

                        $all_order_ingredients = array();
                        if (!empty($temp_order_ingredients)) {
                            foreach ($temp_order_ingredients as $k => $v) {
                                $all_order_ingredients = array_merge_recursive($all_order_ingredients, $v);
                            }
                            $temp_set = [];
                            foreach ($all_order_ingredients as $k => $v) {
                                $temp_set = array_merge_recursive($temp_set, $v);
                            }
                            $ingredient_stocks = [];
                            foreach ($temp_set as $key => $value) {
                                if (is_array($temp_set[$key])) {
                                    $ingredient_stocks[$key] = array_sum($temp_set[$key]);
                                } else {
                                    $ingredient_stocks[$key] = (float) $temp_set[$key];
                                }
                            }
                            foreach ($ingredient_stocks as $key => $rs) {
                                $available_ingredient_qty = ProductModel::where('slack', $key)->select('quantity', 'name')->first();
                                if (isset($available_ingredient_qty) && $available_ingredient_qty->quantity < $rs) {
                                    throw new Exception($available_ingredient_qty->name . " ingredient went out of stock", 400);
                                }
                            }
                        }

                        // to check product's modifier's ingredient's stock
                        $this->__check_and_update_modifier_ingredient_stock($order_products);
                        array_walk($order_products, function (&$item, $key) use ($order_id, $request, $order_data) {

                            $order_product_modifiers = $item['modifiers'];
                            
                            $item['slack'] = $this->generate_slack("order_products");
                            $item['order_id'] = $order_id;
                            $item['created_at'] = now();
                            $item['created_by'] = $request->logged_user_id;
                            unset($item['modifiers']);

                            $order_product_id = OrderProductModel::create($item);
                            $order_product_id = $order_product_id->id;
                            // adding modifier in order product

                            $total_modifier_amount = 0;

                            if (isset($order_product_modifiers) && !empty($order_product_modifiers) && $order_product_modifiers != "[]") {

                                foreach ($order_product_modifiers as $order_product_modifier) {

                                    $modifier_option_price = $order_product_modifier->price;
                                    if ($item['tax_percentage'] > 0) {
                                        $modifier_option_price = $this->calculate_reverse_percent_amount($order_product_modifier->price, $item['tax_percentage']);

                                        if ($item['is_tobacco_tax'] > 0) {
                                            $modifier_option_price = $this->calculate_reverse_percent_amount($modifier_option_price, $item['tobacco_tax_percentage']);
                                        }
                                    }
                                    OrderProductModifierOptionModel::create([
                                        'order_product_id' => $order_product_id,
                                        'modifier_option_id' => $order_product_modifier->id,
                                        'modifier_option_price' => $modifier_option_price
                                    ]);

                                }
                                $total_modifier_amount += (float) $item['total_modifier_amount'];
                            }

                            OrderProductModel::where('id', $order_product_id)->update(['total_modifier_amount' => $total_modifier_amount]);
                            if ($item['product_id'] != '' && $item['quantity'] > 0) {
                                $product = ProductModel::find($item['product_id']);
                                if ($product['quantity'] != -1.00) {
                                    $product->decrement('quantity', $item['quantity']);
                                    // Add quantity history
                                    $this->addQuantityHistory($this->generate_slack('quantity_history'),$product->id,$product['store_id'],'ORDER','DECREMENT',$item['quantity'],$product->id);
                                }
                                $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity']);
                            }
                        });
                    }
                }
                DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 
                            where discount_code like '%{$request->selected_discount_code}%' and limit_on_discount>0");
                if (count($cashier_discounts_amount) > 0) {
                    foreach ($cashier_discounts_amount as $id) {
                        if(!empty($id)){
                            DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where id=$id and limit_on_discount>0");
                        }
                    }
                } else {
                    foreach ($cashier_discounts_percentage as $id) {
                        if(!empty($id)){
                            DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 where id=$id and limit_on_discount>0");
                        }
                    }
                }
    
                if (isset($request->cart)) {
                    $cart = json_decode($request->cart);
                    foreach ($cart as $cart_item_key => $cart_item) {
                        if (isset($cart_item->discount_code_id)) {
                            DB::update("update discount_codes set limit_on_discount = limit_on_discount-1 
                                        where id like '{$cart_item->discount_code_id}' and limit_on_discount>0");
                        }
                    }
                }
            }

            DB::commit();

            $order_item = OrderModel::find($order_id);

            $forward_link = '';
            if ($request->order_status == "CLOSE") {
             
                if (isset($request->save_order) && $request->save_order) {
                    $forward_link = route('order_receipt', ['slack' => $slack]);
                } else {
                    $forward_link = route('order_summary', ['slack' => $slack]);
                }
                if($prev_order_status == 7 || $prev_order_status == 1 ){ // for checking the current bill was in PARTIAL_PAYMENT or CLOSED status
                    
                    $transaction_type_data = MasterTransactionTypeModel::select('id')
                        ->where('transaction_type_constant', '=', 'INCOME')->first();
                    if (empty($transaction_type_data)) {
                        throw new Exception("Invalid transaction type selected", 400);
                    }
                    $previous_paid_amount = TransactionModel::select('id')->where([
                        ['bill_to', '=', 'POS_ORDER'],
                        ['bill_to_id', '=', $order_details->id ],
                        ['transaction_type', '=', $transaction_type_data->id],
                    ])->sum('amount');
                    if($previous_paid_amount >= $order_details->total_order_amount){
                        throw new Exception("The order total amount of $order_details->total_order_amount SAR has already been paid!", 400);
                    }
                    $pending_amount = bcsub($request->total_order_amount, $previous_paid_amount, 2);

                    $this->record_order_payment_transaction($slack, $pending_amount);
                }else{
                    $this->record_order_payment_transaction($slack);
                }
                // $this->record_hrm_transaction($order['slack'], $forward_link);

                $notification_api = new NotificationAPI();
                $notification_api->send_sms('POS_SALE_BILL_MESSAGE', $slack);
            } else if (in_array($request->order_status, ['IN_KITCHEN','PARTIAL_PAYMENT'])) {
                $forward_link = route('order_summary', ['slack' => $slack]);
                if($request->order_status == 'PARTIAL_PAYMENT'){
                    $paid_amount = isset($request->paid_amount)?$request->paid_amount:0;
                    $this->record_order_payment_transaction($slack, $paid_amount);
                }else{
                    $this->record_order_payment_transaction($slack);
                }
            }
            $item_data = new OrderResource($order_item);

            $store_detail = StoreModel::find($request->logged_user_store_id);
            $separate_api = 0;
            $transaction_data = $this->get_order_pending_payment_data($slack, $separate_api);
            $qr_code = \Salla\ZATCA\GenerateQrCode::fromArray([
                new \Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),
                new \Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                new \Salla\ZATCA\Tags\InvoiceDate(Carbon::now()->format('Y-m-d h:i')),
                new \Salla\ZATCA\Tags\InvoiceTotalAmount($item_data['total_order_amount']),
                new \Salla\ZATCA\Tags\InvoiceTaxAmount($item_data['total_tax_amount'])
            ])->toBase64();

            return response()->json($this->generate_response(
                array(
                    "message" => "Order updated successfully",
                    "data" => (string) $slack,
                    "link" => $forward_link,
                    "qr_code" => $qr_code,
                    "device_slack" => (isset($request->device_slack)) ? $request->device_slack : 0,
                    "order" => $item_data,
                    "transaction_data" => $transaction_data,
                ),
                'SUCCESS'
            ));
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
        //     ];
        // }
        catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function orderPartialPayment(Request $request){
        try {

            if (!check_access(['A_EDIT_ORDER'], true)) {
                throw new Exception("Invalid request", 400);
            }
            $validator = Validator::make($request->all(), [
                'paid_amount' => $this->get_validation_rules("numeric", true),
                'order_slack' => $this->get_validation_rules("slack", true),
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }
               
            $slack = $request->order_slack;
            $order_details = OrderModel::where('slack', $slack)->first();
            $prev_order_status = $order_details->status;
            DB::beginTransaction();

            $paid_amount = isset($request->paid_amount)?$request->paid_amount:0;
            $notes = isset($request->notes)?$request->notes:'';
            if($paid_amount <= 0){
                throw new Exception("The partially paid amount should be greater than zero!", 400);
            }

            if($prev_order_status == 7){ // for checking the current bill was in PARTIAL_PAYMENT status
                
                $transaction_type_data = MasterTransactionTypeModel::select('id')->where('transaction_type_constant', '=', 'INCOME')->first();
                if (empty($transaction_type_data)) {
                    throw new Exception("Invalid transaction type selected", 400);
                }
                $previous_paid_amount = TransactionModel::select('id')->where([
                    ['bill_to', '=', 'POS_ORDER'],
                    ['bill_to_id', '=', $order_details->id ],
                    ['transaction_type', '=', $transaction_type_data->id],
                ])->sum('amount');
                if($previous_paid_amount >= $order_details->total_order_amount){
                    throw new Exception("The order total amount of $order_details->total_order_amount SAR has already been paid!", 400);
                }
                $total_paid_amount_temp = $previous_paid_amount + $paid_amount;
                if($total_paid_amount_temp > $order_details->total_order_amount){
                    throw new Exception("The partially paid amount should not be greater than the order total amount of $order_details->total_order_amount SAR!", 400);
                }
                $this->record_order_payment_transaction($slack, $paid_amount, $notes);
            }elseif($prev_order_status == 1){
                throw new Exception("This order number - $order_details->order_number has already been Closed!", 400);
            }

            $order['updated_at'] = now();
            $order['updated_by'] = $request->logged_user_id;
            $action_response = OrderModel::where('slack', $slack)->update($order);

            DB::commit();
            $separate_api = 0;
            $transaction_data = $this->get_order_pending_payment_data($slack, $separate_api);

            $order = OrderModel::where('slack', $slack)->first();
            $order_data = new OrderResource($order);
     
            $store_detail = StoreModel::find($request->logged_user_store_id);

            $qr_code = \Salla\ZATCA\GenerateQrCode::fromArray([
                new \Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),
                new \Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                new \Salla\ZATCA\Tags\InvoiceDate(Carbon::now()->format('Y-m-d h:i')),
                new \Salla\ZATCA\Tags\InvoiceTotalAmount($order_data->total_order_amount),
                new \Salla\ZATCA\Tags\InvoiceTaxAmount($order_data->total_order_amount)
            ])->toBase64();

            return response()->json($this->generate_response(
                array(
                    "message" => "Order partial payment updated successfully",
                    "data" => (string) $slack,
                    "transaction_data" => $transaction_data,
                    "order" => $order_data,
                    "qr_code" => $qr_code,
                ),
                'SUCCESS'
            ));
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
        //     ];
        // }
        catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        } 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $slack)
    {
        try {

            if (!check_access(['A_DELETE_ORDER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $order_detail = OrderModel::select('id', 'store_id', 'order_number')->where('slack', $slack)->first();
            if (empty($order_detail)) {
                throw new Exception("Invalid order provided", 400);
            }
            $order_id = $order_detail->id;
            $store_id = $order_detail->store_id;
            $order_number = $order_detail->order_number;

            $order_products = OrderProductModel::where('order_id', $order_id)->get()->toArray();

            DB::beginTransaction();

            array_walk($order_products, function (&$item, $key) use ($request) {

                $product = ProductModel::find($item['product_id']);
                if ($product['quantity'] != -1) {
                    $product->increment('quantity', $item['quantity']);
                }


                $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity'], 'increment');
            });


            TransactionModel::where([
                ['bill_to', '=', 'POS_ORDER'],
                ['bill_to_id', '=', $order_id],
            ])->delete();
            OrderProductModel::where('order_id', $order_id)->delete();
            OrderModel::where('id', $order_id)->delete();
            // deleting hrm finance details created by POS
            AccTransactionModel::where('order_slack', $slack)->delete();

            // $store_id = UserModel::find(session('user_id'))->store_id;

            // $store_details = StoreModel::select('store_order_number')->where('id', $store_id)->where('status', 1)->orderBy('id', 'desc')->first();

            // if ($order_number == $store_details->store_order_number) {
            //     $store = StoreModel::find($store_id);
            //     $store->decrement('store_order_number', 1);
            // }

            DB::commit();

            $forward_link = route('orders');

            return response()->json($this->generate_response(
                array(
                    "message" => "Order deleted successfully",
                    "data" => $slack,
                    "link" => $forward_link
                ),
                'SUCCESS'
            ));

        } catch (Exception $e) {
            DB::rollback();

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function form_order_array($request, $slack = '', $prev_order_status = '')
    {
        $this->checkDiscount($request);
        if ($request->order_status == "SAVE") {
            $request->order_status = "CLOSE";
            $request->save_order = true;
        }

        $cart = json_decode($request->cart);
        if (empty((array) $cart)) {
            throw new Exception("Cart cannot be empty");
        }

        if (!empty($cart)) {
            // $this->checkProductDiscount($cart,$request->logged_user_store_id);
            $validate_response_data = $this->validate_order_close_request($request, $slack);

        //    dd($request->payment_method_slack, $request->payment_method);

            switch ($request->order_status) {
                case 'PARTIAL_PAYMENT':
                    $status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'PARTIAL_PAYMENT')->first();
                    $order_status = $status_data->value;
                    break;
                case 'HOLD':
                    $status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'HOLD')->first();
                    $order_status = $status_data->value;
                    break;
                case 'CLOSE':

                    $status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'CLOSED')->first();
                    $payment_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'PAYMENT_PENDING')->first();
                    $order_status = $status_data->value;
                    if (!isset($request->save_order) && ($prev_order_status != 1 && $prev_order_status != 7)) {

                        switch (strtoupper($validate_response_data['payment_method']->payment_constant)) {
                            case "STRIPE":
                                $order_status = $payment_status_data->value;
                                break;
                            case "PAYPAL":
                                $order_status = $payment_status_data->value;
                                break;
                            case "CASH":
                                $order_status = $payment_status_data->value;
                                break;
                        }
                    }

                    break;
                case 'IN_KITCHEN':
                    $status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_STATUS', 'IN_KITCHEN')->first();
                    $order_status = $status_data->value;
                    break;
            }

            if($request->order_status == 'HOLD'){
                if(isset($request->payment_option) && !empty($request->payment_option)){
                    $payment_option = (string) $request->payment_option;
                }else{
                    $payment_option = null;
                }
            }else{
                $payment_option = (string) $request->payment_option;
            }
            $restaurant_mode = (isset($request->restaurant_mode)) ? ($request->restaurant_mode == 1) ? 1 : 0 : 0;


            $store_data = StoreModel::select('tax_code_id', 'discount_code_id', 'tax_codes.tax_code', 'discount_codes.discount_code', 'tax_codes.total_tax_percentage', 'discount_codes.discount_percentage', 'currency_name', 'currency_code')
                ->taxcodeJoin()
                ->discountcodeJoin()
                ->where([
                    ['stores.id', '=', $request->logged_user_store_id],
                    ['stores.status', '=', 1]
                ])
                ->first();
            if (empty($store_data)) {
                throw new Exception("Invalid store selected");
            }

            $store_level_total_tax_percentage = isset($store_data->total_tax_percentage) ? $store_data->total_tax_percentage : 0.00;
            $store_level_total_discount_percentage = isset($store_data->discount_percentage) ? $store_data->discount_percentage : 0.00;
            $store_tax_code_id = isset($store_data->tax_code_id) ? $store_data->tax_code_id : '';
            $store_tax_code = isset($store_data->tax_code) ? $store_data->tax_code : '';

            $total_product_additional_discount_amount = 0.0;
            $additional_discount_amount = 0.0;
            $total_discount_amount = 0.0;
            $additional_discount_percentage = isset($request->additional_discount_percentage) ? $request->additional_discount_percentage : 0.00;
            $product_additional_discount_amount = isset($request->additional_discount_amount) ? $request->additional_discount_amount : 0.00;
            $product_total_tax_amount = 0.0;
            $array_cart = json_decode(json_encode($cart), true);
            $cart_quantity_total =  count($array_cart);
            $tax_amount = 0.0;
            $total_modifier_amount = 0.0;
            $bonat_discount = FALSE;
            $bonat_coupon = null;

            foreach ($cart as $cart_item_key => $cart_item) {

                // converting combo item measurement to actual product quantity
                if( isset($cart_item->combo_id) && $cart_item->combo_id > 0){
                    $product = ProductModel::where('products.slack', '=', $cart_item->product_slack)->first();
                    if(isset($product) && isset($cart_item->product_measurement) && $cart_item->product_measurement != null ){
                        $cart_item->quantity = $this->__get_measurement_conversion_value($cart_item->product_measurement,$product->measurements,$cart_item->combo_quantity);
                    }
                }

                if(isset($cart_item->gift_flag) && $cart_item->gift_flag == true){
                    $cart_item->price = $cart_item->price_actual;
                }
                $total_modifier_amount = 0;
                DB::enableQueryLog();
                $product_data = ProductModel::select('products.*', 'tax_codes.id as tax_code_id', 'discount_codes.id as discount_code_id', 'tax_codes.tax_code', 'discount_codes.discount_code', 'tax_codes.total_tax_percentage as tax_percentage', 'discount_codes.discount_percentage as discount_percentage')
                    ->where('products.slack', '=', $cart_item->product_slack)
                    ->categoryJoin()
                    //    ->supplierJoin()
                    ->taxcodeJoin()
                    ->discountcodeJoin()
                    ->categoryActive()
                    // ->supplierActive()
                    //  ->taxcodeActive()
                    ->quantityCheck($cart_item->quantity)
                    ->first();
                //$tax_amount = $cart_item->total_tax;
                if (empty($product_data)) {
                    throw new Exception("Product code: " . $cart_item->product_code . " is out of stock", 400);
                }

                $sub_total_purchase_price_excluding_tax = bcmul($cart_item->quantity, $product_data->purchase_amount_excluding_tax, 2);
                if (isset($cart_item->modifier_amount) && $cart_item->modifier_amount > 0) {

                    $modifier_amount = $this->calculate_reverse_percent_amount($cart_item->modifier_amount, $cart_item->tax_percentage);
                    if ($cart_item->is_tobacco_tax > 0) {
                        $modifier_amount = $this->calculate_reverse_percent_amount($modifier_amount, $cart_item->tobacco_tax_percentage);
                    }

                    $total_modifier_amount += ($modifier_amount * $cart_item->quantity);
                }
                $cart_bonat_discount = isset($cart_item->bonat_discount) ? $cart_item->bonat_discount : FALSE;
                if ($cart_bonat_discount == TRUE) {
                    $bonat_price = isset($cart_item->bonat_price)  ? $cart_item->bonat_price : 0;
                    // $total_amount =  bcmul($cart_item->quantity, $bonat_price, 2);
                    $total_amount =  $cart_item->quantity * $bonat_price;
                    $total_amount =  number_format($total_amount, 2);
                    $bonat_discount = TRUE;
                } else {
                    // $total_amount =  bcmul($cart_item->quantity, $product_data->sale_amount_excluding_tax);

                    $total_amount =  ($cart_item->quantity * $cart_item->price) + $total_modifier_amount;
                    // $total_amount = number_format($total_amount,2);
                }

                $bonat_coupon = isset($cart_item->bonat_coupon) ? $cart_item->bonat_coupon : NULL;

                $discount_amount = $this->calculate_discount($total_amount, $cart_item->discount_percentage, isset($cart_item->discount_value) ? $cart_item->discount_value : 0);
                $discount_amount = number_format($discount_amount, 4, '.', '');

                // $total_amount_after_discount = bcsub($total_amount, $discount_amount, 2);
                $total_amount_after_discount = $total_amount - $discount_amount;
                $tax_amount = $this->calculate_tax($total_amount_after_discount, $cart_item->tax_percentage);

                if (!isset($cart_item->tax_percentage)) {
                    $cart_item->tax_percentage = 0.0;
                }

                // $item_total = bcadd($total_amount_after_discount, $tax_amount, 2);
                $item_total = $total_amount_after_discount + $tax_amount;


                // if (isset($product_data->is_taxable) && ($product_data->is_taxable == 1) && isset($store_tax_code_id)) {
                //     $product_tax_component_data = TaxcodeTypeModel::select('tax_type', 'tax_percentage')->where("tax_code_id", $product_data->tax_code_id)->get()->toArray();
                //     foreach ($product_tax_component_data as $key => $product_tax_component_data_item) {
                //         $tax_component_amount = $this->calculate_tax($total_amount_after_discount, $product_tax_component_data_item['tax_percentage']);
                //         $product_tax_component_data[$key]['tax_amount'] = $tax_component_amount;
                //     }
                //     $product_tax_component_data = json_encode($product_tax_component_data);
                // }
                $product_tax_component_data = $cart_item->tax_components;
                // $store_tax = TaxCodeModel::select('total_tax_percentage')->where('id', $store_tax_code_id)->first();


                //dd($total_modifier_amount);
                $is_ready_to_serve = 0;
                if ($restaurant_mode == 1 && $slack != '') {

                    $order_data = OrderModel::select('orders.id')->where('orders.slack', $slack)->first();

                    $kitchen_order_product_item = OrderProductModel::select('quantity', 'is_ready_to_serve')->where([
                        ['order_products.order_id', '=', $order_data->id],
                        ['order_products.product_slack', '=', $cart_item_key]
                    ])
                        ->first();
                    if (!empty($kitchen_order_product_item)) {
                        $is_ready_to_serve = ($cart_item->quantity > $kitchen_order_product_item->quantity) ? 0 : $kitchen_order_product_item->is_ready_to_serve;
                    }
                }

                // $product_total_tax_amount = bcadd($product_total_tax_amount, $tax_amount, 2);

                if ($product_data->is_taxable == 1) {
                    $order_products_tax_code_id =   $store_tax_code_id;
                    $order_products_tax_percentage =   $store_level_total_tax_percentage;
                    $order_products_tax_code =   $store_tax_code;
                } else {
                    $order_products_tax_code_id =   NULL;
                    $order_products_tax_percentage =   0.00;
                    $order_products_tax_code =   NULL;
                }
                $is_gifted = (isset($cart_item->gift_flag) && $cart_item->gift_flag == true) ? 1 : 0 ; 
                $dataset = [
                    'order_id' => 0,
                    'product_slack' => $product_data->slack,
                    'product_id' => $product_data->id,
                    'product_code' => $product_data->product_code,
                    'name' => $product_data->name,
                    'quantity' => $cart_item->quantity,
                    'purchase_amount_excluding_tax' => $product_data->purchase_amount_excluding_tax,
                    'sale_amount_excluding_tax' => ($cart_item->price > 0) ? $cart_item->price : 0,
                    'discount_code_id' => isset($product_data->discount_code_id) ? $product_data->discount_code_id : NULL,
                    'discount_code' => isset($product_data->discount_code) ? $product_data->discount_code : NULL,
                    'discount_percentage' => isset($cart_item->discount_percentage) ? $cart_item->discount_percentage : 0,
                    'tax_code_id' => isset($product_data->tax_code_id) ? $product_data->tax_code_id : NULL,
                    'tax_code' => isset($product_data->tax_code) ? $product_data->tax_code : NULL,
                    'tax_percentage' => $cart_item->tax_percentage,
                    'tax_components' => ($cart_item->tax_percentage > 0) ? $product_tax_component_data : NULL,
                    'is_tobacco_tax' => $cart_item->is_tobacco_tax ?? 0,
                    'tobacco_tax_percentage' => $cart_item->tobacco_tax_percentage ?? 0,
                    'tobacco_tax_components' => ($cart_item->is_tobacco_tax > 0) ? $cart_item->tobacco_tax_components : NULL,
                    'sub_total_purchase_price_excluding_tax' => $sub_total_purchase_price_excluding_tax,
                    'sub_total_sale_price_excluding_tax' => $total_amount,
                    'discount_amount' => $discount_amount,
                    'total_after_discount' => $total_amount_after_discount,
                    'tax_amount' => $tax_amount,
                    'total_amount' => $item_total,
                    'is_ready_to_serve' => (isset($is_ready_to_serve)) ? $is_ready_to_serve : 0,
                    'total_modifier_amount' => $total_modifier_amount,
                    'modifiers' => (isset($cart_item->modifiers)) ? $cart_item->modifiers : '',
                    'note' => (isset($cart_item->note)) ? $cart_item->note : '',
                    'bonat_discount' => isset($cart_item->bonat_discount) ? $cart_item->bonat_discount : 0,
                    'bonat_discount_price' => isset($cart_item->bonat_price) ? $cart_item->bonat_price : 0,
                    'bonat_coupon' => ($bonat_coupon != null) ? $bonat_coupon : NULL,
                    'is_gifted' => $is_gifted,
                    'combo_id' => (isset($cart_item->combo_id) && $cart_item->combo_id != null) ? $cart_item->combo_id : '',
                    'combo_cart_id' => (isset($cart_item->combo_cart_id) && $cart_item->combo_cart_id != null) ? $cart_item->combo_cart_id : '',
                ];
                $order_products[] = $dataset;
            }

            $total_purchase_amount_excluding_tax_array = data_get($order_products, '*.sub_total_purchase_price_excluding_tax', 0);
            $total_purchase_amount_excluding_tax = array_sum($total_purchase_amount_excluding_tax_array);

            $total_sale_amount_excluding_tax_array = data_get($order_products, '*.sub_total_sale_price_excluding_tax', 0);
            // $total_sale_amount_excluding_tax = array_sum($total_sale_amount_excluding_tax_array);
            $total_sale_amount_excluding_tax = $request->sale_amount_subtotal_excluding_tax;

            $store_level_total_discount_amount = $this->calculate_discount($total_sale_amount_excluding_tax, $additional_discount_percentage);


            $total_discount_amount_array = data_get($order_products, '*.discount_amount');

            $product_level_total_discount_amount = array_sum($total_discount_amount_array);
            $product_level_total_discount_amount = number_format($product_level_total_discount_amount, 2, '.', '');

            $total_discount_before_additional_discount = number_format($product_level_total_discount_amount, 2, '.', '');

            $total_amount_before_additional_discount = ($total_sale_amount_excluding_tax - $product_level_total_discount_amount) + $total_modifier_amount;

            if ($request->discount_type == 1) {
                $additional_discount_amount = $product_additional_discount_amount;
            } else {
                $additional_discount_amount = $this->calculate_discount($total_sale_amount_excluding_tax, $additional_discount_percentage);
            }
            $total_discount_amount = $additional_discount_amount;
            // $total_amount_after_discount = $total_amount_before_additional_discount - $additional_discount_amount;
            $total_amount_after_discount = $request->total_after_discount;
            $store_level_total_tax_amount = $this->calculate_tax($total_amount_after_discount, $store_level_total_tax_percentage);

            // $total_tax_amount = $this->calculate_tax($total_amount_after_discount, $store_level_total_tax_percentage);
            $total_tax_amount = $request->total_tax_amount;

            $product_level_total_tax_amount = $total_tax_amount;

            // if (isset($store_data->tax_code_id)) {
            //     $store_tax_component_data = TaxcodeTypeModel::select('tax_type', 'tax_percentage')->where("tax_code_id", $store_data->tax_code_id)->get()->toArray();
            //     foreach ($store_tax_component_data as $key => $store_tax_component_data_item) {
            //         $tax_component_amount = $this->calculate_tax($total_amount_after_discount, $store_tax_component_data_item['tax_percentage']);
            //         $store_tax_component_data[$key]['tax_amount'] = $tax_component_amount;
            //     }
            //     $store_tax_component_data = json_encode($store_tax_component_data);
            // }
            $store_tax_component_data = $request->store_level_tax_components??NULL;


            // $total_order_amount = bcadd($total_amount_after_discount, $total_tax_amount, 2);
            $total_amount_after_discount = number_format($total_amount_after_discount, 2, '.', '');
            // $total_order_amount = $total_amount_after_discount + $total_tax_amount;
            $total_order_amount = $request->total_order_amount;

            if ($total_order_amount < 0) {
                throw new Exception("Grand Total cannot be Negative.");
            }

            
            if (isset($request->customer_slack)) {
                $customer = CustomerModel::select('id as customer_id', 'phone', 'email')->where('slack', $request->customer_slack)->active()->first();
            } else if ($request->customer_name != "" || $request->customer_number != "" || $request->customer_email != "") {
                // dd($request->customer_number);
                $customer = [
                    'customer_name'   => $request->customer_name ?? '',
                    'customer_number'   => $request->customer_number ?? '',
                    'customer_email'    => $request->customer_email ?? ''
                ];
                $customer = $this->handle_customer($customer);
            } else {
                $customer['customer_id'] = "";
                $customer['phone'] = "";
                $customer['email'] = "";
            }

            if (isset($request->save_order) && $request->save_order ) {
                $validate_response_data['order_type']->id = 2;
                $validate_response_data['billing_type']->id = 2;
                $validate_response_data['billing_type']->label = 'QUICK BILL';
                $order_status = 1;
            }
            // dd($request->save_order, $order_status);
            // store opening logic
            if(isset($request->value_date) && $request->value_date != ''){
                $request->value_date = $request->value_date;
            }else{
                $request->value_date = $this->get_order_value_date();
            }
            $store_discount_data = [];
            if(($request->additional_discount_percentage > 0 || $additional_discount_amount > 0)  ){
                if(!isset($request->discount_code_id) ){
                    if($request->additional_discount_percentage > 0){
                        $store_discount_data = DiscountcodeModel::select('id','discount_code')->where('discount_percentage',$request->additional_discount_percentage)
                                                ->where('discounttype','cashier')->first();
                    }else{
                        $store_discount_data = DiscountcodeModel::select('id','discount_code')->where('discount_value',$additional_discount_amount)
                                                ->where('discounttype','cashier')->first();
                    }
                }
            }
            if(!empty($store_discount_data) && (!isset($request->discount_code_id) || empty($request->discount_code_id))){
                $discount_code_id = $store_discount_data->id;
                $discount_code_name = $store_discount_data->discount_code;
            }elseif(isset($request->discount_code_id) && !empty($request->discount_code_id)){
                $discount_code_id = $request->discount_code_id;
                $discount_code_name = $request->discount_code_name;
            }
            $order = [
                "customer_id" => $customer['customer_id'],
                "customer_phone" => $customer['phone'],
                "customer_email" => $customer['email'],
                "counter_slack" => $request->counter_slack,
                "counter_name" => $request->counter_name,

                "store_level_discount_code_id" => isset($discount_code_id) ? $discount_code_id : NULL,
                "store_level_discount_code" => isset($discount_code_name) ? $discount_code_name : NULL,
                "store_level_total_discount_percentage" => $store_level_total_discount_percentage,
                "store_level_total_discount_amount" => (string) $store_level_total_discount_amount,
                "product_level_total_discount_amount" => (string) $product_level_total_discount_amount,

                "store_level_tax_code_id" => isset($store_data->tax_code_id) ? $store_data->tax_code_id : NULL,
                "store_level_tax_code" => isset($store_data->tax_code) ? $store_data->tax_code : NULL,
                "store_level_total_tax_percentage" => (string) $store_level_total_tax_percentage,
                "store_level_total_tax_amount" => (string) $total_tax_amount,
                'store_level_total_tax_components' => $store_tax_component_data,

                "product_level_total_tax_amount" => (string) $total_tax_amount,

                "purchase_amount_subtotal_excluding_tax" => (string) $total_purchase_amount_excluding_tax,
                // "sale_amount_subtotal_excluding_tax" => (string) $total_sale_amount_excluding_tax,
                "sale_amount_subtotal_excluding_tax" => (string)$request->sale_amount_subtotal_excluding_tax,

                "total_discount_before_additional_discount" => (string) $total_discount_before_additional_discount,
                "total_amount_before_additional_discount" => (string) $total_amount_before_additional_discount,

                "additional_discount_percentage" => $request->additional_discount_percentage,
                "additional_discount_amount" => (string) $additional_discount_amount,
                "total_discount_amount" => (string) $total_discount_amount,
                "total_after_discount" => (string)  $total_amount_after_discount,
                "total_tax_amount" => (string) $total_tax_amount,
                "total_order_amount" => (string) $total_order_amount,
                "total_order_amount_rounded" => (string) round($total_order_amount),

                'payment_method_id' => (isset($validate_response_data['payment_method']) && $validate_response_data['payment_method'] != "") ? $validate_response_data['payment_method']->id : '',
                'payment_method_slack' => (isset($validate_response_data['payment_method']) && $validate_response_data['payment_method'] != "") ? $validate_response_data['payment_method']->slack : '',
                'payment_method' => (isset($validate_response_data['payment_method']) && $validate_response_data['payment_method'] != "") ? $validate_response_data['payment_method']->label : '',

                "currency_name" => $store_data->currency_name,
                "currency_code" => $store_data->currency_code,

                'business_account_id' => (isset($validate_response_data['business_account'])) ? $validate_response_data['business_account']->id : '',

                'restaurant_mode' => $restaurant_mode,

                'order_type_id' => (isset($validate_response_data['order_type'])) ? $validate_response_data['order_type']->id : '',
                'order_type' => (isset($validate_response_data['order_type'])) ? $validate_response_data['order_type']->label : '',

                'table_id' => (isset($validate_response_data['restaurant_table'])) ? $validate_response_data['restaurant_table']->id : '',
                'table_number' => (isset($validate_response_data['restaurant_table'])) ? $validate_response_data['restaurant_table']->table_number : '',
                'waiter_id' => (isset($validate_response_data['waiter'])) ? $validate_response_data['waiter']->id : '',

                'bill_type_id' => (isset($validate_response_data['billing_type'])) ? $validate_response_data['billing_type']->id : '',
                'bill_type' => (isset($validate_response_data['billing_type'])) ? $validate_response_data['billing_type']->label : '',

                'status' => $order_status,
                'discount_type' => (string) $request->discount_type,
                'payment_option' => $payment_option,
                'cash_amount' => (isset($request->cash_amount)) ? (string) $request->cash_amount : 0.00 ,
                'change_amount' => (isset($request->change_amount)) ? (string) $request->change_amount : 0.00 ,
                'card_name' => (isset($request->card_name)) ? $request->card_name : null,
                'credit_amount' => (isset($request->credit_amount)) ? (string) $request->credit_amount : 0.00 ,
                'value_date' => $request->value_date,
                'transaction_id' => $request->transaction_id,
                'bonat_discount' => isset($bonat_discount) ? $bonat_discount : 0,
            ];


            if ($restaurant_mode == 1 && isset($slack)) {
                $order_products_collection = collect($order_products);

                $ready_to_serve_array = $order_products_collection->map(function ($item, $key) {
                    return $item['is_ready_to_serve'];
                })->toArray();
                $not_ready_to_serve_exists = in_array(0, $ready_to_serve_array) ? true : false;

                if ($not_ready_to_serve_exists == true) {
                    $kitchen_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_KITCHEN_STATUS', 'STARTED_PREPARING')->first();
                    if (!empty($kitchen_status_data) && $request->order_status == 'IN_KITCHEN') {
                        $order['kitchen_status'] = $kitchen_status_data->value;
                    } else if ($request->order_status == 'CLOSE') {
                        $kitchen_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_KITCHEN_STATUS', 'ORDER_READY')->first();
                        $order['kitchen_status'] = $kitchen_status_data->value;
                    }
                }
            }
        }

        return [
            'order_data' => $order,
            'order_products_data' => $order_products,
            'bonat_coupon' => $bonat_coupon
        ];
    }

    function calculate_reverse_percent_amount($item_total, $tax_percentage)
    {
        $amount = ($item_total / (100 + $tax_percentage)) * 100;
        return $amount;
    }

    private function validate_order_close_request($request, $slack = "")
    {

        $response = [];
        // dd($request->payment_method_slack);
        if ($request->order_status == 'CLOSE' || $request->order_status == 'PARTIAL_PAYMENT') {
            if ($request->payment_method != "") {
                $payment_method = PaymentMethodModel::select('id', 'slack', 'label', 'payment_constant')
                    ->where([
                        ['payment_methods.slack', '=', $request->payment_method_slack]
                    ])
                    ->active()
                    ->first();
                if (empty($payment_method)) {
                    throw new Exception("Invalid Payment method selected");
                }
            } else {
                $payment_method = "";
            }
            $response['payment_method'] = $payment_method;

            $business_account = AccountModel::select('id')
                ->where([
                    ['accounts.slack', '=', $request->business_account]
                ])
                ->active()
                ->first();
            if (empty($business_account)) {
                throw new Exception("Invalid Business Account selected");
            }
            $response['business_account'] = $business_account;
            
        }

        if (in_array($request->order_status, ['HOLD', 'IN_KITCHEN', 'CLOSE','PARTIAL_PAYMENT'])) {
            $order_type = MasterOrderTypeModel::select('id', 'label', 'order_type_constant')
                ->active()
                ->where([
                    ['restaurant', '=', 1],
                    ['order_type_constant', '=', $request->restaurant_order_type],
                ])->first();

            if (empty($order_type)) {
                throw new Exception("Invalid Order Type selected");
            }
            $response['order_type'] = $order_type;


            $restaurant_table = TableModel::select('id', 'table_number')
                ->active()
                ->where([
                    ['slack', '=', $request->restaurant_table],
                ])->first();
            if (empty($restaurant_table) && $request->restaurant_order_type == 'DINEIN') {
                throw new Exception("Invalid Table selected");
            }
            $response['restaurant_table'] = $restaurant_table;

            if (!empty($restaurant_table)) {
                $occupied_tables = OrderModel::select('table_id', 'table_number')->whereNotNull('table_id')->inkitchen()->where('table_id', $restaurant_table->id)
                    ->when($slack, function ($query, $slack) {
                        return $query->where('slack', '!=', $slack);
                    })->first();
                if (!empty($occupied_tables)) {
                    throw new Exception("This table is already occupied");
                }
            }

            if (!empty($request->waiter)) {
                $waiter = UserModel::select('id')
                    ->active()
                    ->where([
                        ['slack', '=', $request->waiter],
                    ])->first();
                if (empty($waiter)) {
                    throw new Exception("Invalid Waiter selected");
                }
                $response['waiter'] = $waiter;
            }


            $billing_type = MasterBillingTypeModel::select('id', 'billing_type_constant', 'label')
                ->active()
                ->where([
                    ['billing_type_constant', '=', $request->billing_type]
                ])
                ->first();
            if (empty($billing_type)) {
                throw new Exception("Invalid Billing Type selected");
            }

            $response['billing_type'] = $billing_type;
        }


        return $response;
    }

    public function calculate_tax($item_total, $tax_percentage)
    {
        $tax_amount = 0.0;
        $tax_amount = ($tax_percentage / 100) * $item_total;

        return $tax_amount;
        // return round($tax_amount,2);
    }

    public function calculate_discount($item_total, $discount_percentage, $discount_value = 0)
    {
        $discount_amount = 0.0;
        if ($discount_value == 0) {
            $discount_amount = ((float)$discount_percentage / 100) * ((float)$item_total);
        } else {
            $discount_amount = (float)$discount_value;
        }
        return $discount_amount;
    }

    private function handle_customer($customer)
    {

        $customer_name = trim($customer['customer_name']);
        $customer_phone = trim($customer['customer_number']);
        $customer_email = trim($customer['customer_email']);
        
        if ($customer_phone != '' || $customer_email != '' || $customer_name != '') {
            $customer_data = CustomerModel::select('id', 'name', 'email', 'phone')
                ->where(function ($query) use ($customer_email, $customer_phone, $customer_name) {
                    if (!empty($customer_email)) {
                        $query->where('email', '=', $customer_email);
                    }
                    if (!empty($customer_phone)) {
                        $query->orWhere('phone', '=', $customer_phone);
                    }
                    // if (!empty($customer_name)) {
                    //     $query->orWhere('name', '=', $customer_name);
                    // }
                })
                ->first();

            if (empty($customer_data)) {
                $customer = [
                    'slack'         => $this->generate_slack("customers"),
                    'customer_type' => 'WALKIN',
                    'name'          => $customer_name??'',
                    'email'         => (isset($customer_email) && ($customer_email != '' && $customer_email != null)) ? $customer_email : '',
                    'phone'         => (isset($customer_phone) && ($customer_phone != '' && $customer_phone != null)) ? $customer_phone : '',
                    'status'        => 1,
                    "created_by"    => request()->logged_user_id
                ];
                $customer_id = CustomerModel::create($customer)->id;
            } else {
                
                $customer_id = $customer_data->id;
                $customer = [
                    'name'          => $customer_name??'',
                    'email'         => (isset($customer_email) && ($customer_email != '' && $customer_email != null)) ? $customer_email : $customer_data->email,
                    'phone'         => (isset($customer_phone) && ($customer_phone != '' && $customer_phone != null)) ? $customer_phone : $customer_data->phone,
                    'status'        => 1,
                    'updated_by'    => request()->logged_user_id
                ];

                $action_response = CustomerModel::where('id', $customer_id)
                    ->update($customer);
                
                CustomerModel::where('id', '!=', $customer_id)
                    ->where(function ($query) use ($customer_email, $customer_phone) {
                        if (!empty($customer_email)) {
                            $query->where('email', '=', $customer_email);
                        }
                        if (!empty($customer_phone)) {
                            $query->orWhere('phone', '=', $customer_phone);
                        }
                    })->delete();
            }
            $customer_data = CustomerModel::select('id', 'email', 'phone','name')
                ->where(function ($query) use ($customer_email, $customer_phone) {
                    if (!empty($customer_email)) {
                        $query->where('email', '=', $customer_email);
                    }
                    if (!empty($customer_phone)) {
                        $query->orWhere('phone', '=', $customer_phone);
                    }
                })
                ->first();
        } else {
            $customer_data = CustomerModel::select('id', 'email', 'phone','name')
                ->where('customer_type', '=', 'DEFAULT')
                ->active()
                ->first();
            $customer_id = $customer_data->id;
        }
        
        $customer = [
            'customer_id' => $customer_id,
            'name'       => $customer_data->name ?? '',
            'email'       => $customer_data->email ?? '',
            'phone'       => $customer_data->phone ?? '',
        ];
        return $customer;
    }

    public function filter_orders(Request $request)
    {
        try {

            $keyword = $request->keyword;

            $order_list = OrderModel::select("*")
                ->where('order_number', 'like', $keyword . '%')
                ->orWhere('customer_email', 'like', $keyword . '%')
                ->orWhere('customer_phone', 'like', $keyword . '%')
                ->limit(25)
                ->get();

            $orders = OrderResource::collection($order_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Order filtered successfully",
                    "data" => $orders
                ),
                'SUCCESS'
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

    public function record_order_payment_transaction($order_slack, $paid_amount = 0, $notes = '')
    {
        $order_detail = OrderModel::select('*')->where('slack', $order_slack)->first();
        $previous_paid_amount = 0;
        $transaction_type_data = MasterTransactionTypeModel::select('id')
            ->where('transaction_type_constant', '=', 'INCOME')
            ->first();
        if (empty($transaction_type_data)) {
            throw new Exception("Invalid transaction type selected", 400);
        }

        $customer_data = CustomerModel::select('id', 'name', 'email', 'phone', 'address')
            ->where('id', '=', $order_detail->customer_id)
            ->first();
            // check condition for partial payment paid amount.
        $previous_paid_amount = TransactionModel::select('id')->where([
                ['bill_to', '=', 'POS_ORDER'],
                ['bill_to_id', '=', $order_detail->id ],
                ['transaction_type', '=', $transaction_type_data->id],
            ])->sum('amount');
        if($paid_amount > 0 && $order_detail->status == 1){
            if($previous_paid_amount >= $order_detail->total_order_amount){
                throw new Exception("The order total amount of $order_detail->total_order_amount SAR has already been paid!", 400);
            }
        }
        if($paid_amount == 0){
            $paid_amount = $order_detail->total_order_amount;
        }
        $transaction = [
            "slack" => $this->generate_slack("transactions"),
            "store_id" => $order_detail->store_id,
            "transaction_code" => Str::random(6),
            "account_id" => $order_detail->business_account_id,
            "transaction_type" => $transaction_type_data->id,
            "payment_method_id" => $order_detail->payment_method_id,
            "payment_method" => $order_detail->payment_method,
            "bill_to" => 'POS_ORDER',
            "bill_to_id" => $order_detail->id,
            "bill_to_name" => (isset($customer_data->name)) ? $customer_data->name : 'Walkin Customer',
            "bill_to_contact" => $order_detail->customer_phone,
            "bill_to_address" => (isset($customer_data->address)) ? $customer_data->address : '',
            "currency_code" => $order_detail->currency_code,
            "amount" => $paid_amount,
            "pg_transaction_id" => '',
            "pg_transaction_status" => '',
            "notes" => $notes,
            "transaction_date" => date('Y-m-d'),
            "created_by" => request()->logged_user_id
        ];
        // dd($paid_amount);
        $transaction_id = TransactionModel::create($transaction)->id;

        $code_start_config = Config::get('constants.unique_code_start.transaction');
        $code_start = (isset($code_start_config)) ? $code_start_config : 100;

        $transaction_code = [
            "transaction_code" => ($code_start + $transaction_id)
        ];
        TransactionModel::where('id', $transaction_id)->update($transaction_code);
        // dd($paid_amount, $order_detail->status);
        if($paid_amount > 0 && $order_detail->status == 7 ){   // only for PARTIAL_PAYMENT
            $total_paid_amount = $previous_paid_amount + $paid_amount;  
            if($total_paid_amount >= $order_detail->total_order_amount){

                OrderModel::where('slack', $order_slack)->update(['status' => 1]); // update the bill to close status.
            }
        }
    }

    public function get_hold_list(Request $request)
    {
        try {
            if (!check_access(['MM_ORDERS'], true) || !check_access(['SM_POS_ORDERS'], true)) {
                throw new Exception("Invalid request", 400);
            }
            $hold_order_list = OrderModel::select('*')
                ->where('status',$request->order_status)
                // ->hold()
                ->orderBy('id', 'desc')
                // ->whereDate('created_at', '>', Carbon::now()->subDays(2))
                ->get();

            $hold_orders = OrderResource::collection($hold_order_list);
            // dd($hold_orders[0]->transactions);
            return response()->json($this->generate_response(
                array(
                    "message" => "Order list loaded successfully",
                    "data" => $hold_orders,
                ),
                'SUCCESS'
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

    public function get_in_kitchen_order_list(Request $request)
    {

        try {
            if (!check_access(['MM_RESTAURANT'], true) || !check_access(['SM_RESTAURANT_KITCHEN'], true)) {
                throw new Exception("Invalid request", 400);
            }

            if (!check_access(['A_VIEW_KITCHEN_ORDER_LISTING'], true)) {
                throw new Exception("You Don't Currently Have Permission to Access Listing. Please Request for Access.", 400);
            }

            $in_kitchen_order_list = OrderModel::select('*')
                ->inkitchen()
                ->orderBy('created_at', 'desc')
                // ->whereDate('created_at', '>', Carbon::now()->subDays(2))
                ->get();

            $in_kitchen_orders = OrderResource::collection($in_kitchen_order_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Kitchen order tickets loaded successfully",
                    "data" => $in_kitchen_orders,
                ),
                'SUCCESS'
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

    public function get_running_order_list(Request $request)
    {
        try {
            if (!check_access(['MM_ORDERS'], true) || !check_access(['SM_POS_ORDERS'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $page = $request->page;

            $running_order_list = new OrderCollection(OrderModel::select('*')
                ->inkitchen()
                ->whereDate('created_at', '>', Carbon::now()->subDays(5))
                ->orderBy('created_at', 'desc')
                ->paginate(10));

            return response()->json($this->generate_response(
                array(
                    "message" => "Running orders loaded successfully",
                    "data" => $running_order_list,
                ),
                'SUCCESS'
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

    public function update_kitchen_order_status(Request $request)
    {
        try {
            if (!check_access(['A_CHANGE_KITCHEN_ORDER_STATUS'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $validator = Validator::make($request->all(), [
                'order_slack' => $this->get_validation_rules("slack", true),
                'kitchen_status' => $this->get_validation_rules("string", true),
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $kitchen_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_KITCHEN_STATUS', $request->kitchen_status)->first();
            if (empty($kitchen_status_data)) {
                throw new Exception("Invalid kitchen status provided", 400);
            }

            DB::beginTransaction();

            $order = [];
            $order['kitchen_status'] = $kitchen_status_data->value;
            $order['updated_at'] = now();
            $order['updated_by'] = $request->logged_user_id;

            $action_response = OrderModel::where('slack', $request->order_slack)
                ->update($order);

            $order = OrderModel::select('orders.*')->where('orders.slack', $request->order_slack)
                ->first();

            $order_items = OrderProductModel::select('id', 'is_ready_to_serve')->where('order_products.order_id', $order->id)
                ->get();

            if ($request->kitchen_status == 'ORDER_READY') {

                $ready_to_serve_array = $order_items->map(function ($item, $key) use ($request) {

                    $order_product = [];
                    $order_product['is_ready_to_serve'] = 1;
                    $order_product['updated_at'] = now();
                    $order_product['updated_by'] = $request->logged_user_id;

                    $action_response = OrderProductModel::where('id', $item->id)
                        ->update($order_product);

                    return $action_response;
                })->toArray();
            }

            $order_data = new OrderResource($order);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Kitchen order status changed successfully",
                    "data" => ['order_data' => $order_data],
                ),
                'SUCCESS'
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

    public function get_register_order_amount(Request $request)
    {
        try {
            // if (!check_access(['A_ADD_ORDER'], true) || !check_access(['A_EDIT_ORDER'], true)) {
            //     throw new Exception("Invalid request", 400);
            // }

            $total_amount = 0.00;
            $total_net_cash_amount = 0.00;
            $total_net_credit_amount = 0.00;
            $cash_payment_method_id = 4;

            $business_register_data = BusinessRegisterModel::select('id', 'slack', 'opening_amount')
                ->where('user_id', '=', trim($request->logged_user_id))
                ->whereNull('closing_date')
                ->first();
            if (empty($business_register_data)) {
                throw new Exception("You dont have any register open", 400);
            }

            $payment_methods = PaymentMethodModel::active()->get();
        
            if (isset($payment_methods)) {
                foreach ($payment_methods as $payment_method) {

                    if($payment_method->payment_constant != 'CASH'){
                        
                        $payment_order_amount = OrderModel::withoutGlobalScopes()
                            ->whereIn('orders.status',[1,6])
                            ->where('orders.payment_method_id', $payment_method->id)
                            ->where('register_id', $business_register_data->id)
                            ->select(
                                DB::Raw('IFNULL (SUM(orders.credit_amount), 0) as amount')
                            )
                            ->first();
                        $payment_return_order_amount = ReturnOrdersModel::withoutGlobalScopes()
                            ->where('order_return.payment_method_id', $payment_method->id)
                            ->where('register_id', $business_register_data->id)
                            ->select(
                                DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount')
                            )
                            ->first();
                            $total_net_credit_amount +=  $payment_order_amount->amount - $payment_return_order_amount->amount;

                    }elseif($payment_method->payment_constant == 'CASH'){
                        $cash_payment_method_id = $payment_method->id;
                    }
                }
            }

            $cash_order_amount = OrderModel::withoutGlobalScopes()
                ->select(
                    DB::Raw('IFNULL (SUM(orders.cash_amount), 0) as amount'),
                    DB::Raw('IFNULL (SUM(orders.change_amount), 0) as change_amount')
                )
                ->whereIn('status', [1,6])
                ->where('register_id', $business_register_data->id)
                ->first();
            $cash_order_return_amount = ReturnOrdersModel::withoutGlobalScopes()
                ->select(
                    DB::Raw('IFNULL (SUM(order_return.total_order_amount), 0) as amount'),
                )
                ->where('order_return.payment_method_id', $cash_payment_method_id)
                ->where('register_id', $business_register_data->id)
                ->first();
            $total_net_cash_amount = $cash_order_amount['amount'] - $cash_order_amount['change_amount'] - $cash_order_return_amount['amount'];

            $total_net_cash_amount = !empty($total_net_cash_amount) ? $total_net_cash_amount : 0.00;
            $total_net_credit_amount = !empty($total_net_credit_amount) ? $total_net_credit_amount : 0.00;
            $total_amount = $total_net_cash_amount + $total_net_credit_amount;
            
            $total_amount = !empty($total_amount) ? $total_amount : 0.00;
            // $total_closing_amount = $business_register_data->opening_amount + $total_amount->order_amount;
            $total_net_cash_amount = $business_register_data->opening_amount + $total_net_cash_amount;

            if (!check_access(['A_AUTOFILL_CLOSE_REGISTER'], true)) {
                // $total_closing_amount = 0.00;
                $total_net_cash_amount = 0.00;
                $total_net_credit_amount = 0.00;
            }


            // $data = OrderModel::closed()
            //     ->where('register_id', '=', $business_register_data->id)
            //     ->sum('total_order_amount');

            // $data['closing_amount'] = format_decimal($total_closing_amount);
            $data['cash_amount'] = format_decimal($total_net_cash_amount);
            $data['credit_amount'] = format_decimal($total_net_credit_amount);

            // $data = [];

            // $data['total_amount'] = OrderModel::closed()
            //     ->where('register_id', '=', $business_register_data->id)
            //     ->sum('total_order_amount');

            // $data['total_cash_amount'] = OrderModel::closed()
            //     ->where('register_id', '=', $business_register_data->id)
            //     ->sum('cash_amount');

            // $data['total_credit_amount'] = OrderModel::closed()
            //     ->where('register_id', '=', $business_register_data->id)
            //     ->sum('credit_amount');

            return response()->json($this->generate_response(
                array(
                    "message" => "Register order value calculated successfully",
                    "data" => $data,
                ),
                'SUCCESS'
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

    public function share_invoice_sms(Request $request, $slack)
    {
        try {
            if (!check_access(['A_SHARE_INVOICE_SMS'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $notification_api = new NotificationAPI();
            $response = $notification_api->send_sms('POS_SALE_BILL_MESSAGE', $slack);

            $response_decoded = json_decode(json_encode($response), true);
            if ($response_decoded['original']['status_code'] != 200) {
                if ($response_decoded['original']['status_code'] == 400) {
                    throw new Exception($response_decoded['original']['msg'], 400);
                } else {
                    throw new Exception('Twilio response: ' . $response_decoded['original']['msg'] . " (status code: " . $response_decoded['original']['status_code'] . ")", 400);
                }
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoice has been sent via SMS successfully!",
                    "data" => $slack,
                ),
                'SUCCESS'
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


    public function get_waiter_order_list(Request $request)
    {
        try {
            if (!check_access(['MM_RESTAURANT'], true) || !check_access(['SM_RESTAURANT_WAITER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $waiter_id = $request->logged_user_id;

            $waiter_order_list = OrderModel::select('*')
                ->inkitchen()
                ->where('waiter_id', '=', $waiter_id)
                ->orderBy('created_at', 'desc')
                ->whereDate('created_at', '>', Carbon::now()->subDays(2))
                ->get();

            $waiter_orders = OrderResource::collection($waiter_order_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Waiter orders loaded successfully",
                    "data" => $waiter_orders,
                ),
                'SUCCESS'
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

    public function update_kitchen_item_status(Request $request)
    {
        try {
            if (!check_access(['A_CHANGE_KITCHEN_ORDER_STATUS'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $validator = Validator::make($request->all(), [
                'item_slack' => $this->get_validation_rules("slack", true)
            ]);

            $validation_status = $validator->fails();
            if ($validation_status) {
                throw new Exception($validator->errors());
            }

            $item_slack = $request->item_slack;

            DB::beginTransaction();

            $order_item = OrderProductModel::select('order_products.order_id', 'order_products.is_ready_to_serve')->where('order_products.slack', $item_slack)
                ->first();

            $order_data = OrderModel::select('orders.id')->where('orders.id', $order_item->order_id)
                ->first();

            $order_product = [];
            $order_product['is_ready_to_serve'] = ($order_item->is_ready_to_serve == 0) ? 1 : 0;
            $order_product['updated_at'] = now();
            $order_product['updated_by'] = $request->logged_user_id;

            $action_response = OrderProductModel::where('slack', $item_slack)
                ->update($order_product);

            $order_items = OrderProductModel::select('order_products.is_ready_to_serve')->where('order_products.order_id', $order_data->id)
                ->get();

            $ready_to_serve_array = $order_items->map(function ($item, $key) {
                return $item['is_ready_to_serve'];
            })->toArray();
            $not_ready_to_serve_exists = in_array(0, $ready_to_serve_array) ? true : false;

            if ($not_ready_to_serve_exists == true) {
                $kitchen_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_KITCHEN_STATUS', 'STARTED_PREPARING')->first();
                if (!empty($kitchen_status_data)) {
                    $order = [];
                    $order['kitchen_status'] = $kitchen_status_data->value;
                    $order['updated_at'] = now();
                    $order['updated_by'] = $request->logged_user_id;

                    $action_response = OrderModel::where('id', $order_data->id)
                        ->update($order);
                }
            } else {
                $kitchen_status_data = MasterStatusModel::select('value')->filterByValueConstant('ORDER_KITCHEN_STATUS', 'ORDER_READY')->first();
                if (!empty($kitchen_status_data)) {
                    $order = [];
                    $order['kitchen_status'] = $kitchen_status_data->value;
                    $order['updated_at'] = now();
                    $order['updated_by'] = $request->logged_user_id;

                    $action_response = OrderModel::where('id', $order_data->id)
                        ->update($order);
                }
            }

            $order = OrderModel::select('orders.*')->where('orders.id', $order_data->id)
                ->first();

            $order_data = new OrderResource($order);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Kitchen item status changed successfully",
                    "data" => ['order_data' => $order_data],
                ),
                'SUCCESS'
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

    public function cancel_order(Request $request)
    {

        try {
            $slack = $request->cancel_order_slack;

            $order_detail = OrderModel::select('id', 'store_id', 'order_number')->where('slack', $slack)->first();
            if (empty($order_detail)) {
                throw new Exception("Invalid order provided", 400);
            }
            $order_id = $order_detail->id;
            $store_id = $order_detail->store_id;
            $order_number = $order_detail->order_number;

            $order_products = OrderProductModel::where('order_id', $order_id)->get()->toArray();

            DB::beginTransaction();
            array_walk($order_products, function (&$item, $key) use ($request) {
                $product = ProductModel::find($item['product_id']);
                if ($product['quantity'] != -1) {
                    $product->increment('quantity', $item['quantity']);
                }

                $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity'], 'increment');
            });

            TransactionModel::where([
                ['bill_to', '=', 'POS_ORDER'],
                ['bill_to_id', '=', $order_id],
            ])->delete();
            OrderProductModel::where('order_id', $order_id)->delete();
            OrderModel::where('id', $order_id)->delete();



            // $store_details = StoreModel::select('store_order_number')->where('id', $request->logged_user_store_id)->where('status', 1)->orderBy('id', 'desc')->first();
            // if ($order_number == $store_details->store_order_number) {
            //     $store = StoreModel::find($store_id);
            //     $store->decrement('store_order_number', 1);
            // }

            DB::commit();

            /*-------------------------------*/
            /* UPDATE ZID's PRODUCT QUANTITY*/
            /*-------------------------------*/

            if (isset($order_products)) {

                foreach ($order_products as $order_product) {

                    $product = ProductModel::where('slack', $order_product['product_slack'])->first();

                    if ($product->zid_product_id != "") {

                        $this->zid_update_product_quantity($product->quantity, $product->zid_product_id);
                    }
                }
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "Order cancel successfully",
                    "data" => $slack,
                ),
                'SUCCESS'
            ));
        } catch (Exception $e) {
            DB::rollback();

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function get_order_receipt(Request $request)
    {
        try {
            $transaction_date = $request->transaction_date;
            $transaction_id = $request->transaction_id;

            $order = OrderModel::select('*')
                ->where('value_date', '=', $transaction_date)
                ->where('reference_number', '=', $transaction_id)
                ->first();
            $item_data = new OrderResource($order);

            if (isset($order)) {
                $forward_link = route('order_receipt', ['slack' => $order['slack']]);

                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                $print_logo_path = session('store_logo');

                if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path))) {
                    $print_logo_path = public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
                    //$print_logo_path = asset('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
                } else {
                    $print_logo_path = public_path('images/logo.png');
                }

                $order_data = app('App\Http\Controllers\Order')->get_order_data($order['slack']);

                $print_data = view($view_file, ['data' => json_encode($order_data), 'logo_path' => $print_logo_path])->render();

                $mpdf_config = [
                    'mode'          => 'utf-8',
                    'format'        => $format,
                    'orientation'   => 'P',
                    'margin_left'   => 7,
                    'margin_right'  => 7,
                    'margin_top'    => 7,
                    'margin_bottom' => 7,
                    'tempDir' => storage_path() . "/pdf_temp"
                ];

                $stylesheet = File::get(public_path($css_file));
                $mpdf = new Mpdf($mpdf_config);
                $mpdf->curlAllowUnsafeSslRequests = true;
                $mpdf->SetDisplayMode('real');
                $mpdf->showImageErrors = true;
                $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
                $mpdf->WriteHTML($print_data);
                $filename = 'order_' . session('merchant_id') . "_" . $order_data['order_number'] . '.pdf';

                $view_path = Config::get('constants.upload.order.view_path');
                $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
                $mpdf->Output($upload_dir . $filename, 'F');

                $store = StoreModel::find($order->store_id);

                $qr_code = \Salla\ZATCA\GenerateQrCode::fromArray([
                    new \Salla\ZATCA\Tags\Seller(($store->tax_registration_name != '') ? $store->tax_registration_name : $store->name),
                    new \Salla\ZATCA\Tags\TaxNumber($store->vat_number),
                    new \Salla\ZATCA\Tags\InvoiceDate(Carbon::parse($order->created_at)->format('Y-m-d h:i')),
                    new \Salla\ZATCA\Tags\InvoiceTotalAmount($order->total_order_amount),
                    new \Salla\ZATCA\Tags\InvoiceTaxAmount($order->total_tax_amount)
                ])->toBase64();

                return response()->json($this->generate_response(
                    array(
                        "message" => "Order created successfully",
                        "data" => $order['slack'],
                        'order' => $item_data,
                        "link" => $forward_link,
                        "qr_code" => $qr_code,
                        "pdf_link" => 'order_' . session('merchant_id') . "_" . $order_data['order_number'] . '.pdf',
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => __('No order found.'),
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function return_order_list(Request $request)
    {
        
        try {
            $total_order_amount = 0.0;
            $return_products = array();
            $order_slack = $request->order_slack;
            $products = $request->products;
            $order_basic = $request->order_basic;
            $return_products = json_decode($products, true);
            $order_basic = json_decode($order_basic, true);
            DB::beginTransaction();
            
            // dd($return_products);
            $order_value_date_detail = OrderModel::select('value_date')->where('store_id', $request->logged_user_store_id)->where('status', 5)->orWhere('status', 1)->orderBy('id', 'desc')->first();
            if (isset($order_value_date_detail->value_date)) {

                $order_value_date = Carbon::parse($order_value_date_detail->value_date);
                $current_date = Carbon::parse(DATE(NOW()));

                if ($current_date->greaterThan($order_value_date)) {
                    $store = StoreModel::find($request->logged_user_store_id);
                    $store->increment('store_order_return_number', 1);
                } else {
                    $store_update_data['store_order_return_number'] = 1;
                    StoreModel::where('id', $request->logged_user_store_id)->update($store_update_data);
                }
            } else {
                $store_update_data['store_order_return_number'] = 1;
                StoreModel::where('id', $request->logged_user_store_id)->update($store_update_data);
            }


            $item = OrderModel::select('*')->where('slack', $order_slack)->first();
            $order_detail = $item;
            $return_order_check = ReturnOrdersModel::select('*')->where('order_slack', $order_slack)->first();

            $ordered_amount = !empty($item->cash_amount) ? $item->cash_amount : $item->credit_amount; 
            if($order_basic['status']['constant'] == 'HOLD'){
                if(!isset($request->payment_slack) || empty($request->payment_slack)){
                    $payment_method = [];
                    $payment_option = null;
                }else{
                    $payment_method = PaymentMethodModel::where('slack',$request->payment_slack)->first();
                    $payment_option = $payment_method->label == 'Cash' ? 1 : 2;
                }
            }else{
                $payment_method = PaymentMethodModel::where('slack',$request->payment_slack)->first();
                $payment_option = $payment_method->label == 'Cash' ? 1 : 2;
            }

            /*if (isset($return_order_check->id)) {
                throw new Exception("Order is already returned.", 400);
            }*/

            $business_register_data = BusinessRegisterModel::select('id', 'slack')
                    ->where('user_id', '=', trim($request->logged_user_id))
                    ->whereNull('closing_date')
                    ->first();
            if(!isset($business_register_data)){
                throw new Exception('Please open a counter to return your order');
            }
            $return_total_order_amount = number_format($order_basic['total_order_amount'], 2, '.', '');
            $return_order_slack = $order['slack'] = $this->generate_slack("order_return");
            $order['store_id'] = $item->store_id;
            $order['value_date'] = $this->get_order_value_date();
            $order['return_order_number'] = DB::table('stores')->where('id', $request->logged_user_store_id)->value('store_order_return_number');
            $order['order_slack'] = trim($order_slack);
            $order['reference_number'] = $item->reference_number;
            $order['order_number'] = $item->order_number;
            $order['customer_id'] = $item->customer_id;
            $order['customer_phone'] = $item->customer_phone;
            $order['customer_email'] = $item->customer_email;
            $order['register_id'] = $item->register_id;
            $order['returning_register_id'] = $business_register_data->id;
            $order['store_level_discount_code_id'] = $item->store_level_discount_code_id;
            $order['store_level_discount_code'] = $item->store_level_discount_code;
            $order['store_level_total_discount_percentage'] = $item->store_level_total_discount_percentage;
            $order['store_level_total_discount_amount'] = $item->store_level_total_discount_amount;
            $order['product_level_total_discount_amount'] = (string) $item->product_level_total_discount_amount;
            $order['store_level_tax_code_id'] = $item->store_level_tax_code_id;
            $order['store_level_tax_code'] = $item->store_level_tax_code;
            $order['store_level_total_tax_percentage'] = $item->store_level_total_tax_percentage;
            $order['store_level_total_tax_amount'] = $item->store_level_total_tax_amount;
            $order['store_level_total_tax_components'] = json_encode($order_basic['order_level_tax_components']);
            $order['product_level_total_tax_amount'] = $item->product_level_total_tax_amount;
            $order['purchase_amount_subtotal_excluding_tax'] = $item->purchase_amount_subtotal_excluding_tax;
            $order['sale_amount_subtotal_excluding_tax'] =  number_format($order_basic['sale_amount_subtotal_excluding_tax'], 2, '.', '');
            $order['total_discount_before_additional_discount'] = number_format($order_basic['total_discount_before_additional_discount'], 2, '.', '');
            $order['total_amount_before_additional_discount'] = number_format($order_basic['total_amount_before_additional_discount'], 2, '.', '');
            $order['additional_discount_percentage'] = empty($order_basic['additional_discount_percentage']) ? '' : 
                                                        number_format($order_basic['additional_discount_percentage'], 2, '.', '');
            $order['additional_discount_amount'] = empty($order_basic['additional_discount_amount']) ? '' : number_format($order_basic['additional_discount_amount'], 2, '.', '');
            $order['total_discount_amount'] = empty($order_basic['additional_discount_amount']) ? '' : number_format($order_basic['additional_discount_amount'], 2, '.', '');
            $order['total_after_discount'] = number_format($order_basic['total_after_discount'], 2, '.', '');
            $order['total_tax_amount'] = number_format($order_basic['total_tax_amount'], 2, '.', '');
            $order['total_order_amount'] = $return_total_order_amount;
            $order['total_order_amount_rounded'] =  round($order_basic['total_order_amount']);
            $order['payment_method_id'] = isset($payment_method->id) ? $payment_method->id : null;
            $order['payment_method_slack'] = isset($payment_method->slack) ? $payment_method->slack : null;
            $order['payment_method'] = isset($payment_method->label) ? $payment_method->label : null;
            $order['currency_name'] = $item->currency_name;
            $order['currency_code'] = $item->currency_code;
            $order['business_account_id'] = $item->business_account_id;
            $order['order_type_id'] = $item->order_type_id;
            $order['order_type'] = $item->order_type;
            $order['restaurant_mode'] = $item->restaurant_mode;
            $order['bill_type_id'] = $item->bill_type_id;
            $order['bill_type'] = $item->bill_type;
            $order['table_id'] = $item->table_id;
            $order['table_number'] = $item->table_number;
            $order['waiter_id'] = $item->waiter_id;
            $order['status'] = 1;
            $order['kitchen_status'] = $item->kitchen_status;
            $order['created_at'] = now();
            $order['created_by'] = $request->logged_user_id;
            $order['discount_type'] = $item->discount_type;
            $order['payment_option'] = $payment_option;
            $order['cash_amount'] = (isset($payment_method->label) && $payment_method->label == 'Cash')  ? $ordered_amount : 0.00;
            $order['change_amount'] = $item->change_amount;
            $order['card_name'] = (isset($payment_method->label) && $payment_method->label != 'Cash') ? $payment_method->label : NULL;
            $order['credit_amount'] = (isset($payment_method->label) && $payment_method->label != 'Cash') ? $ordered_amount : 0.00;
            $order['value_date'] = $item->value_date;
            $order['transaction_id'] = $item->transaction_id;
            $order['reason'] = $request->return_reason;

            $order['return_type'] = $request->return_type;

            $order_item = ReturnOrdersModel::create($order);
            $return_order_id = $order_item->id;

            if(empty($item->return_order_amount) || is_null($item->return_order_amount) ){
                $item->return_order_amount = 0;
            }
            $total_order_amount = $order['total_order_amount'] + $item->return_order_amount;

            $i = 0;
            $index = 0;
            $quantity_check = TRUE;
            $orderids = [];
            for ($index = 0; $index < count($return_products); $index++) {
                if ((float)$return_products[$index]['quantity'] == 0) {
                    array_push($orderids, $return_products[$index]['product_slack']);
                    unset($return_products[$index]);
                }
            }
            $order_product = OrderProductModel::where('order_id', $item['id'])->get();
            for ($index = 0; $index < count($order_product); $index++) {
                if (in_array($order_product[$i]->product_slack, $orderids)) {
                    unset($order_product[$i]);
                }
            }
            $return_order_count = 0;
            if (count($return_products) > 0) {

                // filter out returning combo items
                
                foreach ($return_products as $product) {
                    $returning_combos = [];
                    if(isset($request->returning_combos)){
                        $returning_combos = json_decode($request->returning_combos, true);
                        if($product['combo_cart_id'] != null && $product['combo_cart_id'] != '' && !in_array($product['combo_cart_id'],$returning_combos) ){
                            // avoid returning unselected combo item
                            continue;
                        }
                    }

                    if(!isset($product['is_gifted'])){
                        $product['is_gifted'] = 0;
                    }
                    if ($product['product_slack'] != '' && $product['quantity'] > 0 && $product['is_gifted'] == 0) {
                        
                        $order_product_quantity = OrderProductModel::where('slack', $product['slack'])->first(['quantity']);
                        $pre_returned_quantity = ReturnOrdersProductsModel::where('product_slack', $product['slack'])->sum('quantity');
                        $product_name = $product['name'];
                        
                        if($product['combo_cart_id'] != null && $product['combo_cart_id'] != '' && in_array($product['combo_cart_id'],$returning_combos) ){
                            
                            $actual_quantity = OrderProductModel::where('slack',$product['slack'])->pluck('quantity')->first();
                            $ret_qty = $actual_quantity;
                            $max_quantity = (float)$actual_quantity;
                        }else{
                            $ret_qty = $product['quantity'];
                            $max_quantity = (float)$order_product_quantity->quantity - (float)$pre_returned_quantity;
                        }

                        if($max_quantity <= 0){
                            throw new Exception("Item '$product_name' has returned all quantities!", 400);
                        }elseif($ret_qty > $max_quantity){
                            throw new Exception("Item '$product_name' has returned the quantity of $ret_qty is more than the ordered balance quantity of $max_quantity.", 400);
                        }
                        
                        $product_data = OrderProductModel::where('slack', $product['slack']);

                        $product_data = $product_data->first();

                        $product_data_temp = ProductModel::find($product_data->product_id);

                        if (isset($product['is_wastage']) && $product['is_wastage'] == true) {
                            // no need to increase quantity
                        } else {
                            if ($product_data->quantity != -1.00 && $product_data_temp->quantity != -1.00  && $request->return_type == 'Return') {
                                $product_data_temp->increment('quantity', $product['quantity']);
                                $this->update_ingredient_quantity($request, $product_data->product_id, $product['quantity'],'increment');
                                if($product['total_modifier_amount'] > 0){

                                    $order_product_modifier_options = OrderProductModifierOptionModel::select('order_product_modifier_options.modifier_option_id as id')
                                        ->where('order_product_modifier_options.order_product_id',$product_data->id)->get();
                                    $product_modifier_options_arr = [];
                                    if(isset($order_product_modifier_options) && count($order_product_modifier_options) > 0){
                                        foreach($order_product_modifier_options as $product_modifier_option){
                                            $product_modifier_options_arr[] = [
                                                "id" =>  $product_modifier_option->id,
                                            ];
                                        }
                                        $product['modifiers'] = $product_modifier_options_arr;
                                    }
                                    $this->__update_modifier_single_ingredient_stock($product);
                                }
                            }
                        }
                       
                        $return_product_item = [];
                        
                        $return_product_item['slack'] = $this->generate_slack("order_return_product");
                        $return_product_item['return_order_id'] = $return_order_id;
                        $return_product_item['order_id'] = $item->id;
                        $return_product_item['product_id'] = $product_data->product_id;
                        $return_product_item['product_slack'] = $product_data->slack;
                        $return_product_item['product_code'] = $product['product_code'];
                        $return_product_item['name'] = $product['name'];
                        $return_product_item['order_slack'] = trim($order_slack);
                        $return_product_item['quantity'] = $product['quantity'];
                        $return_product_item['purchase_amount_excluding_tax'] = isset($product_data->purchase_amount_excluding_tax) ? $product_data->purchase_amount_excluding_tax : NULL;
                        $return_product_item['sale_amount_excluding_tax'] = isset($product_data->sale_amount_excluding_tax) ? $product_data->sale_amount_excluding_tax : NULL;
                        $return_product_item['discount_code_id'] = isset($product_data->discount_code_id) ? $product_data->discount_code_id : NULL;
                        $return_product_item['discount_code'] = isset($product['discount_code']) ? $product['discount_code'] : NULL;
                        $return_product_item['discount_percentage'] = isset($product['discount_percentage']) ? $product['discount_percentage'] : NULL;
                        $return_product_item['tax_code_id'] = isset($product_data->tax_code_id) ? $product_data->tax_code_id : NULL;
                        $return_product_item['tax_code'] = isset($product['tax_code']) ? $product['tax_code'] : NULL;
                        $return_product_item['tax_percentage'] = isset($product['tax_percentage']) ? $product['tax_percentage'] : NULL;
                        $return_product_item['tax_components'] = isset($product['tax_components']) ? json_encode($product['tax_components']) : NULL;
                        $return_product_item['tobacco_tax_components'] = isset($product['tobacco_tax_components']) ? json_encode($product['tobacco_tax_components']) : NULL;
                        $return_product_item['sub_total_purchase_price_excluding_tax'] = isset($product['sub_total']) ? $product['sub_total'] : NULL;
                        $return_product_item['discount_amount'] = isset($product['discount_amount_calc']) ? $product['discount_amount_calc'] : 0;
                        $return_product_item['total_after_discount'] = isset($product['total_after_discount']) ? $product['total_after_discount'] : NULL;
                        $return_product_item['tax_amount'] = isset($product['tax_amount']) ? $product['tax_amount'] : NULL;
                        $return_product_item['total_amount'] = isset($product['total_amount']) ? $product['total_amount'] : NULL;
                        $return_product_item['is_ready_to_serve'] = isset($product['is_ready_to_serve']) ? $product['is_ready_to_serve'] : NULL;
                        $return_product_item['status'] = 1;
                        $return_product_item['order_product_modifier_options'] = isset($product['modifier_options']) ? json_encode($product['modifier_options']) : "";
                        $return_product_item['created_by'] = $request->logged_user_id;
                        $return_product_item['created_at'] = now();
                        $return_product_item['reason'] = $request->return_reason;
                        $return_product_item['time'] = (new \DateTime(Carbon::now()))->format("F d, h:i:s a");
                        $store = StoreModel::find($request->logged_user_store_id);
                        if(isset($store->name))
                        {
                            $return_product_item['branch'] = $store->name;
                        }
                        else
                        {
                          $return_product_item['branch'] = "";
                        }
                        $return_product_item['order_reference'] = isset($item->order_number)?$item->order_number:0;
                        $return_product_item['branch_reference'] = isset($store->store_code)?$store->store_code:"";
                        $return_product_item['is_wastage'] = (isset($product['is_wastage']) && $product['is_wastage'] == true) ? true : false;
                        $return_product_item['return_type'] = $request->return_type;
                        $return_product_item['combo_id'] = $product['combo_id'];
                        $return_product_item['combo_cart_id'] = $product['combo_cart_id'];
                        
                        $order_id = ReturnOrdersProductsModel::create($return_product_item)->id;
                        $i++;

                        // Add quantity history
                        $this->addQuantityHistory($this->generate_slack('quantity_history'),$return_product_item['product_id'],$request->logged_user_store_id,($request->return_type == 'Damage') ? 'ORDER_DAMAGE' : 'ORDER_RETURN',($request->return_type == 'Damage') ? 'DECREMENT' : 'INCREMENT',$return_product_item['quantity'],$order_id);

                    }
                    if($product['is_gifted'] == 1){
                        $return_order_count += $product['quantity'];
                    }
                }
            }
            
            $return_orders = ReturnOrdersProductsModel::where('order_id', $item['id'])->get()->toArray();
            $order_products = OrderProductModel::where('order_id', $item['id'])->get()->toArray();
            $order_product_count = 0;
            foreach ($return_orders as $return_order) {
                $return_order_count += $return_order['quantity'];
            }
            foreach ($order_products as $order_product) {
                $order_product_count += $order_product['quantity'];
            }
            if ($return_order_count >= $order_product_count) {

                $update_order_data = [
                    "status" => 6,
                    "return_order_amount" => $total_order_amount
                ];
            } else {
                $update_order_data = [
                    "status" => 1,
                    "return_order_amount" => $total_order_amount
                ];
            }

            OrderModel::where('slack', $order_slack)->update($update_order_data);

            // if (isset($order_basic['transactions']) && !empty($order_basic['transactions'])) {
            //     $order_basic_transactions = $order_basic['transactions'];
            //     foreach ($order_basic_transactions as $order_basic_transaction) {
            //         $transaction_slack = $order_basic_transaction['slack'];
            //         $transaction_amount = $order['total_order_amount'];
            //         $transaction_data = TransactionModel::where('slack', $transaction_slack)->first();
            //         if ($return_order_count == $order_product_count) {
            //             $transaction_data->amount = 0.00;
            //         } else {
            //             $transaction_data->amount = abs(round((float)$transaction_data->amount, 2) - round((float)$transaction_amount, 2));
            //         }
            //         $transaction_data->save();
            //     }
            // }

            // Add a transaction entry for return with minus amount for calculatig the total existing order amount for reports.
            $customer_data = CustomerModel::select('id', 'name', 'email', 'phone', 'address')
                ->where('id', '=', $order_detail->customer_id)->first();
            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', 'INCOME')->first();
            $transaction = [
                "slack" => $this->generate_slack("transactions"),
                "store_id" => $order_detail->store_id,
                "transaction_code" => Str::random(6),
                "account_id" => $order_detail->business_account_id,
                "transaction_type" => $transaction_type_data->id,
                "payment_method_id" => $order_detail->payment_method_id,
                "payment_method" => $order_detail->payment_method,
                "bill_to" => 'POS_ORDER',
                "bill_to_id" => $order_detail->id,
                "bill_to_name" => (isset($customer_data->name)) ? $customer_data->name : 'Walkin Customer',
                "bill_to_contact" => $order_detail->customer_phone,
                "bill_to_address" => (isset($customer_data->address)) ? $customer_data->address : '',
                "currency_code" => $order_detail->currency_code,
                "amount" => (-$return_total_order_amount),
                "pg_transaction_id" => '',
                "pg_transaction_status" => '',
                "notes" => '',
                "transaction_date" => date('Y-m-d'),
                "created_by" => request()->logged_user_id,
                "return_bill_to_id" => $return_order_id,
            ];
            // dd($paid_amount);
            $transaction_id = TransactionModel::create($transaction)->id;
    
            $code_start_config = Config::get('constants.unique_code_start.transaction');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;
    
            $transaction_code = [
                "transaction_code" => ($code_start + $transaction_id)
            ];
            TransactionModel::where('id', $transaction_id)->update($transaction_code);

            //Qoyod
            if(Session('qoyod_status')){
                $order_return_detail = ReturnOrders::where('id',$return_order_id)->first();
                $this->qoyod_create_credit_note($order_return_detail,'Order');
            }

            DB::commit();
            $returnorder = new ReturnOrderResource($order_item);
            $item_data = ReturnOrdersProductsModel::where('return_order_id', $return_order_id)->get();
            $forward_link = route('return_order_receipt', ['slack' => $order['slack']]);
            $this->record_return_hrm_transaction($return_order_slack, $forward_link);
            if (isset($order)) {
                $returnorder['products'] = $item_data;
                return response()->json($this->generate_response(
                    array(
                        "message" => "Return Order created successfully",
                        "data" => $order['slack'],
                        "link" => $forward_link,
                        "order" => $returnorder
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => "No order found.",
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {

            DB::rollback();
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function return_order(Request $request){
        
        try {
            $logged_user_id = $request->logged_user_id;
            $logged_user_store_id = $request->logged_user_store_id;
            $order_slack = $request->order_slack;

            
            $return_products = json_decode($request->products, true);
            $payment_slack = $request->payment_slack;
            $return_reason = $request->return_reason??'Customer Cancelled';
            $return_type = $request->return_type!=null?$request->return_type:'Return';
            $store = StoreModel::find($logged_user_store_id)->first();
            $branch = isset($store->name)?$store->name:'';

            DB::beginTransaction();

            //Get the date data
            $order_value_date_detail = OrderModel::select('value_date')->where('store_id', $logged_user_store_id)->where('status', 5)->orWhere('status', 1)->orderBy('id', 'desc')->first();

            //update the date related data
            if (isset($order_value_date_detail->value_date)) {

                $order_value_date = Carbon::parse($order_value_date_detail->value_date);
                $current_date = Carbon::parse(DATE(NOW()));

                if ($current_date->greaterThan($order_value_date)) {
                    $store = StoreModel::find($logged_user_store_id);
                    $store->increment('store_order_return_number', 1);
                } else {
                    $store_update_data['store_order_return_number'] = 1;
                    StoreModel::where('id', $logged_user_store_id)->update($store_update_data);
                }
            } else {
                $store_update_data['store_order_return_number'] = 1;
                StoreModel::where('id', $logged_user_store_id)->update($store_update_data);
            }

            //Order data
            $order = OrderModel::select('*')->where('slack', $order_slack)->first();
            $order_detail = $order;
            $list = new OrderListByDateResource($order);

            // Combo Return : Start
            $return_combo_products = [];
            if(isset($request->combos)){
                $combos = json_decode($request->combos,true);
                foreach($combos as $combo){
                    $combo_products = OrderProductModel::select('slack','quantity')->where('order_id',$order->id)->where('combo_cart_id',$combo['combo_cart_id'])->get();
                    if(isset($combo_products)){
                        foreach($combo_products as $combo_product){
                            $dataset = [];
                            $dataset['quantity'] = $combo_product->quantity;
                            $dataset['cart_slack'] = $combo_product->slack;
                            $dataset['return_type'] = $combo['return_type'];
                            $return_combo_products[] = $dataset;
                        }
                    }
                }
            }
            if(isset($return_products) && !is_null($return_products) ){
                $return_products = array_merge($return_products,$return_combo_products);
            }
            // Combo Return : End

            $whole_order = json_encode($list);
            $decode_order = json_decode($whole_order);
            $transactions = $decode_order->transactions;
            if($order->status == 2){
                $return_products = [];
                $order_carts = OrderProductModel::where('order_id', $order->id)->get();
                foreach ($order_carts as $cart){
                    $return_products[] = array(
                        'id'=> $cart->id,
                        'quantity'=> $cart->quantity,
                        'cart_slack'=> $cart->slack,
                        'total_modifier_amount'=>  $cart->total_modifier_amount,
                        'return_type'=> 'Return',
                    );
                }
            }

            $ordered_amount = !empty($order->cash_amount) ? $order->cash_amount : $order->credit_amount;
            
            //Payment_method Data
            $payment_method = [];
            if($order->status == 2){  // Hold status
                if(!isset($request->payment_slack) || empty($request->payment_slack)){
                    $payment_option = null;
                }else{
                    $payment_method = PaymentMethodModel::where('slack',$request->payment_slack)->first();
                    if(isset($payment_method)){
                        $payment_option = $payment_method->label == 'Cash' ? 1 : 2;
                    }else{
                        $payment_option = null;
                    }
                }
            }else{
                $payment_method = PaymentMethodModel::where('slack',$request->payment_slack)->first();
                $payment_option = $payment_method->label == 'Cash' ? 1 : 2;
            }

            $order_data = $order->toArray();
            // dd($request->combos);
            
            // /////////////////// Return Calculations  /////////////////////
            
            $return_data = $this->returnOrderCalculations($order_data, $return_products );

            $return_order_arr = $return_data['bill_wise_details'];
            $products_arr_calc = $return_data['products_arr_calc'];

            $business_register_data = BusinessRegisterModel::select('id', 'slack')
                    ->where('user_id', '=', trim($request->logged_user_id))
                    ->whereNull('closing_date')
                    ->first();
            if(!isset($business_register_data)){
                throw new Exception('Please open a counter to return your order');
            }
            // dd($products_arr_calc, $return_order_arr);
            //Generate Return Order Slack
            $return_total_order_amount = number_format($return_order_arr['total_order_amount'], 2, '.', '');
            $return_order_slack = $return_order_add['slack'] = $this->generate_slack("order_return");
            $return_order_add['store_id'] = $order->store_id;
            $return_order_add['value_date'] = $this->get_order_value_date();
            $return_order_add['return_order_number'] = DB::table('stores')->where('id', $request->logged_user_store_id)->value('store_order_return_number');
            $return_order_add['order_slack'] = trim($order_slack);
            $return_order_add['reference_number'] = $order->reference_number;
            $return_order_add['order_number'] = $order->order_number;
            $return_order_add['customer_id'] = $order->customer_id;
            $return_order_add['customer_phone'] = $order->customer_phone;
            $return_order_add['customer_email'] = $order->customer_email;
            $return_order_add['register_id'] = $order->register_id;
            $return_order_add['returning_register_id'] = $business_register_data->id;
            $return_order_add['store_level_discount_code_id'] = $order->store_level_discount_code_id;
            $return_order_add['store_level_discount_code'] = $order->store_level_discount_code;
            $return_order_add['store_level_total_discount_percentage'] = $order->store_level_total_discount_percentage;
            $return_order_add['store_level_total_discount_amount'] = $order->store_level_total_discount_amount;
            $return_order_add['product_level_total_discount_amount'] = (string) $order->product_level_total_discount_amount;
            $return_order_add['store_level_tax_code_id'] = $order->store_level_tax_code_id;
            $return_order_add['store_level_tax_code'] = $order->store_level_tax_code;
            $return_order_add['store_level_total_tax_percentage'] = $order->store_level_total_tax_percentage;
            $return_order_add['store_level_total_tax_amount'] = $order->store_level_total_tax_amount;
            $return_order_add['store_level_total_tax_components'] = json_encode($return_order_arr['order_level_tax_components']);
            $return_order_add['product_level_total_tax_amount'] = $order->product_level_total_tax_amount;
            $return_order_add['purchase_amount_subtotal_excluding_tax'] = $order->purchase_amount_subtotal_excluding_tax;
            $return_order_add['sale_amount_subtotal_excluding_tax'] =  number_format($return_order_arr['sale_amount_subtotal_excluding_tax'], 2, '.', '');
            $return_order_add['total_discount_before_additional_discount'] = number_format($return_order_arr['total_discount_before_additional_discount'], 2, '.', '');
            $return_order_add['total_amount_before_additional_discount'] = number_format($return_order_arr['total_amount_before_additional_discount'], 2, '.', '');
            $order['additional_discount_percentage'] = empty($return_order_arr['additional_discount_percentage']) ? '' : 
                                                        number_format($return_order_arr['additional_discount_percentage'], 2, '.', '');
            $return_order_add['additional_discount_amount'] = empty($return_order_arr['additional_discount_amount']) ? '' : number_format($return_order_arr['additional_discount_amount'], 2, '.', '');
            $return_order_add['total_discount_amount'] = empty($return_order_arr['additional_discount_amount']) ? '' : number_format($return_order_arr['additional_discount_amount'], 2, '.', '');
            $return_order_add['total_after_discount'] = number_format($return_order_arr['total_after_discount'], 2, '.', '');
            $return_order_add['total_tax_amount'] = number_format($return_order_arr['total_tax_amount'], 2, '.', '');
            $return_order_add['total_order_amount'] = $return_total_order_amount;
            $return_order_add['total_order_amount_rounded'] =  round($return_order_arr['total_order_amount']);
            $return_order_add['payment_method_id'] = isset($payment_method->id) ? $payment_method->id : null;
            $return_order_add['payment_method_slack'] = isset($payment_method->slack) ? $payment_method->slack : null;
            $return_order_add['payment_method'] = isset($payment_method->label) ? $payment_method->label : null;
            $return_order_add['currency_name'] = $order->currency_name;
            $return_order_add['currency_code'] = $order->currency_code;
            $return_order_add['business_account_id'] = $order->business_account_id;
            $return_order_add['order_type_id'] = $order->order_type_id;
            $return_order_add['order_type'] = $order->order_type;
            $return_order_add['restaurant_mode'] = $order->restaurant_mode;
            $return_order_add['bill_type_id'] = $order->bill_type_id;
            $return_order_add['bill_type'] = $order->bill_type;
            $return_order_add['table_id'] = $order->table_id;
            $return_order_add['table_number'] = $order->table_number;
            $return_order_add['waiter_id'] = $order->waiter_id;
            $return_order_add['status'] = 1;
            $return_order_add['kitchen_status'] = $order->kitchen_status;
            $return_order_add['created_at'] = now();
            $return_order_add['created_by'] = $request->logged_user_id;
            $return_order_add['discount_type'] = $order->discount_type;
            $return_order_add['payment_option'] = $payment_option;
            $return_order_add['cash_amount'] = (isset($payment_method->label) && $payment_method->label == 'Cash')  ? $ordered_amount : 0.00;
            $return_order_add['change_amount'] = $order->change_amount;
            $return_order_add['card_name'] = (isset($payment_method->label) && $payment_method->label != 'Cash') ? $payment_method->label : NULL;
            $return_order_add['credit_amount'] = (isset($payment_method->label) && $payment_method->label != 'Cash') ? $ordered_amount : 0.00;
            $return_order_add['value_date'] = $order->value_date;
            $return_order_add['transaction_id'] = $order->transaction_id;
            $return_order_add['reason'] = $return_reason;
            $return_order_add['return_type'] = $return_type;

            // dd($return_order_add);
            $return_order_entry = ReturnOrdersModel::create($return_order_add);
            $return_order_id = $return_order_entry->id;
            if(empty($order->return_order_amount) || is_null($order->return_order_amount) ){
                $order->return_order_amount = 0;
            }
            $total_order_amount = $return_order_add['total_order_amount'] + $order->return_order_amount;
            $return_order_count = 0;

            if (count($products_arr_calc) > 0) {
                foreach ($products_arr_calc as $product) {

                    if(!isset($product['is_gifted'])){
                        $product['is_gifted'] = 0;
                    }
                    if ($product['quantity'] > 0 && $product['is_gifted'] == 0) {

                        // $product_data = OrderProductModel::where('slack', $product['slack']);
                        // $product_data = $product_data->first();

                        $product_data_temp = ProductModel::find($product['product_id']);

                        if (isset($product['is_wastage']) && $product['is_wastage'] == true) {
                            // no need to increase quantity
                        } else {
                            if ($product_data_temp->quantity != -1.00  && $return_type == 'Return') {

                                $product_data_temp->increment('quantity', $product['quantity']);
                                $this->update_ingredient_quantity($request, $product['product_id'], $product['quantity'],'increment');
                                if($product['total_modifier_amount'] > 0){

                                    $order_product_modifier_options = OrderProductModifierOptionModel::select('order_product_modifier_options.modifier_option_id as id')
                                        ->where('order_product_modifier_options.order_product_id',$product['order_product_id'])->get();
                                    $product_modifier_options_arr = [];
                                    if(isset($order_product_modifier_options) && count($order_product_modifier_options) > 0){
                                        foreach($order_product_modifier_options as $product_modifier_option){
                                            $product_modifier_options_arr[] = [
                                                "id" =>  $product_modifier_option->id,
                                            ];
                                        }
                                        $product['modifiers'] = $product_modifier_options_arr;
                                    }
                                    $this->__update_modifier_single_ingredient_stock($product);
                                }
                            }
                        }
                        $return_product_item = [];

                        $return_product_item['slack'] = $this->generate_slack("order_return_product");
                        $return_product_item['return_order_id'] = $return_order_id;
                        $return_product_item['order_id'] = $order->id;
                        $return_product_item['product_id'] = $product['product_id'];
                        $return_product_item['product_slack'] = $product['slack'];
                        $return_product_item['product_code'] = $product['product_code'];
                        $return_product_item['name'] = $product['name'];
                        $return_product_item['order_slack'] = trim($order_slack);
                        $return_product_item['quantity'] = $product['quantity'];
                        $return_product_item['purchase_amount_excluding_tax'] = isset($product['purchase_amount_excluding_tax']) ? $product['purchase_amount_excluding_tax'] : NULL;
                        $return_product_item['sale_amount_excluding_tax'] = isset($product['sale_amount_excluding_tax']) ? $product['sale_amount_excluding_tax'] : NULL;
                        $return_product_item['discount_code_id'] = isset($product['discount_code_id']) ? $product['discount_code_id'] : NULL;
                        $return_product_item['discount_code'] = isset($product['discount_code']) ? $product['discount_code'] : NULL;
                        $return_product_item['discount_percentage'] = isset($product['discount_percentage']) ? $product['discount_percentage'] : NULL;
                        $return_product_item['tax_code_id'] = isset($product['tax_code_id']) ? $product['tax_code_id'] : NULL;
                        $return_product_item['tax_code'] = isset($product['tax_code']) ? $product['tax_code'] : NULL;
                        $return_product_item['tax_percentage'] = isset($product['tax_percentage']) ? $product['tax_percentage'] : NULL;
                        $return_product_item['tax_components'] = isset($product['tax_components']) ? json_encode($product['tax_components']) : NULL;
                        $return_product_item['tobacco_tax_components'] = isset($product['tobacco_tax_components']) ? json_encode($product['tobacco_tax_components']) : NULL;
                        $return_product_item['sub_total_purchase_price_excluding_tax'] = isset($product['sub_total']) ? $product['sub_total'] : NULL;
                        $return_product_item['discount_amount'] = isset($product['discount_amount_calc']) ? $product['discount_amount_calc'] : 0;
                        $return_product_item['total_after_discount'] = isset($product['total_after_discount']) ? $product['total_after_discount'] : NULL;
                        $return_product_item['tax_amount'] = isset($product['tax_amount']) ? $product['tax_amount'] : NULL;
                        $return_product_item['total_amount'] = isset($product['total_amount']) ? $product['total_amount'] : NULL;
                        $return_product_item['is_ready_to_serve'] = isset($product['is_ready_to_serve']) ? $product['is_ready_to_serve'] : NULL;
                        $return_product_item['status'] = 1;
                        $return_product_item['order_product_modifier_options'] = isset($product['modifier_options']) ? $product['modifier_options'] : "";
                        $return_product_item['created_by'] = $request->logged_user_id;
                        $return_product_item['created_at'] = now();
                        $return_product_item['reason'] = $return_reason;
                        $return_product_item['time'] = (new \DateTime(Carbon::now()))->format("F d, h:i:s a");
                        $store = StoreModel::find($request->logged_user_store_id);
                        if(isset($store->name))
                        {
                            $return_product_item['branch'] = $store->name;
                        }
                        else
                        {
                          $return_product_item['branch'] = "";
                        }
                        $return_product_item['order_reference'] = isset($order->order_number)?$order->order_number:0;
                        $return_product_item['branch_reference'] = isset($store->store_code)?$store->store_code:"";
                        $return_product_item['is_wastage'] = (isset($product['is_wastage']) && $product['is_wastage'] == true) ? true : false;
                        $return_product_item['return_type'] = $return_type;
                        // dd($return_product_item);
                        $return_product_item['combo_id'] = (isset($product['combo_id']) && $product['combo_id'] != null) ? $product['combo_id'] : '' ;
                        $return_product_item['combo_cart_id'] = (isset($product['combo_cart_id']) && $product['combo_cart_id'] != null) ? $product['combo_cart_id'] : '';

                        // echo $return_product_item['quantity'];

                        $order_id = ReturnOrdersProductsModel::create($return_product_item)->id;

                        // Add quantity history
                        $this->addQuantityHistory($this->generate_slack('quantity_history'),$return_product_item['product_id'],$request->logged_user_store_id,($request->return_type == 'Damage') ? 'ORDER_DAMAGE' : 'ORDER_RETURN',($request->return_type == 'Damage') ? 'DECREMENT' : 'INCREMENT',$return_product_item['quantity'],$order_id);

                    }
                    if($product['is_gifted'] == 1){
                        $return_order_count += $product['quantity'];
                    }
                }
            }
            
            $return_orders = ReturnOrdersProductsModel::where('order_id', $order['id'])->get()->toArray();
            $order_products = OrderProductModel::where('order_id', $order['id'])->get()->toArray();
            $order_product_count = 0;
            foreach ($return_orders as $return_order) {
                $return_order_count += $return_order['quantity'];
            }
            foreach ($order_products as $order_product) {
                $order_product_count += $order_product['quantity'];
            }
            if ($return_order_count == $order_product_count) {

                $update_order_data = [
                    "status" => 6,
                    "return_order_amount" => $total_order_amount
                ];
            } else {
                $update_order_data = [
                    "status" => 1,
                    "return_order_amount" => $total_order_amount
                ];
            }

            OrderModel::where('slack', $order_slack)->update($update_order_data);

            // if (isset($order_basic['transactions']) && !empty($order_basic['transactions'])) {
            //     $order_basic_transactions = $order_basic['transactions'];
            //     foreach ($order_basic_transactions as $order_basic_transaction) {
            //         $transaction_slack = $order_basic_transaction['slack'];
            //         $transaction_amount = $order['total_order_amount'];
            //         $transaction_data = TransactionModel::where('slack', $transaction_slack)->first();
            //         if ($return_order_count == $order_product_count) {
            //             $transaction_data->amount = 0.00;
            //         } else {
            //             $transaction_data->amount = abs(round((float)$transaction_data->amount, 2) - round((float)$transaction_amount, 2));
            //         }
            //         $transaction_data->save();
            //     }
            // }

            // Add a transaction entry for return with minus amount for calculatig the total existing order amount for reports.
            $customer_data = CustomerModel::select('id', 'name', 'email', 'phone', 'address')
                ->where('id', '=', $order_detail->customer_id)->first();
            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', 'INCOME')->first();
            $transaction = [
                "slack" => $this->generate_slack("transactions"),
                "store_id" => $order_detail->store_id,
                "transaction_code" => Str::random(6),
                "account_id" => $order_detail->business_account_id,
                "transaction_type" => $transaction_type_data->id,
                "payment_method_id" => $order_detail->payment_method_id,
                "payment_method" => $order_detail->payment_method,
                "bill_to" => 'POS_ORDER',
                "bill_to_id" => $order_detail->id,
                "bill_to_name" => (isset($customer_data->name)) ? $customer_data->name : 'Walkin Customer',
                "bill_to_contact" => $order_detail->customer_phone,
                "bill_to_address" => (isset($customer_data->address)) ? $customer_data->address : '',
                "currency_code" => $order_detail->currency_code,
                "amount" => (-$return_total_order_amount),
                "pg_transaction_id" => '',
                "pg_transaction_status" => '',
                "notes" => '',
                "transaction_date" => date('Y-m-d'),
                "created_by" => request()->logged_user_id,
                "return_bill_to_id" => $return_order_id,
            ];
            // dd($paid_amount);
            $transaction_id = TransactionModel::create($transaction)->id;
    
            $code_start_config = Config::get('constants.unique_code_start.transaction');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;
    
            $transaction_code = [
                "transaction_code" => ($code_start + $transaction_id)
            ];
            TransactionModel::where('id', $transaction_id)->update($transaction_code);

            //Qoyod
            if(Session('qoyod_status')){
                $order_return_detail = ReturnOrders::where('id',$return_order_id)->first();
                $this->qoyod_create_credit_note($order_return_detail,'Order');
            }
            DB::commit();
            $returnorder = new ReturnOrderResource($return_order_entry);
            $item_data = ReturnOrdersProductsModel::select('order_return_product.*','combos.name as combo_name')->where('return_order_id', $return_order_id)->ComboJoin()->get();
            $forward_link = route('return_order_receipt', ['slack' => $return_order_add['slack']]);
            $this->record_return_hrm_transaction($return_order_slack, $forward_link);
            if (isset($return_order_add)) {
                $returnorder['products'] = $item_data;
                return response()->json($this->generate_response(
                    array(
                        "message" => "Return Order created successfully",
                        "data" => $return_order_add['slack'],
                        "link" => $forward_link,
                        "order" => $returnorder
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => "No order found.",
                    ),
                    'ERROR'
                ));
            }
        } 
        // catch (\GuzzleHttp\Exception $e) {
        //     return [
        //         'status' => false,
        //         'message' => $e->getResponse()->getReasonPhrase() . " from Zid",
        //     ];
        // }
        catch (Exception $e) {

            DB::rollback();
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function returnOrderCalculations($bill_wise_details, $return_products) { 
        
        $grand_total = 0;
        $item_total_excl_tax_before_discount = 0;
        $item_total_excluding_tax = 0;
        $last_additional_discount_amount = 0;
        $final_total_tax = 0;
        $total_quantity = 0;
        $additional_discount_amount = $bill_wise_details['additional_discount_amount'];
        $sale_amount_subtotal_excluding_tax = $bill_wise_details['sale_amount_subtotal_excluding_tax'];
        $additional_discount_percentage = $bill_wise_details['additional_discount_percentage'];
        if ($bill_wise_details['discount_type'] == 1) {
            $additional_discount_percentage = (($additional_discount_amount) / $sale_amount_subtotal_excluding_tax)*100;
        } 
        $item_level_total_tax_details = []; $item_level_total_tobacco_tax_details = []; $products_arr_calc = [];
        
        // foreach ($return_products as $products) {
        //     $order_product = OrderProductModel::where('slack', $products['cart_slack'])->first();
            
        //     $order_product->tobacco_tax_components = json_decode($order_product->tobacco_tax_components);
        //     $order_product->tax_components = json_decode($order_product->tax_components);
        //     if($order_product->is_gifted == 0){
                
        //         // if($order_product->tobacco_tax_components != null ){
        //         //     $tobacco_tax_detail = $order_product->tobacco_tax_components[0];
           
        //         //     $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id] = $tobacco_tax_detail->tax_name_id;
        //         //     if (!isset($item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_percentage']) ) {
        //         //         $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id] = [];
        //         //     }
        //         //     $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_percentage'] = $tobacco_tax_detail->tax_percentage;
        //         //     $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_label'] = $tobacco_tax_detail->tax_type;
        //         //     $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_total'] = 0;
        //         // }
        //         // if ($order_product->tax_code_id > 0) {
        //         //     foreach($order_product->tax_components as $tax_detail) {
        //         //         $item_level_total_tax_details[$tax_detail->tax_name_id] = $tax_detail->tax_name_id;
        //         //         if (!isset($item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_percentage'])) {
        //         //             $item_level_total_tax_details[$tax_detail->tax_name_id] = [];
        //         //         }
        //         //         $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_percentage'] = $tax_detail->tax_percentage;
        //         //         $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_label'] = $tax_detail->tax_type;
        //         //         $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_total'] = 0;                        
        //         //     }
        //         // }
        //     }
        // }

        foreach ($return_products as $products) {
            
            $order_product = OrderProductModel::where('slack', $products['cart_slack'])->first();
            
            //dd($order_product);
            $products['order_product_id'] = $order_product->id;
            $products['is_gifted'] = $order_product->is_gifted??0;
            $products['product_id'] = $order_product->product_id;
            $products['product_slack'] = $order_product->product_slack;
            $products['slack'] = $order_product->slack;
            $products['product_code'] = $order_product->product_code;
            $products['name'] = $order_product->name;
            $products['purchase_amount_excluding_tax'] = isset($order_product->purchase_amount_excluding_tax) ? $order_product->purchase_amount_excluding_tax : NULL;
            $products['sale_amount_excluding_tax'] = isset($order_product->sale_amount_excluding_tax) ? $order_product->sale_amount_excluding_tax : NULL;
            $products['discount_code_id'] = isset($order_product->discount_code_id) ? $order_product->discount_code_id : NULL;
            $products['discount_code'] = isset($order_product->discount_code) ? $order_product->discount_code : NULL;
            $products['discount_percentage'] = isset($order_product->discount_percentage) ? $order_product->discount_percentage : 0.00;
            $products['tax_code_id'] = isset($order_product->tax_code_id) ? $order_product->tax_code_id : NULL;
            $products['tax_code'] = isset($order_product->tax_code) ? $order_product->tax_code : NULL;
            $products['tax_percentage'] = isset($order_product->tax_percentage) ? $order_product->tax_percentage : 0.00;
            $products['is_ready_to_serve'] = isset($order_product->is_ready_to_serve) ? $order_product->is_ready_to_serve : NULL;
            $products['combo_id'] = isset($order_product->combo_id) ? $order_product->combo_id : NULL;
            $products['combo_cart_id'] = isset($order_product->combo_cart_id) ? $order_product->combo_cart_id : NULL;

            $quantity = $products['quantity'];
            // $max_quantity = $order_product->quantity;

            if($quantity > 0){
                $ordered_quantity = $order_product->quantity;
                $pre_returned_quantity = ReturnOrdersProductsModel::where('product_slack', $order_product->slack)->sum('quantity');
                $product_name = $order_product->name;

                $ret_qty = $quantity;
                $max_quantity = (float)$ordered_quantity - (float)$pre_returned_quantity;
            
                if($max_quantity <= 0){
                    throw new Exception("Item '$product_name' has returned all quantities!", 400);
                }elseif($ret_qty > $max_quantity){
                    throw new Exception("Item '$product_name' has returned the quantity of $ret_qty is more than the ordered balance quantity of $max_quantity.", 400);
                }
            }

            if($order_product->is_gifted == 0){
                $cart_length = count($products);
                $modifier_price = 0; //$products->total_modifier_amount;

                // $order_product_modifier_options = OrderProductModifierOptionModel::select('*')
                //     ->where('order_product_modifier_options.order_product_id',$order_product->id)->get();

                $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id', $order_product->id)
                        ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();

                $modifier_options = [];
                //dd($order_product_modifier_options);
                if(isset($order_product_modifier_options) && count($order_product_modifier_options) > 0){
                    
                    foreach($order_product_modifier_options as $product_modifier_option){
                        $modifier_price += $product_modifier_option->modifier_option_price;
                        $modifier_options[] = [
                            'modifier_label' => $product_modifier_option->modifier_label,
                            'modifier_option_label' => $product_modifier_option->modifier_option_label,
                            'modifier_option_price' => $product_modifier_option->modifier_option_price
                        ];
                    }
                }
                $products['total_modifier_amount'] = $modifier_price;
                $products['modifier_options'] = json_encode($modifier_options);

                $item_total_max = 0;
                $tax_amount = 0;
                $item_total = 0;
                
                $unit_price = $order_product->sale_amount_excluding_tax + $modifier_price;
                $discount_percentage = $order_product->discount_percentage;
                $discount_amount = $order_product->discount_amount;
                $tax_percentage = $order_product->tax_percentage;
                $total_after_discount = 0;
                if ( isset($quantity) && !empty($quantity) && isset($unit_price) && !empty($unit_price)) {
                    
                    $item_total_max = $ordered_quantity * $unit_price;
                    $item_total = $quantity * $unit_price;
                    $item_total_excl_tax_before_discount = ($item_total_excl_tax_before_discount) + $item_total;
                    if ( isset($discount_percentage) && $discount_percentage > 0 ) {
                        $discount_amount = $this->calculate_discount( $item_total,$discount_percentage);
                        //discount_amount = round(discount_amount, 4, ".", "");
                        //$products['discount_amount'] = round(discount_amount, 4, ".", "");
                        $item_total = $item_total - $discount_amount;
                    }else if ( isset($discount_amount) && $discount_amount > 0) {
                        $discount_percentage = ($discount_amount / $item_total_max)*100;
                        // if(isNaN(discount_percentage)){
                        //     discount_percentage = 0;
                        // }
                        $discount_amount = $this->calculate_discount( $item_total,$discount_percentage);
                        $item_total = $item_total - $discount_amount;
                        $products['discount_amount_calc'] = round($discount_amount, 4);
                    }
                    // console.log("item_total with dis =", item_total);
                    $item_total_curr = round($item_total, 4);
                    $products['total_price'] = $item_total_curr;
                    $products['sub_total_purchase_price_excluding_tax'] = $item_total_curr;
                    $products['total_after_discount'] = $item_total_curr;

                    $item_total_excluding_tax = $item_total_excluding_tax + $item_total;
                    if($additional_discount_percentage > 0){
                        $additional_discount_amount = $this->calculate_discount( $item_total, $additional_discount_percentage );
                    }
                    $total_after_discount = $item_total - $additional_discount_amount;
                    $item_total = $total_after_discount;

                    $tobacco_tax_amount = 0;
                    $order_product->tobacco_tax_components = json_decode($order_product->tobacco_tax_components);
                    if($order_product->tobacco_tax_components != null ){
                        if(count($order_product->tobacco_tax_components) > 0){
                            $tobacco_tax_amount = $this->calculate_tax($total_after_discount, $order_product->tobacco_tax_components[0]->tax_percentage);
                            $item_total = ($total_after_discount) + ($tobacco_tax_amount);
                            $products['tobacco_tax_components'][0]['tax_type'] = $order_product->tobacco_tax_components[0]->tax_type;
                            $products['tobacco_tax_components'][0]['tax_name_id'] = 0;
                            $products['tobacco_tax_components'][0]['tax_percentage'] = $order_product->tobacco_tax_components[0]->tax_percentage;
                            $products['tobacco_tax_components'][0]['tax_amount'] = round($tobacco_tax_amount,4);

                            $tobacco_tax_detail = $order_product->tobacco_tax_components[0];
                           // $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id] = $tobacco_tax_detail->tax_name_id;
                            if (!isset($item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_percentage']) ) {
                                $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id] = [];
                                $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_percentage'] = $tobacco_tax_detail->tax_percentage;
                                $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_label'] = $tobacco_tax_detail->tax_type;
                                $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_total'] = 0;
                            }
                            $item_level_total_tobacco_tax_details[$tobacco_tax_detail->tax_name_id]['item_tax_total'] += ($tobacco_tax_amount); 
                        }
                    }
                    // console.log("item_total with tobacco =", item_total);
                    $order_product->tax_components = json_decode($order_product->tax_components);
                    $tax_splitup_totals = 0;
                    if ( isset($tax_percentage) && !empty($tax_percentage) && !empty($order_product->tax_components)) {
                        // $tax_amount = $this->calculate_tax($item_total, $tax_percentage);
                        foreach($order_product->tax_components as $key => $tax_detail) {
                            $curr_total_tax = $this->calculate_tax($item_total, $tax_detail->tax_percentage);
                            // $curr_total_tax = ($curr_total_tax);
                            $tax_splitup_totals = $tax_splitup_totals + $curr_total_tax;

                            // $item_level_total_tax_details[$tax_detail->tax_name_id] = $tax_detail->tax_name_id;
                            if (!isset($item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_percentage'])) {
                                $item_level_total_tax_details[$tax_detail->tax_name_id] = [];
                                $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_percentage'] = $tax_detail->tax_percentage;
                                $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_label'] = $tax_detail->tax_type;
                                $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_total'] = 0;
                            }
                            $item_level_total_tax_details[$tax_detail->tax_name_id]['item_tax_total'] += $curr_total_tax;

                            $products['tax_components'][$key]['tax_type'] = $tax_detail->tax_type;
                            $products['tax_components'][$key]['tax_name_id'] = $tax_detail->tax_name_id;
                            $products['tax_components'][$key]['tax_percentage'] = $tax_detail->tax_percentage;
                            $products['tax_components'][$key]['tax_amount'] = round($curr_total_tax,4);
                        }                        
                    }
                    $products['sub_total'] = $item_total;
                    $final_total_tax = $final_total_tax + $tax_splitup_totals + $tobacco_tax_amount;
                    $item_total = $item_total + $tax_splitup_totals;
                    $products['total_amount'] = round($item_total,4);
                    $products['tax_amount'] = round($tax_splitup_totals, 4);
                    $last_additional_discount_amount += $additional_discount_amount;
                    $total_quantity = $total_quantity + $quantity;
                    // $total_max_quantity = ($total_max_quantity) + ($max_quantity);
                    $grand_total = $grand_total + $item_total;
                } 
           }
           $products_arr_calc[] = $products; 
        }
        $bill_wise_details['additional_discount_amount'] = round( $last_additional_discount_amount, 2);

        if ( isset($bill_wise_details['sale_amount_subtotal_excluding_tax']) && $bill_wise_details['sale_amount_subtotal_excluding_tax'] != "") {
            $bill_wise_details['sale_amount_subtotal_excluding_tax'] = round($item_total_excluding_tax, 2);
        }
        if ( isset($bill_wise_details['additional_discount_amount']) && $bill_wise_details['additional_discount_amount'] != "" ) {
            $total_exclude_tax_with_disc = ($item_total_excluding_tax) - ($last_additional_discount_amount);
            $bill_wise_details['total_after_discount'] = round( $total_exclude_tax_with_disc, 2 );
        }

        $order_level_tax_components = [];
        foreach($item_level_total_tobacco_tax_details as $tax_detail) {
            if (isset($tax_detail)) {
                $tax_obj = [
                    'tax_type' => $tax_detail['item_tax_label'],
                    'tax_percentage' => $tax_detail['item_tax_percentage'],
                    'tax_amount' => round($tax_detail['item_tax_total'],4)
                ];
                $order_level_tax_components[] = $tax_obj;
            }
        }
        foreach($item_level_total_tax_details as $tax_detail) {
            if (isset($tax_detail)) {
                $tax_obj = [
                    'tax_type' => $tax_detail['item_tax_label'],
                    'tax_percentage' => $tax_detail['item_tax_percentage'],
                    'tax_amount' => round($tax_detail['item_tax_total'],4)
                ];
            $order_level_tax_components[] = $tax_obj;
            }
        }
        $bill_wise_details['order_level_tax_components'] = $order_level_tax_components;

        $bill_wise_details['total_tax_amount'] = round($final_total_tax, 2);

        $grand_total = round($grand_total,2);
        $bill_wise_details['total_order_amount'] = $grand_total;

        // dd($bill_wise_details, $products_arr_calc);
        $order_return_data['bill_wise_details'] = $bill_wise_details;
        $order_return_data['products_arr_calc'] = $products_arr_calc;
        return $order_return_data;
    }

    function cal_percentage($num_amount, $num_total) {
        $count1 = $num_amount / $num_total;
        $count2 = $count1 * 100;
        $count = number_format($count2, 2);
        return $count;
    }

    public function order_list_by_date(Request $request)
    {
        try {
            
            $updated_at = (isset($request->updated_at) && $request->updated_at != '') ? $request->updated_at : '';

            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $startDate = Carbon::createFromFormat('d-m-Y', $startDate)->format('Y-m-d 00:00:00');
            $endDate = Carbon::createFromFormat('d-m-Y', $endDate)->format('Y-m-d 23:59:59');
            $orders = OrderModel::select('*')->where('created_at', '>=', $startDate)
            ->when( ($updated_at != '' ), function($query) use($updated_at) {
                $query->where('updated_at','>=',$updated_at);
            })
            ->where('created_at', '<=', $endDate)->orderBy('created_at', 'desc');
            if (isset($orders)) {
                $order_list     = $orders->paginate();
                $list = new OrderListByDateCollection($order_list);
                return response()->json($this->generate_response(
                    array(
                        "message" => "Order fetched successfully",
                        "data" => $list
                    ),
                    'SUCCESS'
                ));
            } else {
                return response()->json($this->generate_response(
                    array(
                        "message" => "No order found.",
                    ),
                    'ERROR'
                ));
            }
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function close_order_from_kitchen(Request $request)
    {

        try {

            DB::beginTransaction();

            OrderModel::where('id', $request->order_id)->update([
                'status' => 1,
                'kitchen_status' => 2
            ]);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Order Closed Successfully",
                ),
                'ERROR'
            ));
        } catch (Exception $e) {

            DB::rollback();

            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }

    public function get_order_list(Request $request)
    {
        $order_number = $request->ordernumber;
        $order_value_date = Carbon::parse($request->valuedate)->format('Y-m-d');
        $ordered_device_id = $request->deviceid;
        $order_param_array = [$order_number, $order_value_date, $ordered_device_id];
        $order_request_array = [
            'order_number' => $order_number,
            'value_date' => $order_value_date,
            'device_id' => $ordered_device_id
        ];

        $order_param_count = count(array_filter($order_param_array, function ($arr) {
            return !empty($arr);
        }));

        $order_query = OrderModel::select('orders.*', 'transactions.id as transaction_id')
            ->join('transactions', 'transactions.bill_to_id', '=', 'orders.id')
            ->orderBy('transactions.bill_to_id', 'desc');

        if ($order_param_count > 0 && $order_param_count < 3) {
            $validator = FacadesValidator::make($order_request_array, [
                'order_number' => 'required',
                'value_date' => 'required',
                'device_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($this->generate_response(
                    array(
                        "message" => $validator->errors()->all(),
                    ),
                    'ERROR'
                ));
            }
        } elseif ($order_param_count == 3) {
            $order_query = $order_query->where([
                ['orders.order_number', '=', $order_number],
                ['orders.value_date', '=', $order_value_date],
                ['orders.device_id', '=', $ordered_device_id]
            ]);
        }

        $order_query = $order_query->when(request('transId', false), function ($query, $trans_id) {
            return $query->where('transactions.id', $trans_id);
        });

        $order_query = $order_query->when(request('email', false), function ($query, $email) {
            return $query->where('orders.customer_email', $email);
        });

        $order_query = $order_query->when(request('phone', false), function ($query, $phone) {
            return $query->where('orders.customer_phone', $phone);
        });

        $order_query = $order_query->get();

        $item_data = OrderResource::collection($order_query);

        return response()->json($this->generate_response(
            array(
                "message" => "Order List",
                "data" => $item_data
            ),
            'SUCCESS'
        ));
    }


    private function __check_and_update_modifier_ingredient_stock($order_products){

        $requested_modifiers = [];
        foreach($order_products as $order_product){

            if (isset($order_product['modifiers']) && !empty($order_product['modifiers']) && $order_product['modifiers'] != "[]"  && $order_product['modifiers'] != "[]" ) {
                foreach ($order_product['modifiers'] as $order_product_modifier) {
                    if(array_key_exists($order_product_modifier->id,$requested_modifiers) ) {
                        $requested_modifiers[$order_product_modifier->id]['quantity'] += (float) $order_product['quantity'];
                    }else{
                        $dataset = [];
                        $dataset['label'] = $order_product_modifier->label;
                        $dataset['price'] = $order_product_modifier->price;
                        $dataset['quantity'] = (float) $order_product['quantity'];
                        $requested_modifiers[$order_product_modifier->id] = $dataset;
                    }
                }
            }

        }
        
        $requested_modifier_ingredients = [];
        if(isset($requested_modifiers)){
            foreach($requested_modifiers as $key => $value){
                $modifier_ingredients = ModifierOptionIngredient::where('modifier_option_id',$key)->get();
                if(isset($modifier_ingredients)){
                    foreach($modifier_ingredients as $modifier_ingredient){
                        
                        $converted_quantity = $this->__convert_ingredient_quantity($modifier_ingredient);
                        $converted_quantity = (float) $converted_quantity * (float) $value['quantity']; 
                        
                        if(array_key_exists($modifier_ingredient->ingredient_id,$requested_modifier_ingredients) ) {
                            $requested_modifier_ingredients[$modifier_ingredient->ingredient_id]['quantity'] += $converted_quantity; 
                        }else{
                            $dataset = []; 
                            $dataset['quantity'] = (float) $converted_quantity; 
                            $requested_modifier_ingredients[$modifier_ingredient->ingredient_id] = $dataset; 
                        }
                    }
                }

            }
        }

        // dd($requested_modifier_ingredients);

        $error_messages = [];
        if(isset($requested_modifier_ingredients)){
            foreach($requested_modifier_ingredients as $ingredient_id => $ingredient_qty){
                
                $ingredient = ProductModel::where('id',$ingredient_id)->select('name','quantity','measurement_id')->first();
                
                if(isset($ingredient) && $ingredient->quantity < $ingredient_qty['quantity']){
                    $measurement = Measurement::find($ingredient->measurement_id);
                    $error_message = "INGREDIENT OUT OF STOCK: ordered quantity for [".$ingredient->name."] is ".$ingredient_qty['quantity']. " ".$measurement->label." where available quantity is ".$ingredient->quantity. " ".$measurement->label .",";
                    array_push($error_messages,$error_message);
                }

            }
        }

        if(count($error_messages) > 0){
            throw new Exception( implode(" ",$error_messages) ,400);
        }else{
            foreach($requested_modifier_ingredients as $ingredient_id => $ingredient_qty){
                $ingredient = ProductModel::find($ingredient_id);
                if(isset($ingredient) && $ingredient->quantity != '-1.00'){
                    $ingredient->decrement('quantity', (float) $ingredient_qty['quantity']);
                }
            }
        }

    }

    private function __update_modifier_single_ingredient_stock($order_products){

        $requested_modifiers = [];
        // foreach($order_products as $order_product){
        if (isset($order_products['modifiers']) && !empty($order_products['modifiers'])) {
            foreach ($order_products['modifiers'] as $order_product_modifier) {
                $modifier_id = $order_product_modifier['id'];
                if(array_key_exists($modifier_id,$requested_modifiers) ) {
                    $requested_modifiers[$modifier_id]['quantity'] += (float) $order_products['quantity']; 
                }else{
                    $dataset = [];
                    $dataset['quantity'] = (float) $order_products['quantity']; 
                    $requested_modifiers[$modifier_id] = $dataset; 
                }
            }
        }
        
       // }
        $requested_modifier_ingredients = [];
        if(isset($requested_modifiers)){
            foreach($requested_modifiers as $key => $value){
                $modifier_ingredients = ModifierOptionIngredient::where('modifier_option_id',$key)->get();
                if(isset($modifier_ingredients)){
                    foreach($modifier_ingredients as $modifier_ingredient){
                        
                        $converted_quantity = $this->__convert_ingredient_quantity($modifier_ingredient);
                        $converted_quantity = (float) $converted_quantity * (float) $value['quantity']; 
                        if(array_key_exists($modifier_ingredient->ingredient_id,$requested_modifier_ingredients) ) {
                            $requested_modifier_ingredients[$modifier_ingredient->ingredient_id]['quantity'] += $converted_quantity; 
                        }else{
                            $dataset = []; 
                            $dataset['quantity'] = (float) $converted_quantity; 
                            $requested_modifier_ingredients[$modifier_ingredient->ingredient_id] = $dataset; 
                        }
                    }
                }

            }
        }

        $error_messages = [];
        if(count($error_messages) > 0){
            throw new Exception( implode(" ",$error_messages) ,400);
        }else{
            foreach($requested_modifier_ingredients as $ingredient_id => $ingredient_qty){
                $ingredient = ProductModel::find($ingredient_id);
                if(isset($ingredient) && $ingredient->quantity != '-1.00'){
                    $ingredient->increment('quantity', (float) $ingredient_qty['quantity']);
                }
            }
            return true;
        }
        

    }

    private function __check_ingredient_stock($product_id, $requested_product_quantity)
    {

        $product_ingredients = ProductIngredientModel::with('measurements')->where('product_id', $product_id)->get();

        $low_ingredient_stock = 0;
        $ingredient_stocks = [];

        if (!empty($product_ingredients)) {
            foreach ($product_ingredients as $product_ingredient) {

                $ingredient = ProductModel::where('id', $product_ingredient->ingredient_product_id)->active()->first();

                if ($product_ingredient->measurements != null) {

                    if ($ingredient->measurement_id == $product_ingredient->measurement_id || $product_ingredient->measurements->measurement_category_id == "") {

                        $quantity = $product_ingredient->quantity * $requested_product_quantity;
                        if (($ingredient->quantity < $quantity) && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                        if ($ingredient->quantity == '-1.00') {
                            $quantity = '-1';
                        }
                    } else {

                        // if not same then we need to get the conversion, means 1 == ?
                        $measurement_conversion = MeasurementConversionModel::where([
                            'from_measurement_id' => $product_ingredient->measurement_id,
                            'to_measurement_id' => $ingredient->measurement_id
                        ])->active()->first();

                        if(isset($measurement_conversion->value))
                        {
                          $quantity = ((float) $measurement_conversion->value * $product_ingredient->quantity) * $requested_product_quantity;
                        }
                        else
                        {
                            $quantity = ((float)$product_ingredient->quantity) * $requested_product_quantity;
                        }
                        if ($ingredient->quantity < $quantity && ($ingredient->quantity != '-1.00')) {
                            $low_ingredient_stock = 1;
                        }
                        if ($ingredient->quantity == '-1.00') {
                            $quantity = '-1';
                        }
                    }

                    $dataset = [];
                    $dataset[$ingredient->slack] = $quantity;
                    // $dataset['quantity'] = $quantity;
                    $ingredient_stocks[] = $dataset;
                }
            }
        }

        return $ingredient_stocks;
    }

    private function __convert_ingredient_quantity($ingredient)
    {

        $converted_quantity = $ingredient->quantity;
        
        
        $modifier_ingredient_id = $ingredient->ingredient_id;
        $modifier_ingredient_measurement_id = $ingredient->measurement_id;

        $ingredient = ProductModel::where('id', $modifier_ingredient_id)->active()->first();
        
        if($ingredient->quantity != '-1.00'){

            if($modifier_ingredient_measurement_id != $ingredient->measurement_id){
                
                $measurement_conversion = MeasurementConversionModel::where([
                    'from_measurement_id' => $modifier_ingredient_measurement_id,
                    'to_measurement_id' => $ingredient->measurement_id
                ])->active()->first();

                if(isset($measurement_conversion->value))
                {
                    $converted_quantity = (float) $measurement_conversion->value * $converted_quantity;
                }else{
                    throw new Exception('Missing measurement converions',400);
                }

            }
        
        }

        return $converted_quantity;
        
    }

    public function __get_measurement_conversion_value($from,$to,$quantity){

        if($from->id != $to->id){
            $measurement_conversion = MeasurementConversionModel::where([
                'from_measurement_id' => $from->id,
                'to_measurement_id' => $to->id
            ])->active()->first();
            if(isset($measurement_conversion->value))
            {
                return (float) $measurement_conversion->value * $quantity;
            }else{
                throw new Exception('Missing measurement converions',400);
            }

        }else{
            return $quantity;
        }

    }
    

    public function record_hrm_transaction($order_slack, $transaction_receipt_link)
    {
        $order_detail = OrderModel::select('*')->where('slack', $order_slack)->first();
        $acc_transaction = AccTransactionModel::orderBy('id', 'DESC')->first();

        $transaction_id = isset($acc_transaction->ID) ? $acc_transaction->ID : 0;
        $Vtype = "JV";
        $voucher_no = "Journal-" . bcadd($transaction_id, 1);


        $id = isset($order_detail->id) ? $order_detail->id : NULL;


        //Discount check

        $sales_discount_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales Discount%')
            ->first();
        if (empty($sales_discount_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }
        if (isset($order_detail->total_discount_amount) && $order_detail->total_discount_amount != 0.00) {
            $sales_discount_debit = isset($order_detail->total_discount_amount) ? $order_detail->total_discount_amount : 0;
            $sales_discount_credit =  0;

            $sales_discount_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_discount_headcode->HeadCode) ? $sales_discount_headcode->HeadCode : '',
                'Narration'      =>  'POS Entry',
                'Debit'          =>  empty($sales_discount_debit) ? 0 : $sales_discount_debit,
                'Credit'         =>  empty($sales_discount_credit) ? 0 : $sales_discount_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_discount_transaction)->id;
        }

        //Sales Tax check

        $sales_vat_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales VAT%')
            ->first();
        if (empty($sales_vat_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($order_detail->store_level_total_tax_amount) && $order_detail->store_level_total_tax_amount != 0.00) {
            $sales_vat_debit =  0;
            $sales_vat_credit = isset($order_detail->store_level_total_tax_amount) ? $order_detail->store_level_total_tax_amount : 0;

            $sales_vat_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_vat_headcode->HeadCode) ? $sales_vat_headcode->HeadCode : '',
                'Narration'      =>  'POS Entry',
                'Debit'          =>  empty($sales_vat_debit) ? 0 : $sales_vat_debit,
                'Credit'         =>  empty($sales_vat_credit) ? 0 : $sales_vat_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_vat_transaction)->id;
        }

        // $modifier_total_amount = OrderProductModel::where('order_id', $order_detail->id)->sum('total_modifier_amount');


        //Sales check
        $sales_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'Sales')
            ->first();
        if (empty($sales_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($order_detail->total_amount_before_additional_discount)) {

            $sales_debit =  0;
            // $sales_credit = isset($order_detail->sale_amount_subtotal_excluding_tax) ? bcadd($order_detail->sale_amount_subtotal_excluding_tax, $modifier_total_amount, 2)  : 0;
            // total_amount_before_additional_discount
            $sales_credit = isset($order_detail->total_amount_before_additional_discount) ? $order_detail->total_amount_before_additional_discount : 0;

            $sales_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_headcode->HeadCode) ? $sales_headcode->HeadCode : '',
                'Narration'      =>  'POS Entry',
                'Debit'          =>  empty($sales_debit) ? 0 : $sales_debit,
                'Credit'         =>  empty($sales_credit) ? 0 : $sales_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_transaction)->id;
        }

        //Bank check
        $cash_In_hand_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Cash In Hand%')
            ->first();
        if (empty($cash_In_hand_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        $cash_at_bank_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Default%')
            ->where('PHeadName', 'like', '%Cash At Bank%')
            ->first();
        if (empty($cash_at_bank_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if ($order_detail->payment_method != 'Cash') {
            $credit_amount_debit =  isset($order_detail->credit_amount) ? $order_detail->credit_amount : 0;
            $credit_amount_credit =  0;

            if ($credit_amount_debit != 0.00 || $credit_amount_debit != 0) {

                $cash_at_bank_transaction = [
                    'VNo'            =>  $voucher_no,
                    'Vtype'          =>  $Vtype,
                    'VDate'          =>  date('Y-m-d'),
                    'COAID'          =>  isset($cash_at_bank_headcode->HeadCode) ? $cash_at_bank_headcode->HeadCode : '',
                    'Narration'      =>  'POS Entry',
                    'Debit'          =>  empty($credit_amount_debit) ? 0 : $credit_amount_debit,
                    'Credit'         =>  empty($credit_amount_credit) ? 0 : $credit_amount_credit,
                    'transaction_id' => isset($id) ? $id : NULL,
                    'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                    'order_slack' => $order_slack,
                    'IsPosted'       => 1,
                    'CreateBy'       => request()->logged_user_id,
                    'CreateDate'     => date('Y-m-d'),
                    'IsAppove'       => 0
                ];


                $transaction_id = AccTransactionModel::create($cash_at_bank_transaction)->id;
            }


            $cash_amount_debit =  isset($order_detail->cash_amount) ? $order_detail->cash_amount : 0;
            $cash_amount_credit =  0;

            if ($cash_amount_debit != 0.00 || $cash_amount_debit != 0) {
                $cash_amount_transaction = [
                    'VNo'            =>  $voucher_no,
                    'Vtype'          =>  $Vtype,
                    'VDate'          =>  date('Y-m-d'),
                    'COAID'          =>  isset($cash_In_hand_headcode->HeadCode) ? $cash_In_hand_headcode->HeadCode : '',
                    'Narration'      =>  'POS Entry',
                    'Debit'          =>  empty($cash_amount_debit) ? 0 : $cash_amount_debit,
                    'Credit'         =>  empty($cash_amount_credit) ? 0 : $cash_amount_credit,
                    'transaction_id' => isset($id) ? $id : NULL,
                    'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                    'order_slack' => $order_slack,
                    'IsPosted'       => 1,
                    'CreateBy'       => request()->logged_user_id,
                    'CreateDate'     => date('Y-m-d'),
                    'IsAppove'       => 0
                ];


                $transaction_id = AccTransactionModel::create($cash_amount_transaction)->id;
            }
        } else {
            //Cash check
            $cash_in_hand_debit =  isset($order_detail->total_order_amount) ? $order_detail->total_order_amount : 0;
            $cash_in_hand_credit = 0;

            $cash_in_hand_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($cash_In_hand_headcode->HeadCode) ? $cash_In_hand_headcode->HeadCode : '',
                'Narration'      =>  'POS Entry',
                'Debit'          =>  empty($cash_in_hand_debit) ? 0 : $cash_in_hand_debit,
                'Credit'         =>  empty($cash_in_hand_credit) ? 0 : $cash_in_hand_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($cash_in_hand_transaction)->id;
        }
    }

    public function record_return_hrm_transaction($order_slack, $transaction_receipt_link)
    {
        $order_detail = ReturnOrdersModel::select('*')->where('slack', $order_slack)->first();
        $acc_transaction = AccTransactionModel::orderBy('id', 'DESC')->first();

        $id = isset($order_detail->id) ? $order_detail->id : NULL;

        $transaction_id = isset($acc_transaction->ID) ? $acc_transaction->ID : 0;
        $Vtype = "JV";
        $voucher_no = "Journal-" . bcadd($transaction_id, 1);

        //Discount check
        $sales_discount_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales Discount%')
            ->first();
        if (empty($sales_discount_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }
        if (isset($order_detail->total_discount_amount) && $order_detail->total_discount_amount != 0.00) {
            $sales_discount_debit = 0;
            $sales_discount_credit = isset($order_detail->total_discount_amount) ? $order_detail->total_discount_amount : 0;

            $sales_discount_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_discount_headcode->HeadCode) ? $sales_discount_headcode->HeadCode : '',
                'Narration'      =>  'Return POS Entry',
                'Debit'          =>  empty($sales_discount_debit) ? 0 : $sales_discount_debit,
                'Credit'         =>  empty($sales_discount_credit) ? 0 : $sales_discount_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_discount_transaction)->id;
        }

        //Sales Tax check

        $sales_vat_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales VAT%')
            ->first();
        if (empty($sales_vat_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($order_detail->total_tax_amount) && $order_detail->total_tax_amount != 0.00) {
            $sales_vat_debit = isset($order_detail->total_tax_amount) ? $order_detail->total_tax_amount : 0;
            $sales_vat_credit = 0;

            $sales_vat_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_vat_headcode->HeadCode) ? $sales_vat_headcode->HeadCode : '',
                'Narration'      =>  'Return POS Entry',
                'Debit'          =>  empty($sales_vat_debit) ? 0 : $sales_vat_debit,
                'Credit'         =>  empty($sales_vat_credit) ? 0 : $sales_vat_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_vat_transaction)->id;
        }

        //Sales Return check

        $sales_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales Return%')
            ->first();
        if (empty($sales_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($order_detail->total_amount_before_additional_discount)) {

            $sales_debit = isset($order_detail->total_amount_before_additional_discount) ? $order_detail->total_amount_before_additional_discount : 0;
            $sales_credit = 0;
            // total_amount_before_additional_discount
            $sales_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_headcode->HeadCode) ? $sales_headcode->HeadCode : '',
                'Narration'      =>  'Return POS Entry',
                'Debit'          =>  empty($sales_debit) ? 0 : $sales_debit,
                'Credit'         =>  empty($sales_credit) ? 0 : $sales_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_transaction)->id;
        }



        if ($order_detail->payment_method != 'Cash') {
            //Bank check

            $cash_at_bank_headcode = AccCoaModel::select('HeadCode')
                ->where('HeadName', 'like', '%Default%')
                ->where('PHeadName', 'like', '%Cash At Bank%')
                ->first();
            if (empty($cash_at_bank_headcode)) {
                return false;
                throw new Exception("Invalid Account selected", 400);
            }

            $cash_at_bank_debit =  0;
            $cash_at_bank_credit =  isset($order_detail->total_order_amount) ? $order_detail->total_order_amount : 0;


            $cash_at_bank_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($cash_at_bank_headcode->HeadCode) ? $cash_at_bank_headcode->HeadCode : '',
                'Narration'      =>  'Return POS Entry',
                'Debit'          =>  empty($cash_at_bank_debit) ? 0 : $cash_at_bank_debit,
                'Credit'         =>  empty($cash_at_bank_credit) ? 0 : $cash_at_bank_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($cash_at_bank_transaction)->id;
        } else {
            //Cash check

            $cash_In_hand_headcode = AccCoaModel::select('HeadCode')
                ->where('HeadName', 'like', '%Cash In Hand%')
                ->first();
            if (empty($cash_In_hand_headcode)) {
                return false;
                throw new Exception("Invalid Account selected", 400);
            }

            $cash_in_hand_debit =  0;
            $cash_in_hand_credit = isset($order_detail->total_order_amount) ? $order_detail->total_order_amount : 0;

            $cash_in_hand_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($cash_In_hand_headcode->HeadCode) ? $cash_In_hand_headcode->HeadCode : '',
                'Narration'      =>  'Return POS Entry',
                'Debit'          =>  empty($cash_in_hand_debit) ? 0 : $cash_in_hand_debit,
                'Credit'         =>  empty($cash_in_hand_credit) ? 0 : $cash_in_hand_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $order_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($cash_in_hand_transaction)->id;
        }
    }

    public function zid_update_product_quantity($new_quantity, $zid_product_id)
    {

        $zid_store_detail = StoreModel::find(session('store_id'))->first();

        if (empty($zid_store_detail)) {
            throw new Exception(trans("Please update your Zid Store API token or Zid Store Id from store settings"), 400);
        }

        $zid_store_api_token = $zid_store_detail->zid_store_api_token;
        $zid_store_id = $zid_store_detail->zid_store_id;

        $form_params = [
            "quantity" => $new_quantity,
        ];

        $update_zid_product_response = $this->sync_zid_update_product($zid_store_api_token, $zid_store_id, $form_params, $zid_product_id);

        if (!$update_zid_product_response) {
            throw new Exception(trans("Product failed to add on Zid"), 400);
        }
    }

    public function add_user_points($order_data)
    {

        $api_detail = UserPointsSettingsModel::select('*')->where('merchant_id', session()->get('merchant_id'))->first();

        $status = isset($api_detail->status) ? $api_detail->status : '';
        $token = isset($api_detail->token_id) ? $api_detail->token_id : '';
        $secret_key = isset($api_detail->secret_key) ? $api_detail->secret_key : '';
        $mobile_country_code =  "966";
        $mobile_number =  isset($order_data['customer_phone']) ? $order_data['customer_phone'] : '';
        $amount = isset($order_data['total_after_discount']) ? $order_data['total_after_discount'] : '';
        $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';

        if ($status == 1 && isset($token) && isset($secret_key) && isset($mobile_number)) {

            if ($subdomain_name == 'demo') {
                $url = env('ABKHAS_DEMO_URL');
            } else {
                $url = env('ABKHAS_LIVE_URL');
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, TRUE);

            $fields = json_encode(['token' => $token, 'secret_key' => $secret_key, 'mobile_country_code' => $mobile_country_code, 'mobile_number' => $mobile_number, 'amount' => $amount]);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
            ));

            $response = curl_exec($ch);
            Log::info($response);
        }
    }

    public function send_bonat_order_details($order_data, $order_id)
    {

        $store_details = StoreModel::select('slack')->where('id', $order_data['store_id'])->first();
        $store_slack = isset($store_details->slack) ? $store_details->slack : '';
        $data['store_slack'] = $store_slack;
        $data['counter_slack'] = $order_data['counter_slack'];
        $data['merchant_slack'] = session()->get('merchant_slack');
        $data['order_id'] = isset($order_id) ? $order_id : '';
        $data['total_after_discount'] = $order_data['total_after_discount'];


        $api_detail = BonatStorePointsSettingsModel::select('*')->where('merchant_id', session()->get('merchant_slack'))->where('store_id', $store_slack)->where('counter_id', $order_data['counter_slack'])->first();
        $status = isset($api_detail->status) ? $api_detail->status : '';
        $verify = isset($api_detail->is_verify) ? $api_detail->is_verify : '';
        $bonat_merchant_id = isset($api_detail->bonat_merchant_id) ? $api_detail->bonat_merchant_id : '';
        $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';

        if ($status == 1 && isset($bonat_merchant_id) && $verify == 1) {

            if ($subdomain_name == 'demo') {
                $url = env('BONAT_ORDER_URL');
            } else {
                $url = env('BONAT_ORDER_URL');
            }

            $result = $this->send_order_details($url, $data);

            if ($result == TRUE) {

                return TRUE;
            } else {

                return FALSE;
            }
        }
    }


    public function set_use_coupon_details($order_data)
    {
        $store_details = StoreModel::select('slack')->where('id', $order_data['store_id'])->first();
        $store_slack = $store_details->slack;
        $data['store_slack'] = $store_slack;
        $data['counter_slack'] = $order_data['counter_slack'];
        $data['merchant_slack'] = session()->get('merchant_slack');
        $data['coupon_code'] = $order_data['bonat_coupon'];

        $api_detail = BonatStorePointsSettingsModel::select('*')->where('merchant_id', session()->get('merchant_slack'))->where('store_id', $store_slack)->where('counter_id', $order_data['counter_slack'])->first();
        $status = isset($api_detail->status) ? $api_detail->status : '';
        $verify = isset($api_detail->is_verify) ? $api_detail->is_verify : '';
        $bonat_merchant_id = isset($api_detail->bonat_merchant_id) ? $api_detail->bonat_merchant_id : '';
        $subdomain_name = (Config::get('constants.subdomain_name')) ? Config::get('constants.subdomain_name') : '';

        if ($status == 1 && isset($bonat_merchant_id) && $verify == 1) {

            if ($subdomain_name == 'demo') {
                $url = env('BONAT_API_URL');
            } else {
                $url = env('BONAT_API_URL');
            }

            $result = $this->set_use_coupon($url, $data);

            if ($result == TRUE) {

                return TRUE;
            } else {

                return FALSE;
            }
        }
    }

    public function get_order_value_date()
    {
        $value_date = Carbon::now()->format('Y-m-d');
        if (request()->logged_store_opening_time != '' && request()->logged_store_closing_time != '') {
            $current_date_time = Carbon::now()->format('Y-m-d H:i');
            $store_opening_time = request()->logged_store_opening_time;
            $store_closing_time = request()->logged_store_closing_time;
            if ($current_date_time >= $store_opening_time && $current_date_time <= $store_closing_time) {
                $value_date = Carbon::parse($store_opening_time)->format('Y-m-d');
            }
        }
        return $value_date;
    }
    public function checkProductDiscount($cart_items,$store_id)
    {
        $currentdate = date("Y-m-d H:i:sa");
        $cartitems = [];
        $cartitems_category = [];
        $cartitems_discount = [];
        $cartitems_label = [];
        $discountcodes_inventory = [];

        foreach($cart_items as $cart)
        {   
            if((int)$cart->discount_code_id<=0)
            {
                array_push($cartitems,$cart->product_id);
                if(isset($cart->product_sub_category) && $cart->product_sub_category>0)
                {
                    array_push($cartitems_category,$cart->product_sub_category);
                }
                else
                {
                    array_push($cartitems_category,$cart->product_category);
                }
                array_push($cartitems_label,$cart->name);
            }
            else
            {
                array_push($cartitems_discount,$cart->discount_code_id);
            }
        }
        if(count($cartitems_discount)>0)
        {
            $discountcodes_inventory = DiscountcodeModel::select('*')
                ->where("store_id",$store_id)
                ->whereIn("id",$cartitems_discount)
                ->where("discounttype", "inventory")
                ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
                ->whereRaw("(limit_on_discount>0 or limit_on_discount=-1)")
                ->active()
                ->get();
        }
        if(count($discountcodes_inventory)>0) 
        {
            if(count($cartitems)>0 || count($cartitems_category)>0)
            {
                foreach($discountcodes_inventory as $inventorydiscount)
                {
                    if($inventorydiscount->discount_applied_on=="specific_products")
                    {
                        $items_after_filter = array_diff($cartitems,explode(",",$inventorydiscount->discount_applicable_products));
                        if(count($items_after_filter)>0)
                        {
                            $product_labels="";
                            foreach($items_after_filter as $key=>$value)
                            {
                                $product_labels.="'".$cartitems_label[$key]."' ,";
                            }
                            $product_labels = preg_replace("/,$/","",$product_labels);
                            if($product_labels!="")
                            {
                                throw new Exception("Discount '{$inventorydiscount->label}' not applicable for Products {$product_labels}");
                            }
                        } 
                    }
                    else if($inventorydiscount->discount_applied_on=="specific_product_categories")
                    {
                        foreach($cart_items as $cart)
                        {
                            $items_after_filter_category = array_diff($cartitems_category,explode(",",$inventorydiscount->discount_applicable_categories));
                            if(count($items_after_filter_category)>0)
                            {
                                $product_labels="";
                                foreach($items_after_filter_category as $key=>$value)
                                {
                                    $product_labels.="'".$cartitems_label[$key]."' ,";
                                }
                                $product_labels = preg_replace("/,$/","",$product_labels);
                                if($product_labels!="")
                                {
                                    throw new Exception("Discount '{$inventorydiscount->label}' not applicable for Products {$product_labels} ");
                                }
                            } 
                        }
                    }
                    else if($inventorydiscount->discount_applied_on=="all_products_except_specific")
                    {
                        foreach($cart_items as $cart)
                        {
                            $items_after_filter = array_intersect($cartitems,explode(",",$inventorydiscount->discount_not_applicable_products));
                            if(count($items_after_filter)>0)
                            {
                                $product_labels="";
                                foreach($items_after_filter as $key=>$value)
                                {   
                                    $product_labels.="'".$cartitems_label[$key]."' ,";
                                }
                                $product_labels = preg_replace("/,$/","",$product_labels);
                                if($product_labels!="")
                                {
                                    throw new Exception("Discount '{$inventorydiscount->label}' not applicable for Product {$product_labels}");
                                }
                            } 
                        }
                    }
                }
            }

        }
    }

    public function order_list_by_modified_date(Request $request)
    {
        try {

            if (!check_access(['A_VIEW_ORDER_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $from_date = $request->modified_date == '' ? '' : $request->modified_date;
            $from_date = str_replace('T', ' ', $from_date);

            $list = new OrderCollection(OrderModel::select('*')
                    ->where('updated_at', '>=', $from_date)
                    ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Orders loaded successfully",
                    "data"    => $list
                ),
                'SUCCESS'
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

    private function __check_duplicate_order($order){
        
        $data = [
            'status' => false,
            'data' => []
        ];

        if(isset($order['created_at']) && isset($order['order_number'])  && isset($order['reference_number'])  && isset($order['device_id']) ){
            
            $order['created_at'] = Carbon::createFromTimestamp($order['created_at'])->toDateTimeString();
            
            $duplicate_order = OrderModel::query()
            ->where('order_number',$order['order_number'])
            ->where('reference_number',$order['reference_number'])
            ->where('device_id',$order['device_id'])
            ->where('total_order_amount',$order['total_order_amount'])
            // ->where('created_at',$order['created_at'])
            ->first();

            if(isset($duplicate_order)){
            
                $data = [
                    'status' => true,
                    'data' => $duplicate_order
                ];
            
            }

        }

        return $data;

    }

    private function __terminate_duplicate_orders(){
        
        $orders =  OrderModel::query()
        ->select('id','created_at','updated_at',DB::raw('count(*)'))
        ->where('device_id','!=','')
        ->groupBy('created_at','updated_at')
        ->havingRaw('COUNT(*) > 1')
        ->get()
        ->toArray();

        if(isset($orders)){
            foreach($orders as $order){
                
                $duplicate_orders = OrderModel::query()
                ->where('created_at',$order['created_at'])
                ->where('updated_at',$order['updated_at'])
                ->where('id','!=',$order['id'])
                ->get();

                // dd($duplicate_orders);
                if(isset($duplicate_orders)){
                    foreach($duplicate_orders as $duplicate_order){
                        
                        $transaction = TransactionModel::where('bill_to','POS_ORDER')->where('bill_to_id',$duplicate_order->id)->first();
                        
                        // recording duplicate entry 
                        DB::table('order_duplication_logs')->insert([
                            'order_number' => $duplicate_order['order_number'],
                            'order_created_at' => $duplicate_order['created_at'],
                            'order_updated_at' => $duplicate_order['updated_at'],
                            'order_data' => json_encode($duplicate_order),
                            'transaction_data' => (isset($transaction)) ? json_encode($transaction) : json_encode([]),
                            'created_at' => Carbon::now(),
                        ]);

                        if(isset($transaction)){
                            $transaction->delete();
                        }
                        $duplicate_order->delete();
    
                    }
                }

            }
        }
    }

    public function customer_order_invoice(Request $request)
    {
        try {
            $data['action_key'] = 'A_VIEW_ORDER_LISTING';
            if (check_access(array($data['action_key']), true) == false) {
                $response = $this->no_access_response_for_listing_table();
                return $response;
            }
            $customer = CustomerModel::select('id')->where('slack',$request->customer_slack)->first();
            $item_array = array();
            $draw = $request->draw;
            $limit = $request->length;
            $offset = $request->start;

            $order_by = $request->order[0]["column"];
            $order_direction = $request->order[0]["dir"];

            $order_by_column =   explode(".", $request->columns[$order_by]['name']);
            $order_by_column = end($order_by_column);

            $filter_string = $request->search['value'];
            $filter_columns = array_filter(data_get($request->columns, '*.name'));

            $list_type_filter = $request->list_type;
            $orders_select = OrderModel::select('orders.id as order_id','orders.order_number as number', 'orders.value_date as date', 'orders.total_order_amount as amount','orders.created_at','orders.slack as order_slack','orders.status','orders.created_at','orders.updated_at','orders.created_by',DB::raw("'Order' as type"),'master_status.label','master_status.color','master_status.value_constant');
            $invoice_select = Invoice::select('invoices.id as order_id','invoices.invoice_number as number', 'invoices.invoice_date as date', 'invoices.total_order_amount as amount','invoices.created_at','invoices.slack as order_slack','invoices.status','invoices.created_at','invoices.updated_at','invoices.created_by',DB::raw("'Invoice' as type"),'master_status.label','master_status.color','master_status.value_constant');
            if($list_type_filter==0){
                $order_query = $orders_select
                    ->distinct('orders.id')
                    ->leftJoin('transactions', 'transactions.bill_to_id', '=', 'orders.id')
                    ->statusJoin()
                    ->createdUser()
                    ->where('orders.customer_id',$customer->id);

                $query = $invoice_select
                    ->distinct('invoices.id')
                    ->statusJoin()
                    ->createdUser()
                    ->where('invoices.bill_to_id',$customer->id)
                    ->union($order_query);

            }else if($list_type_filter==1){
                $query = $orders_select
                ->distinct('orders.id')
                ->leftJoin('transactions', 'transactions.bill_to_id', '=', 'orders.id')
                ->statusJoin()
                ->createdUser()
                ->where('orders.customer_id',$customer->id)
                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('orders.created_at', 'desc');
                })
                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });
            }else if($list_type_filter==2){
                $query = $invoice_select
                ->distinct('invoices.id')
                ->statusJoin()
                ->createdUser()
                ->where('invoices.bill_to_id',$customer->id)
                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('invoices.created_at', 'desc');
                })
                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                });
            }
            $total_count =count($query->get());
            $results = $query->take($limit)
                ->skip($offset)
                ->get();
            $item_array = [];
            foreach ($results as $key => $row) {
                $order = $row->toArray($request);
                if($order['type']=='Order'){
                    $order['detail_link'] = '/order/'.$order['order_slack'];
                }else{
                    $order['detail_link'] = '/invoice/'.$order['order_slack'];
                }
                $item_array[$key][] = $order['order_id'];
                $item_array[$key][] = $order['type'];
                $item_array[$key][] = $order['number'];
                $item_array[$key][] = $order['date'];
                $item_array[$key][] = $order['amount'];
                $item_array[$key][] = (isset($order['label'])) ? view('common.status', ['status_data' => ['label' => $order['label'], "color" => $order['color']]])->render() : '-';
                $item_array[$key][] = Carbon::parse($order['created_at'])->format('d-m-Y h:i A');
                $item_array[$key][] = Carbon::parse($order['updated_at'])->format('d-m-Y h:i A');
                if($order['type']=='Order') {
                    $item_array[$key][] = view('order.layouts.order_actions', ['order' => $order])->render();
                }else{
                    $item_array[$key][] = view('invoice.layouts.invoice_actions', array('invoice' => $order))->render();
                }
            }

            $response = [
                'draw' => $draw,
                'recordsTotal' => $total_count,
                'recordsFiltered' => $total_count,
                'data' => $item_array
            ];

            return response()->json($response);
        } catch (Exception $e) {
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
    
    public function get_order_pending_payment_data($slack, $separate_api = 1)
    {
        try {

            if (!check_access(['A_DETAIL_ORDER'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $order = OrderModel::where('slack', '=', $slack)->first();

            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', trim('INCOME'))
                ->first();

            $paid_amount = TransactionModel::select('id')->where([
                ['bill_to', '=', 'POS_ORDER'],
                ['bill_to_id', '=', $order->id],
                ['transaction_type', '=', $transaction_type_data->id],
            ])->sum('amount');
            
            $order_transaction_details = TransactionModel::select('id', 'created_at', 'amount', 'payment_method', 'notes', 'transaction_date')->where([
                ['bill_to', '=', 'POS_ORDER'],
                ['bill_to_id', '=', $order->id],
                ['transaction_type', '=', $transaction_type_data->id],
            ])->orderBy('id', 'DESC')->get();

            $pending_amount = bcsub($order->total_order_amount, $paid_amount, 2);
            
            $response = [
                'total_amount' => $order->total_order_amount,
                'total_paid_amount' => $paid_amount,
                'pending_amount' => $pending_amount,
                'order_transaction_details' => $order_transaction_details,
            ];
            if($separate_api == 0){
                return $response;
            }
            return response()->json($this->generate_response(
                array(
                    "message" => "Transaction amounts calculated successfully",
                    "data" => $response,
                ),
                'SUCCESS'
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


}
