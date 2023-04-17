<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Resources\SettingAppResource;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Order as OrderModel;
use App\Models\OrderProduct as OrderProductModel;
use App\Models\Customer as CustomerModel;
use App\Models\Store as StoreModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Category as CategoryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\Account as AccountModel;
use App\Models\MasterOrderType as MasterOrderTypeModel;
use App\Models\Table as TableModel;
use App\Models\BusinessRegister as BusinessRegisterModel;
use App\Models\MasterBillingType as MasterBillingTypeModel;
use App\Models\Role as RoleModel;
use App\Models\SettingApp as SettingAppModel;
use App\Models\KeyboardShortcut as KeyboardShortcutModel;
use App\Models\Product as ProductModel;
use App\Models\User as UserModel;
use App\Models\BillingCounter as BillingCounterModel;
use App\Models\ModifierOption as ModifierOptionModel;
use App\Models\OrderProductModifierOption as OrderProductModifierOptionModel;
use Mpdf\Mpdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use App\Http\Resources\ReturnOrderResource;
use App\Models\Combo;
use App\Models\ReturnOrders as ReturnOrdersModel;
use App\Models\ReturnOrdersProducts as ReturnOrdersProductsModel;
use App\Models\Transaction as TransactionModel;
use Carbon\Carbon;

class ReturnOrder extends Controller
{
    public function __construct()
    {
    }
    //This is the function that loads the listing page
    public function index(Request $request)
    {

        //check access
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_RETURN_ORDERS';
        check_access(array($data['menu_key'], $data['sub_menu_key']));

        //available return order status
        $data['return_statuses'] = MasterStatus::select('value', 'label')->filterByKey('RETURN_STATUS')->active()->orderBy('label', 'DESC')->get();

        $data['store'] = StoreModel::select('currency_name', 'currency_code', 'store_opening_time', 'store_closing_time')
            ->where('id', $request->logged_user_store_id)
            ->first();
        $date_format = config()->get('date_format_backend');
        $data['store_opening_time'] = Carbon::parse(request()->logged_store_opening_time)->format($date_format.'\TH:i:s');
        $data['store_closing_time'] = Carbon::parse(request()->logged_store_closing_time)->format($date_format.'\TH:i:s');

        return view('return_order.return_order_list', $data);
    }

    public function detail($slack)
    {
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        $data['action_key'] = 'A_DETAIL_RETURN_ORDER';


        check_access([$data['action_key']]);

        $return_order_data = $this->get_order_data($slack);
        $productdetails = [];
        if(isset($return_order_data['damageproducts']) && count($return_order_data['damageproducts'])>0)
        {
          foreach($return_order_data['damageproducts'] as $product)
          {
              $product->price = $product->amount;
          }
        }    
        $data['return_order_data'] = $return_order_data;

        $data['print_return_order_link'] = route('return_print_order', ['slack' => $slack]);
        $data['print_return_pos_receipt_link'] = route('return_print_pos_receipt', ['slack' => $slack]);
        // $return_order_exist =FALSE; 
        // $return_order_check = ReturnOrdersModel::select('*')->where('order_slack', $slack)->first();

        // if(isset($return_order_check->id))
        // {
        //     $return_order_exist = TRUE;
        // }

        // $data['return_order_exist'] = $return_order_exist;
        // $data['delete_order_access'] = check_access(['A_DELETE_ORDER'] ,true);

        // $data['store_tax_percentage'] = "";
        // if(session('store_tax_code') != ""){
        //     $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id',session('store_tax_code'))->first()->total_tax_percentage;
        // }

        // $data['share_invoice_sms_access'] = check_access(['A_SHARE_INVOICE_SMS'] ,true);

        if(isset($data['return_order_data']['combos']) && count($data['return_order_data']['combos']) > 0){

            foreach($data['return_order_data']['combos'] as $combo){
                if(isset($combo)){
                    
                    $dataset = [];
                    $dataset['combo_id'] = $combo['combo_id'];
                    $dataset['combo_cart_id'] = $combo['combo_products'][0]['combo_cart_id'];
                    $dataset['combo_name'] = $combo['combo_name'];
                    $dataset['combo_total_price'] = format_decimal($combo['combo_total_price']);
                    $dataset['is_returned'] = ($combo['combo_products'][0]['return_quantity'] > 0) ? true : false;

                    $data['return_order_data']['products'][] = $dataset;
                    foreach($combo['combo_products'] as $combo_product){

                            $temp = $combo_product;
                            $temp['quantity'] = 1;
                            $data['return_order_data']['products'][] = $temp;
                    }
                }
                
            }
        }

        return view('return_order.return_order_detail', $data);
    }


    public function get_order_data($slack)
    {
        $data['return_order_data'] = null;

        if (isset($slack)) {

            $return_order = ReturnOrdersModel::select('order_return.*')->where('order_return.slack', $slack)
                ->first();

            if (empty($return_order)) {
                abort(404);
            }

            $return_order_data = new ReturnOrderResource($return_order);

            $return_order_data['transactions'] = array();

            $orderId = OrderModel::select('id')->where('slack', $return_order_data->order_slack)->first();
            if (isset($orderId)) {
                $return_order_data['transactions'] = TransactionModel::where('bill_to_id', $orderId->id)->where('bill_to', 'POS_ORDER')->orderBy('transaction_date', 'desc')->get();
            }

            $return_order_products_array = collect($return_order_data->products)->toArray();

            $total_qty_array = data_get($return_order_products_array, '*.quantity', 0);

            $total_quantity = array_sum($total_qty_array);
            $return_order_data = collect($return_order_data);

            $return_order_data->put('total_quantity', $total_quantity);
            $data = $return_order_data->all();
        }

        $products = [];
        $combo_cart = [];
        
        if (!empty($data['products'])) {
            foreach ($data['products'] as $order_product) {
                
                if($order_product->combo_id == null || $order_product->combo_id == 0){
                    
                    $temp = [];
                    $temp = collect($order_product);
                    $temp['is_gifted']=$order_product->is_gifted;
                    $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id', $order_product->id)
                        ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();
                    $temp->put('modifier_options', $order_product_modifier_options);
                    
                    $products[] = $temp;
                
                }else{
                    $combo_cart[$order_product['combo_cart_id']][] = $order_product; 
                }

            }
        }

        $combos = [];

        if(isset($combo_cart) && count($combo_cart) > 0 ){
            
            foreach($combo_cart as $key => $combo_cart_products){
                
                $combo = Combo::find($combo_cart_products[0]['combo_id']);
                
                $dataset = [];
                $dataset['combo_id'] = $combo->id;
                $dataset['combo_name'] = $combo->name;
                $dataset['combo_quantity'] = 1;
                $dataset['combo_total_price'] = 0;
                
                foreach($combo_cart_products as $product){
                    
                    $temp = [];
                    $temp = collect($product);
                    $temp['is_gifted']=$product['is_gifted'];
                    $order_product_modifier_options = OrderProductModifierOptionModel::join('modifier_options', 'modifier_options.id', 'order_product_modifier_options.modifier_option_id')
                        ->join('modifiers', 'modifiers.id', 'modifier_options.modifier_id')
                        ->where('order_product_modifier_options.order_product_id', $product['id'])
                        ->select('modifiers.label as modifier_label', 'modifier_options.label as modifier_option_label', 'order_product_modifier_options.modifier_option_price as modifier_option_price')
                        ->get();
                    $temp->put('modifier_options', $order_product_modifier_options);

                    $total_modifier_option_price = 0;
                    if(isset($order_product_modifier_options)){
                        foreach($order_product_modifier_options as $modifier_option){
                            $total_modifier_option_price += $modifier_option->modifier_option_price;
                        }
                    }
                    $dataset['combo_products'][] = $temp;
                    $dataset['combo_total_price'] += (float) $product['sale_amount_excluding_tax'] + $total_modifier_option_price;
                    
                }
                $combos[] = $dataset;
                
            }

        }
        
        $data['combos'] = $combos;
        $data['products'] = $products;

        return $data;
    }

    public function print_order(Request $request, $slack, $type = 'INLINE', $invoice_print_type = null)
    {

        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access([$data['sub_menu_key']]);

        $order_data = $this->get_order_data($slack);
        if (in_array($order_data['status']['value'], [1, 5]) != 1) {
            abort(404);
        }

        if ($invoice_print_type == null) {
            $invoice_print_type = $order_data['store']['invoice_type'];
        }


        switch ($invoice_print_type) {
            case 'A4':
                $view_file = 'order.invoice.a4_print';
                $css_file = 'css/order_a4_print_invoice.css';
                $format = 'A4';
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL':
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                $print_logo_path = session('store_logo');
                break;
            case 'SMALL_LITE':
                $view_file = 'order.invoice.thermal_print_lite';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
            default:
                $view_file = 'order.invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                $print_logo_path = session('store_logo');
                break;
        }

        if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/'. session('merchant_id') .'/store/' . $print_logo_path))) {
            $print_logo_path = public_path('/storage/'. session('merchant_id') .'/store/' . $print_logo_path);
        } else {
            $print_logo_path = public_path('images/logo.png');
        }

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
        $filename = 'order_' . $order_data['order_number'] . '.pdf';

        if ($type == 'INLINE') {

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
            $mpdf->Output($upload_dir . $filename, 'F');
            $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
        } else {

            Storage::disk('order')->delete(
                [
                    $filename
                ]
            );

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();

            $mpdf->Output($upload_dir . $filename, \Mpdf\Output\Destination::FILE);

            $download_link = $view_path . $filename;
            return $download_link;
        }
    }


    public function print_pos_receipt(Request $request, $slack)
    {

        $this->return_print_pos_receipt($request, $slack, 'INLINE', 'SMALL');
    }

    public function return_print_pos_receipt(Request $request, $slack, $type = 'INLINE', $invoice_print_type = null)
    {
        $data['menu_key'] = 'MM_ORDERS';
        $data['sub_menu_key'] = 'SM_POS_ORDERS';
        check_access([$data['sub_menu_key']]);

        $order_data = $this->get_order_data($slack);
        
        if (in_array($order_data['status']['value'], [1, 5]) != 1) {
            abort(404);
        }

        if ($invoice_print_type == null) {
            $invoice_print_type = $order_data['store']['invoice_type'];
        }

        if (session()->has('merchant_id')) {
            if (session('merchant_id') == '') {
                // default logo
                $print_logo_path = public_path('/images/logo.png');
            } else {
                // for logged in users
                // $print_logo_path = asset('storage/'.session('merchant_id').'/store/'.session('store_logo'));
                $print_logo_path =  public_path('storage/' . session('merchant_id') . '/store/' . $order_data['store']['store_logo']); //asset('storage/' . session('merchant_id') . '/store/' . $order_data['store']['store_logo']);
            }
        } else {
            // for logged out users (public url)
            $print_logo_path = public_path('storage/' . config('constants.config.merchant_id') . '/store/' . $order_data['store']['store_logo']);
        }

        
        switch ($invoice_print_type) {
            case 'A4':
                $view_file = 'order.invoice.a4_print';
                $css_file = 'css/order_a4_print_invoice.css';
                $format = 'A4';
                break;
            case 'SMALL':
                $view_file = 'order.return_invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 150];
                break;
            case 'SMALL_LITE':
                $view_file = 'order.invoice.thermal_print_lite';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                break;
            default:
                $view_file = 'order.return_invoice.thermal_print';
                $css_file = 'css/order_thermal_print_invoice.css';
                $format = [78, 297];
                break;
        }

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
        $filename = 'order_' . $order_data['order_number'] . '.pdf';

        if ($type == 'INLINE') {

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();
            $mpdf->Output($upload_dir . $filename, 'F');
            $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
        } else {

            Storage::disk('order')->delete(
                [
                    $filename
                ]
            );

            $view_path = Config::get('constants.upload.order.view_path');
            $upload_dir = Storage::disk('order')->getAdapter()->getPathPrefix();

            $mpdf->Output($upload_dir . $filename, \Mpdf\Output\Destination::FILE);

            $download_link = $view_path . $filename;
            return $download_link;
        }
    }
}