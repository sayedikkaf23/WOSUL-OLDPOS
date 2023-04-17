<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use App\Models\MasterStatus;
use App\Models\QuantityPurchase as QuantityPurchaseModel;
use App\Models\Country as CountryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Store;
use App\Models\Product;
use App\Models\Taxcode as TaxcodeModel;

use App\Http\Resources\QuantityPurchaseResource;

use Mpdf\Mpdf;

class QuantityPurchase extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){

        //check access
        $data['menu_key'] = 'MM_PURCHASE';
        $data['sub_menu_key'] = 'SM_QUANTITY_PURCHASE';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('quantity_purchase.quantity_purchases', $data);
    }

    // added by chandan
    public function add_quantity_purchase($slack = null){

        $data['menu_key'] = 'MM_PURCHASE';
        $data['sub_menu_key'] = 'SM_QUANTITY_PURCHASE';
        $data['action_key'] = ($slack == null) ? 'A_ADD_QUANTITY_PURCHASE' : 'A_EDIT_QUANTITY_PURCHASE';
        check_access(array($data['action_key']));

        $data['main_categories'] = Category::active()->parentCategory()->sortLabelAsc()->get();
        $data['suppliers'] = Supplier::active()->sortNameAsc()->get();
        $data['stores'] = Store::active()->get();

        if($slack!=null)
        {
            $po_number = QuantityPurchaseModel::select('po_number')->where("slack",$slack)->first();
            $po_number = isset($po_number->po_number)?$po_number->po_number:1;
            $data['po_number'] = $po_number;
        }
        else
        {
          $next_po_number = QuantityPurchaseModel::max('po_number');
          if(isset($next_po_number) && $next_po_number != ""){
            $data['po_number'] = $next_po_number + 1;
          }else{
            $data['po_number'] = 1;
          }
        }

        $purchase_policy_information = Store::find(session('store_id'));
        if($purchase_policy_information){
            $data['purchase_policy_information'] = $purchase_policy_information->purchase_policy_information;
        }else{
            $data['purchase_policy_information'] = '';
        }

        $data['store_tax_percentage'] = "";
        if(session('store_tax_code') != "" && (int)session('store_tax_code')>0){
            $data['store_tax_percentage'] = TaxcodeModel::withoutGlobalScopes()->where('id',session('store_tax_code'))->first()->total_tax_percentage;
        }

        $data['quantity_purchase_data'] = null;
        if(isset($slack)){
            
            $purchase_order = QuantityPurchaseModel::where('slack', '=', $slack)->first();
            if (empty($purchase_order)) {
                abort(404);
            }
 
            $quantity_purchase_data = new QuantityPurchaseResource($purchase_order);
            $data['quantity_purchase_data'] = $quantity_purchase_data;

        }

        $data['merchant_id'] = session('merchant_id');

        return view('quantity_purchase.add_quantity_purchase', $data);

    }


    //This is the function that loads the detail page
    public function detail($slack){

        $data['menu_key'] = 'MM_PURCHASE';
        $data['sub_menu_key'] = 'SM_QUANTITY_PURCHASE';
        $data['action_key'] = 'A_DETAIL_QUANTITY_PURCHASE';
        check_access([$data['action_key']]);

        $purchase_order = QuantityPurchaseModel::where('slack', '=', $slack)->first();
        
        if (empty($purchase_order)) {
            abort(404);
        }

        $quantity_purchase_data = new QuantityPurchaseResource($purchase_order);

        $data['quantity_purchase_data'] = $quantity_purchase_data;

        $po_statuses = [];
        
        if(check_access(['A_EDIT_STATUS_QUANTITY_PURCHASE'] ,true)){
            $po_statuses = MasterStatusModel::select('label','value_constant')->where([
                ['value_constant', '!=', strtoupper('CREATED')],
                ['key', '=', 'QUANTITY_PURCHASE_STATUS'],
                ['status', '=', '1']
            ])->active()->orderBy('value', 'asc')->get();
        }

        $data['po_statuses'] = $po_statuses;

        $data['delete_po_access'] = check_access(['A_DELETE_QUANTITY_PURCHASE'] ,true);

        $data['create_invoice_from_po_access'] = check_access(['A_CREATE_INVOICE_FROM_PO'] ,true);

        return view('quantity_purchase.quantity_purchase_detail', $data);
    }


    public function products(Request $request){

        $products = Product::with('product_images')->where('category_id',$request->sub_category_id)->get();
        return response()->json($products);
    }

    //This is the function that loads the print purchase order page
    public function print_quantity_purchase(Request $request, $slack){

        $data['menu_key'] = 'MM_PURCHASE';
        $data['sub_menu_key'] = 'SM_QUANTITY_PURCHASE';
        check_access([$data['sub_menu_key']]);

        $quantity_purchase = QuantityPurchaseModel::where('slack', '=', $slack)->first();
        
        if (empty($quantity_purchase)) {
            abort(404);
        }

        $quantity_purchase_data = new QuantityPurchaseResource($quantity_purchase);

        $print_logo_path = session('store_logo');

        if (isset($print_logo_path) && $print_logo_path != "" && File::exists(public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path))) 
        {
            $print_logo_path = public_path('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
            //$print_logo_path = asset('/storage/' . session('merchant_id') . '/store/' . $print_logo_path);
        } 
        else 
        {
            $print_logo_path = public_path('images/logo.png');
        }
       
        $print_data = view('quantity_purchase.invoice.po_print', ['data' => json_encode($quantity_purchase_data), 'logo_path' => $print_logo_path])->render();

        $mpdf_config = [
            'mode'          => 'utf-8',
            'format'        => 'A4',
            'orientation'   => 'P',
            'margin_left'   => 7,
            'margin_right'  => 7,
            'margin_top'    => 7,
            'margin_bottom' => 7,
            'tempDir' => storage_path()."/pdf_temp" 
        ];

        $stylesheet = File::get(public_path('css/purchase_order_print_invoice.css'));
        $mpdf = new Mpdf($mpdf_config);
        $mpdf->curlAllowUnsafeSslRequests = true;
        $mpdf->SetDisplayMode('real');
        $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->SetHTMLFooter('<div class="footer">Page: {PAGENO}/{nb}</div>');
        $mpdf->WriteHTML($print_data);
        $mpdf->Output('purchase_order_'.$quantity_purchase_data['po_number'].'.pdf', \Mpdf\Output\Destination::INLINE);

        //return view('purchase_order.invoice.po_print', ['data' => json_encode($purchase_order_data)]);
    }
    
}
