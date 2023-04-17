<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Role as RoleModel;
use App\Models\User as UserModel;

use App\Models\Supplier as SupplierModel;
use App\Models\Category as CategoryModel;
use App\Models\Taxcode as TaxcodeModel;
use App\Models\TaxcodeType as TaxcodeTypeModel;
use App\Models\Discountcode as DiscountcodeModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Account as AccountModel;
use App\Models\PaymentMethod as PaymentMethodModel;
use App\Models\Store as StoreModel;
use App\Models\UserStore as UserStoreModel;
use App\Models\Product as ProductModel;
use App\Models\DamageReportModel;
use Illuminate\Support\Carbon;
use Stripe\TaxCode;

class Report extends Controller
{
    public function index(Request $request){
        
        //check access permission
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_DOWNLOAD_REPORT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        //user
        $data['user_statuses'] = MasterStatus::select('value', 'label')->filterByKey('USER_STATUS')->active()->sortValueAsc()->get();

        $data['roles'] = RoleModel::select('slack', 'role_code', 'label')->resolveSuperAdminRole()->active()->sortLabelAsc()->get();

        $data['users'] = UserModel::select('slack', 'user_code', 'fullname')->active()->orderBy('fullname','ASC')->get();
        
        $data['products'] = ProductModel::all();
        //product
        $data['product_statuses'] = MasterStatus::select('value', 'label')->filterByKey('PRODUCT_STATUS')->active()->sortValueAsc()->get();

        $data['suppliers'] = SupplierModel::select('slack', 'supplier_code', 'name')->sortNameAsc()->get();

        $data['categories'] = CategoryModel::select('slack', 'category_code', 'label')->sortLabelAsc()->get();

        $data['taxcodes'] = TaxcodeModel::select('slack', 'tax_code', 'label')->sortLabelAsc()->get();

        $currentdate = date('Y-m-d H:i:sa');
        $data['discountcodes'] = DiscountcodeModel::select('slack', 'discount_code', 'label')
        ->whereRaw("discounttype!='cashier'")
        ->whereRaw("('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)")
        ->whereRaw("(limit_on_discount=-1 OR limit_on_discount>0)")
        ->sortLabelAsc()->active()->get();

        //order
        $data['order_statuses'] = MasterStatus::select('value', 'label')->filterByKey('ORDER_STATUS')->active()->sortValueAsc()->get();

        //purchase order
        $data['purchase_order_statuses'] = MasterStatus::select('value', 'label')->filterByKey('PURCHASE_ORDER_STATUS')->active()->sortValueAsc()->get();

        //store
        $data['store_statuses'] = MasterStatus::select('value', 'label')->filterByKey('STORE_STATUS')->active()->sortValueAsc()->get();

        //customer
        $data['customer_statuses'] = MasterStatus::select('value', 'label')->filterByKey('CUSTOMER_STATUS')->active()->sortValueAsc()->get();

        //category
        $data['category_statuses'] = MasterStatus::select('value', 'label')->filterByKey('CATEGORY_STATUS')->active()->sortValueAsc()->get();

        //supplier
        $data['supplier_statuses'] = MasterStatus::select('value', 'label')->filterByKey('SUPPLIER_STATUS')->active()->sortValueAsc()->get();

        //tax code
        $data['taxcode_statuses'] = MasterStatus::select('value', 'label')->filterByKey('TAX_CODE_STATUS')->active()->sortValueAsc()->get();

        //discount code
        $data['discountcode_statuses'] = MasterStatus::select('value', 'label')->filterByKey('DISCOUNTCODE_STATUS')->active()->sortValueAsc()->get();
        
        //invoice code
        $data['invoice_statuses'] = MasterStatus::select('value', 'label')->filterByKey('INVOICE_STATUS')->active()->sortValueAsc()->get();

        //quotation code
        $data['quotation_statuses'] = MasterStatus::select('value', 'label')->filterByKey('QUOTATION_STATUS')->active()->sortValueAsc()->get();
        
        //inventory
        $data['inventory_statuses'] = MasterStatus::select('value', 'label')->filterByKey('INVENTORY_STATUS')->active()->sortValueAsc()->get();

        //transaction
        $data['transaction_types'] = MasterTransactionTypeModel::select('transaction_type_constant', 'label')->active()->get();
        $data['accounts'] = AccountModel::select('accounts.slack', 'accounts.label', 'master_account_type.label as account_type_label')->masterAccountTypeJoin()->active()->get();
        $data['payment_methods'] = PaymentMethodModel::select('slack', 'label')->active()->get();

        $data['stores'] = StoreModel::select('id','name')->active()->get();

        $data['logged_in_store_id'] = session('store_id');
        
        return view('report.report', $data);
    }

    
    public function best_seller_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_BEST_SELLER';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('report.best_seller_report', $data);
    }

    public function day_wise_sale_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_DAY_WISE_SALE';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('report.day_wise_sale_report', $data);
    }

    public function catgeory_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_CATEGORY_REPORT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        $data['store'] = StoreModel::select('currency_name', 'currency_code')
        ->where('id', $request->logged_user_store_id)
        ->first();

        return view('report.catgeory_report', $data);
    }

    public function damage_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_DAMAGE_REPORT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        $data['store'] = DamageReportModel::select('*')
        ->where('branch_reference', $request->logged_user_store_code)
        ->first();

        $data['order_start_date'] = (new \DateTime(Carbon::now()))->format('d-m-Y');
        $data['order_end_date'] = (new \DateTime(Carbon::now()))->format('d-m-Y');
        return view('report.damage_orders', $data);
    }

    public function product_quantity_alert(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_PRODUCT_QUANTITY_ALERT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('report.product_quantity_alert', $data);
    }

    public function store_stock_chart(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_STORE_STOCK_CHART';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        $data['store'] = StoreModel::select('currency_name', 'currency_code')
        ->where('id', $request->logged_user_store_id)
        ->first();
        
        return view('report.store_stock_chart', $data);
    }

    public function taxReturnReport(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_TAX_RETURN_REPORT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        $min_created_date = DB::select("select min(date(created_at)) min_created_date from purchase_orders");
        if(!empty($min_created_date)){
            $min_created_date = $min_created_date[0]->min_created_date;
            if (strtotime($min_created_date) !== false){
                $min_created_date = date('Y', strtotime($min_created_date));
            }else{
                $min_created_date = date('Y');
            }
        }
        //dd($purchase_details);
        $data['min_created_year'] = $min_created_date;
        // $data['user_statuses'] = MasterStatus::select('value', 'label')->filterByKey('USER_STATUS')->active()->sortValueAsc()->get();
        return view('report.tax_return_report', $data);
    }

    public function inventory_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_INVENTORY';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        if($request->logged_user_id == 1){
            $data['all_store_names'] = StoreModel::active()->get();
        }else{
            $data['all_store_names'] = UserStoreModel::select('stores.*')->where('user_id', $request->logged_user_id)->storeData()->get();
        }
        
        return view('report.inventory_report', $data);
    }

    public function payment_report(Request $request){
        //check access
        $data['menu_key'] = 'MM_REPORT';
        $data['sub_menu_key'] = 'SM_PAYMENT';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        return view('report.payment_report', $data);
    }
    
}