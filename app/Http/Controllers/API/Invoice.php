<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoiceReturnResource;

use App\Models\Invoice as InvoiceModel;
use App\Models\InvoiceProduct as InvoiceProductModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Customer as CustomerModel;
use App\Models\Product as ProductModel;
use App\Models\Country as CountryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\Transaction as TransactionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\InvoiceCharge as InvoiceChargeModel;
use App\Models\InvoiceReturn as InvoiceReturnModel;
use App\Models\InvoiceReturnProducts as InvoiceReturnProductsModel;
use App\Models\Store as StoreModel;
use App\Models\HRM\AccTransaction as AccTransactionModel;
use App\Models\HRM\AccCoa as AccCoaModel;

use App\Http\Traits\CommonApiTrait;
use App\Http\Traits\QoyodApiTrait;

use App\Http\Resources\Collections\InvoiceCollection;
use App\Models\Taxcode;
use App\Models\Quotation as QuotationModel;
use App\Http\Resources\QuotationResource;
use App\Models\PaymentMethod as PaymentMethodModel;
class Invoice extends Controller
{

    use CommonApiTrait,QoyodApiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {

            $data['action_key'] = 'A_VIEW_INVOICE_LISTING';
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

            $query = InvoiceModel::select('invoices.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
                ->take($limit)
                ->skip($offset)
                ->statusJoin()
                ->createdUser()

                ->when($order_by_column, function ($query, $order_by_column) use ($order_direction) {
                    $query->orderBy($order_by_column, $order_direction);
                }, function ($query) {
                    $query->orderBy('created_at', 'desc');
                })

                ->when($filter_string, function ($query, $filter_string) use ($filter_columns) {
                    $query->where(function ($query) use ($filter_string, $filter_columns) {
                        foreach ($filter_columns as $filter_column) {
                            $query->orWhere($filter_column, 'like', '%' . $filter_string . '%');
                        }
                    });
                })

                ->get();
              
            $invoices = InvoiceResource::collection($query);

            $total_count = InvoiceModel::select("id")->get()->count();

            $item_array = [];


            foreach ($invoices as $key => $invoice) {

                $invoice = $invoice->toArray($request);

                $total_amount_including_tax =  $invoice['subtotal_including_tax'];
                $invoice_api = new Invoice();
                
                $total_invoice_charges = $invoice_api->get_total_invoice_charge($invoice['id']);

                $total_invoice_amount = $total_amount_including_tax + $total_invoice_charges;


                $item_array[$key][] = $invoice['invoice_number'];
                $item_array[$key][] = ($invoice['invoice_reference'] != '') ? $invoice['invoice_reference'] : '-';
                $item_array[$key][] = ($invoice['bill_to'] != '') ? $invoice['bill_to'] : '-';
                $item_array[$key][] = ($invoice['bill_to_name'] != '') ? $invoice['bill_to_name'] : '-';
                $item_array[$key][] = ($invoice['invoice_date'] != '') ? $invoice['invoice_date'] : '-';
                $item_array[$key][] = ($invoice['invoice_due_date'] != '') ? $invoice['invoice_due_date'] : '-';
                // $item_array[$key][] = $invoice['total_order_amount'];
                $item_array[$key][] = $total_invoice_amount;
                $item_array[$key][] = (isset($invoice['status']['label'])) ? view('common.status', ['status_data' => ['label' => $invoice['status']['label'], "color" => $invoice['status']['color']]])->render() : '-';
                $item_array[$key][] = $invoice['created_at_label'];
                $item_array[$key][] = $invoice['updated_at_label'];
                $item_array[$key][] = (isset($invoice['created_by']) && isset($invoice['created_by']['fullname'])) ? $invoice['created_by']['fullname'] : '-';
                $item_array[$key][] = view('invoice.layouts.invoice_actions', array('invoice' => $invoice))->render();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_INVOICE'], true) && !check_access(['A_CREATE_INVOICE_FROM_PO'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $invoice_data = $this->form_invoice_array($request);
            
            if (!empty($invoice_data['invoice_data'])) {

                $invoice = $invoice_data['invoice_data'];

                $invoice_slack = $invoice['slack'] = $this->generate_slack("invoices");
                $invoice['invoice_number'] = $this->getLastInvoiceNumber() + 1;
                $invoice['created_at'] = now();
                $invoice['created_by'] = $request->logged_user_id;

                $invoice_id = InvoiceModel::create($invoice)->id;

                //echo $invoice_id;die;

                $code_start_config = Config::get('constants.unique_code_start.invoice');
                $code_start = (isset($code_start_config)) ? $code_start_config : 100;

                /*$invoice_number = [
                    "invoice_number" =>  $this->getNextInvoiceNumber()
                ];
                InvoiceModel::where('id', $invoice_id)
                    ->update($invoice_number);*/
            }

            if (!empty($invoice_data['invoice_products'])) {

                $invoice_products = $invoice_data['invoice_products'];

                array_walk($invoice_products, function (&$item, $key) use ($invoice_id, $request) {

                    if (isset($item['product_id'])) {
                        $quantity = 0;
                        $product = ProductModel::find($item['product_id']);
                        if ($product['quantity'] != -1.00) {
                            $quantity = $item['quantity'];
                            // Add quantity history

                            $this->addQuantityHistory($this->generate_slack('quantity_history'),$item['product_id'],request()->logged_user_store_id,'INVOICE','DECREMENT',$item['quantity'],$item['product_id']);
                        }
                        $product->decrement('quantity',$quantity);

                        $item['show_description_in'] = $product['show_description_in'];

                        $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity']);
                    }


                    $item['slack'] = $this->generate_slack("invoice_products");
                    $item['invoice_id'] = $invoice_id;
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;


                    InvoiceProductModel::insert($item);
                });
            }

            if (!empty($request->invoice_charges)) {

                $invoice_charges = json_decode($request->invoice_charges);
                foreach ($invoice_charges as $rs) {
                    $dataset['slack'] = $this->generate_slack("invoice_charges");
                    $dataset['invoice_id'] = $invoice_id;
                    $dataset['name'] = $rs->name;
                    $dataset['amount'] = $rs->amount;

                    InvoiceChargeModel::create($dataset);
                }
            }

            //Qoyod
            if(Session('qoyod_status')){
                $invoice_detail = InvoiceModel::where('id',$invoice_id)->first();
                $this->qoyod_create_invoice($invoice_detail,'Invoice');
            }
            DB::commit();

            $forward_link = asset('storage/' . config('constants.config.merchant_id') . '/invoice/temp_invoice_' . $invoice_slack . '.pdf');

            $this->record_invoice_transaction_hrm($invoice_slack, $forward_link);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Invoice created successfully"),
                    "data"    => $invoice['slack']
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
    
    /* Create Invoice bill from Quotation */
    public function quotationToInvoice(Request $request,$slack)
    {
        try {
            
            if (!check_access(['A_ADD_INVOICE'], true) && !check_access(['A_CREATE_INVOICE_FROM_PO'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            // $this->validate_request($request);
            $quotation = QuotationModel::where('slack', '=', $slack)->first();
            if (empty($quotation)) {
                abort(404);
            }

            // Will not allow to create duplicate invoice
            $existing_invoice = InvoiceModel::where('slack', $quotation->converted_invoice_slack)->exists();
            if ($existing_invoice == true) {
                throw new Exception(trans("Invoice is already created!"), 400);
            }

            $quotation_data = new QuotationResource($quotation);
            $quotation_data['creation_type'] = 'quotation_to_invoice';
            DB::beginTransaction();
            $quotation_data->currency = $quotation_data->currency_code;
            $quotation_data->logged_user_store_id = $request->logged_user_store_id;;
            $quotation_data->logged_user_id = $request->logged_user_id;
            $quotation_data->invoice_charges = null;
            $quotation_data->invoice_tax_amount = $quotation_data->total_tax_amount;
            $quotation_data->tax_option = $quotation_data->tax_option_id;
            $quotation_data->invoice_date = date('Y-m-d');
            $quotation_data->invoice_due_date = $quotation_data->quotation_due_date;
            $quotation_data->invoice_reference = $this->generate_reference_no($request->logged_user_store_id);
            $customer_data = CustomerModel::select('slack')->where('id', '=', $quotation_data->bill_to_id)->active()->first();
            if(!empty($customer_data) && isset($customer_data->slack)){
                $quotation_data->bill_to_slack = $customer_data->slack;
            }else{
                $quotation_data->bill_to_slack = '';
            }
            $invoice_data = $this->form_invoice_array($quotation_data);
            
            if (!empty($invoice_data['invoice_data'])) {

                $invoice = $invoice_data['invoice_data'];

                $invoice_slack = $invoice['slack'] = $this->generate_slack("invoices");
                $invoice['invoice_number'] = $this->getLastInvoiceNumber() + 1;
                $invoice['created_at'] = now();
                $invoice['created_by'] = $request->logged_user_id;

                $invoice_id = InvoiceModel::create($invoice)->id;

                $code_start_config = Config::get('constants.unique_code_start.invoice');
                $code_start = (isset($code_start_config)) ? $code_start_config : 100;

                /*$invoice_number = [
                    "invoice_number" =>  $this->getNextInvoiceNumber()
                ];
                InvoiceModel::where('id', $invoice_id)
                    ->update($invoice_number);*/
            }

            if (!empty($invoice_data['invoice_products'])) {

                $invoice_products = $invoice_data['invoice_products'];

                array_walk($invoice_products, function (&$item, $key) use ($invoice_id, $request) {

                    if (isset($item['product_id'])) {
                        $quantity = 0;
                        $product = ProductModel::find($item['product_id']);
                        if ($product['quantity'] != -1.00) {
                            $quantity = $item['quantity'];
                            // Add quantity history

                            $this->addQuantityHistory($this->generate_slack('quantity_history'),$item['product_id'],request()->logged_user_store_id,'INVOICE','DECREMENT',$item['quantity'],$item['product_id']);
                        }
                        $product->decrement('quantity',$quantity);

                        $item['show_description_in'] = $product['show_description_in'];

                        $this->update_ingredient_quantity($request, $item['product_id'], $item['quantity']);
                    }


                    $item['slack'] = $this->generate_slack("invoice_products");
                    $item['invoice_id'] = $invoice_id;
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;


                    InvoiceProductModel::insert($item);
                });
            }

            if (!empty($request->invoice_charges)) {

                $invoice_charges = json_decode($request->invoice_charges);
                foreach ($invoice_charges as $rs) {
                    $dataset['slack'] = $this->generate_slack("invoice_charges");
                    $dataset['invoice_id'] = $invoice_id;
                    $dataset['name'] = $rs->name;
                    $dataset['amount'] = $rs->amount;

                    InvoiceChargeModel::create($dataset);
                }
            }

            // Update the quotation table with invoice ID for reference.
            QuotationModel::where('slack', '=', $slack)->update(['converted_invoice_slack' => $invoice_slack]);

            //Qoyod
            if(Session('qoyod_status')){
                $invoice_detail = InvoiceModel::where('id',$invoice_id)->first();
                $this->qoyod_create_invoice($invoice_detail,'Invoice');
            }
            DB::commit();

            $forward_link = asset('storage/' . config('constants.config.merchant_id') . '/invoice/temp_invoice_' . $invoice_slack . '.pdf');

            $this->record_invoice_transaction_hrm($invoice_slack, $forward_link);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Invoice created successfully"),
                    "data"    => $invoice['slack']
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_service(Request $request)
    {
        
        $request->bill_to = 'CUSTOMER';
        $request->bill_to_slack = 'WriteCustomer';
        $request->write_customer_name = $request->write_customer_name;
        $request->currency = 'SAR';
        $request->tax_option = 0;

        try {

            if (!check_access(['A_ADD_INVOICE'], true) && !check_access(['A_CREATE_INVOICE_FROM_PO'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            DB::beginTransaction();

            $invoice_data = $this->form_invoice_service_array($request);
            
            // dd($invoice_data);
            if (!empty($invoice_data['invoice_data'])) {

                $invoice = $invoice_data['invoice_data'];

                $invoice_slack = $invoice['slack'] = $this->generate_slack("invoices");
                $invoice['invoice_number'] = $this->getLastInvoiceNumber() + 1;
                $invoice['created_at'] = now();
                $invoice['created_by'] = $request->logged_user_id;

                $invoice = InvoiceModel::create($invoice);
                $invoice_id = $invoice->id;


            }

            if (!empty($invoice_data['invoice_services'])) {

                $invoice_services = $invoice_data['invoice_services'];

                array_walk($invoice_services, function (&$item, $key) use ($invoice_id, $request) {

                    if (isset($item['product_id'])) {
                        $quantity = 0;
                        $product = ProductModel::find($item['product_id']);
                        if ($product['quantity'] != -1.00) {
                            $quantity = $item['quantity'];
                            // Add quantity history
                            $this->addQuantityHistory($this->generate_slack('quantity_history'),$item['product_id'],request()->logged_user_store_id,'INVOICE','DECREMENT',$item['quantity'],$item['product_id']);
                        }

                        $item['show_description_in'] = $product['show_description_in'];

                    }


                    $item['slack'] = $this->generate_slack("invoice_products");
                    $item['invoice_id'] = $invoice_id;
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;

                    InvoiceProductModel::insert($item);
                });
            }

            if (!empty($request->invoice_charges)) {

                $invoice_charges = json_decode($request->invoice_charges);
                foreach ($invoice_charges as $rs) {
                    $dataset['slack'] = $this->generate_slack("invoice_charges");
                    $dataset['invoice_id'] = $invoice_id;
                    $dataset['name'] = $rs->name;
                    $dataset['amount'] = $rs->amount;

                    InvoiceChargeModel::create($dataset);
                }
            }
            
            $invoice_data = new InvoiceResource($invoice);
            $store_detail = StoreModel::find($invoice->store_id);
            
            $total_including_charges = $invoice_data->total_after_discount;
            foreach($invoice_data->invoiceCharges as $rs){
                $total_including_charges += $rs->amount;
            }
            
            DB::commit();

            $qrcode = \Salla\ZATCA\GenerateQrCode::fromArray([
                new \Salla\ZATCA\Tags\Seller(($store_detail->tax_registration_name != '') ? $store_detail->tax_registration_name : $store_detail->name),      
                new \Salla\ZATCA\Tags\TaxNumber($store_detail->vat_number),
                new \Salla\ZATCA\Tags\InvoiceDate($invoice_data->invoice_date_iso),
                new \Salla\ZATCA\Tags\InvoiceTotalAmount($total_including_charges + $invoice_data->total_tax_amount),
                new \Salla\ZATCA\Tags\InvoiceTaxAmount( (float) $invoice_data->total_tax_amount) 
            ])->toBase64();
            
            $forward_link = asset('storage/' . config('constants.config.merchant_id') . '/invoice/temp_invoice_' . $invoice_slack . '.pdf');

            $this->record_invoice_transaction_hrm($invoice_slack, $forward_link);

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Services for Invoice added successfully"),
                    "data"    => $invoice_data,
                    "qr_code" => $qrcode,
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
     * Display the specified resource.
     *
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function show($slack)
    {
        try {

            if (!check_access(['A_DETAIL_INVOICE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = InvoiceModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new InvoiceResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoice loaded successfully",
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

            if (!check_access(['A_VIEW_INVOICE_LISTING'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $list = new InvoiceCollection(InvoiceModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoices loaded successfully",
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
     * @param  int  $slack
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slack)
    {


        try {

            if (!check_access(['A_EDIT_INVOICE'], true)) {
                throw new Exception("Invalid request", 400);
            }


            $this->validate_request($request);

            $invoice_details = InvoiceModel::where('slack', $slack)->first();

            DB::beginTransaction();

            $request->invoice_slack = $slack;

            $invoice_data = $this->form_invoice_array($request);

            if (!empty($invoice_data['invoice_data'])) {

                $invoice = $invoice_data['invoice_data'];

                $invoice['updated_at'] = now();
                $invoice['updated_by'] = $request->logged_user_id;

                $action_response = InvoiceModel::where('slack', $slack)
                    ->update($invoice);
            }

            $invoice_id = $invoice_details->id;

            if (!empty($invoice_data['invoice_products'])) {

                if (count($invoice_data['invoice_products']) > 0) {
                    InvoiceProductModel::where('invoice_id', $invoice_id)->delete();
                }

                $invoice_products = $invoice_data['invoice_products'];

                array_walk($invoice_products, function (&$item, $key) use ($invoice_id, $request) {

                    $item['slack'] = $this->generate_slack("invoice_products");
                    $item['invoice_id'] = $invoice_id;
                    $item['updated_at'] = now();
                    $item['updated_by'] = $request->logged_user_id;

                    InvoiceProductModel::insert($item);
                });
            }

            if (!empty($request->invoice_charges)) {

                // Deleting old records
                InvoiceChargeModel::where('invoice_id', $invoice_id)->delete();
                $invoice_charges = json_decode($request->invoice_charges);

                foreach ($invoice_charges as $rs) {
                    $dataset['slack'] = $this->generate_slack("invoice_charges");
                    $dataset['invoice_id'] = $invoice_id;
                    $dataset['name'] = $rs->name;
                    $dataset['amount'] = $rs->amount;
                    InvoiceChargeModel::create($dataset);
                }
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Invoice updated successfully"),
                    "data"    => $invoice_details['slack']
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slack)
    {
        try {

            if (!check_access(['A_DELETE_INVOICE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $invoice_detail = InvoiceModel::select('id')->where('slack', $slack)->first();
            if (empty($invoice_detail)) {
                throw new Exception(trans("Invalid invoice provided"), 400);
            }
            $invoice_id = $invoice_detail->id;

            DB::beginTransaction();

            TransactionModel::where([
                ['bill_to', '=', 'INVOICE'],
                ['bill_to_id', '=', $invoice_id],
            ])->delete();
            InvoiceProductModel::where('invoice_id', $invoice_id)->delete();
            InvoiceModel::where('id', $invoice_id)->delete();

            DB::commit();

            $forward_link = route('invoices');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Invoice deleted successfully"),
                    "data" => $slack,
                    "link" => $forward_link
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

    public function update_invoice_status(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_STATUS_INVOICE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $status_constant = $request->status;

            $invoice_details = InvoiceModel::where('slack', $slack)->first();
            if (empty($invoice_details)) {
                throw new Exception("Invalid Invoice selected");
            }

            $invoice_status = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status_constant)],
                ['key', '=', 'INVOICE_STATUS'],
                ['status', '=', '1']
            ])->active()->first();
            if (empty($invoice_status)) {
                throw new Exception("Invalid status provided");
            }

            DB::beginTransaction();

            $invoice = [];
            $invoice['status'] = $invoice_status->value;
            $invoice['updated_at'] = now();
            $invoice['updated_by'] = $request->logged_user_id;

            $action_response = InvoiceModel::where('slack', $slack)
                ->update($invoice);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoice status changed successfully",
                    "data"    => $invoice_details->slack
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

    public function form_invoice_array($request)
    {

        $bill_to_id = "";
        $bill_to_code = "";
        $bill_to_name = "";
        $bill_to_email = "";
        $bill_to_contact = "";
        $bill_to_address = "";
        $invoice_slack = $request->invoice_slack;

        $products = $request->products;

        if (empty((array) $products)) {
            throw new Exception(trans("Product list cannot be empty"));
        }

        if ($request->bill_to == 'SUPPLIER') {

            if ($request->bill_to_slack == 'DEFAULT') {

                $bill_to_id = "";
                $bill_to_code = "";
                $bill_to_name = "";
                $bill_to_email = "";
                $bill_to_contact = "";
                $bill_to_address = "";
            
            } else {

                $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'email', 'phone', 'address', 'pincode')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Invalid Customer selected"), 400);
                }

                $bill_to_id = $supplier_data->id;
                $bill_to_code = $supplier_data->supplier_code;
                $bill_to_name = $supplier_data->name;
                $bill_to_email = $supplier_data->email;
                $bill_to_contact = $supplier_data->phone;
                $bill_to_address = $supplier_data->address . ',' . $supplier_data->pincode;
            }
        } else if ($request->bill_to == 'COMPANY' || ($request->bill_to == 'CUSTOMER' && trim($request->bill_to_slack) != "" && trim($request->bill_to_slack) != "WriteCustomer")) {
            $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                ->where('slack', '=', trim($request->bill_to_slack))
                ->active()
                ->first();
            if (empty($customer_data)) {
                throw new Exception(trans("Invalid customer selected"), 400);
            }

            $bill_to_id = $customer_data->id;
            $bill_to_code = '';
            $bill_to_name = $customer_data->name;
            $bill_to_email = $customer_data->email;
            $bill_to_contact = $customer_data->phone;
            $bill_to_address = $customer_data->address;
        }else if ($request->bill_to == 'COMPANY' || ($request->bill_to == 'CUSTOMER' && trim($request->bill_to_slack) == "WriteCustomer")) {
            // dd("WriteCustomer");
            $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                ->where('name', '=', trim($request->write_customer_name))
                ->active()
                ->first();
            if (empty($customer_data)) {
               
                $customer_data = CustomerModel::create([
                    "slack" => $this->generate_slack("customers"),
                    'customer_type' => 'CUSTOM',
                    "name" => $request->write_customer_name
                ]);
            }

            $bill_to_id = $customer_data->id;
            $bill_to_code = '';
            $bill_to_name = $customer_data->name;
            $bill_to_email = $customer_data->email;
            $bill_to_contact = $customer_data->phone;
            $bill_to_address = $customer_data->address;
        }

        $invoice_ref_details = InvoiceModel::where([
            ['invoice_number', '=', trim($request->invoice_number)],
            ['status', '!=', 0],
        ])->when($invoice_slack, function ($query, $invoice_slack) {
            return $query->where('slack', '!=', $invoice_slack);
        })->first();
        if (!empty($invoice_ref_details)) {
            throw new Exception(trim($request->invoice_number) . " " . trans("Invoice no is already used"));
        }

        $currency_data = CountryModel::select('currency_code', 'currency_name')
            ->where('currency_code', '=', trim($request->currency))
            ->active()
            ->first();
        if (empty($currency_data)) {
            throw new Exception(trans("Invalid currency selected"), 400);
        }

        // $tax_option_data = $this->calculate_tax_component($request->tax_option);
        if(isset($request['creation_type']) && $request['creation_type'] == 'quotation_to_invoice'){
            $bill_to_id = $request->bill_to_id;
            $bill_to_code = '';
            $bill_to_name = $request->bill_to_name;
            $bill_to_email = $request->bill_to_email;
            $bill_to_contact = $request->bill_to_contact;
            $bill_to_address = $request->bill_to_address;
        }
        foreach ($products as $product_key => $product_value) {
            if(isset($request['creation_type']) && $request['creation_type'] == 'quotation_to_invoice'){
                $product_slack = $product_value['product_slack'];
                $product_value['unit_price'] = (isset($product_value['amount_excluding_tax']) && $product_value['amount_excluding_tax'] != '') 
                                                ? $product_value['amount_excluding_tax'] : 0.00;
                $tax_code_id = (isset($product_value['tax_code_id']) && $product_value['tax_code_id'] != '') ? $product_value['tax_code_id'] : 0;
            }else{
                $product_slack = $product_value['slack'];
            }
            $product_name = $product_value['name'];
            $product_description = $product_value['description'];
            $unit_price = (isset($product_value['unit_price']) && $product_value['unit_price'] != '') ? $product_value['unit_price'] : 0.00;
            $quantity = (isset($product_value['quantity']) && $product_value['quantity'] != '') ? $product_value['quantity'] : 0.00;
            $discount_type = (isset($product_value['discount_type']) && $product_value['discount_type'] != '') ? $product_value['discount_type'] : 0;
            $discount_percentage = (isset($product_value['discount_percentage']) && $product_value['discount_percentage'] != '') ? $product_value['discount_percentage'] : 0.00;
            $measurement_id = (isset($product_value['measurement_id']) && $product_value['measurement_id'] != '') ? $product_value['measurement_id'] : NULL;
            $tax_code_id = (isset($product_value['tax_code']) && $product_value['tax_code'] != '') ? $product_value['tax_code'] : 0;
            $tax_percentage = (isset($product_value['tax_percentage']) && $product_value['tax_percentage'] != '') ? $product_value['tax_percentage'] : 0.00;
            $product_type = (isset($product_value['product_type'])) ? $product_value['product_type'] : null;


            $subtotal_amount_excluding_tax = $unit_price * $quantity;

            $discount_amount = $this->calculate_discount($subtotal_amount_excluding_tax, $discount_type,$discount_percentage);

            $total_amount_after_discount = ($subtotal_amount_excluding_tax - $discount_amount);

            $tax_amount = $this->calculate_tax($total_amount_after_discount, $tax_percentage);

            $item_total = ($total_amount_after_discount + $tax_amount);

            // $tax_components = $tax_option_data['tax_components'];
            // if(count($tax_components)>0){
            //     foreach($tax_components as $key => $tax_component){
            //         $tax_component_percentage = ($tax_percentage/count($tax_components));
            //         $tax_component_amount = $this->calculate_tax($total_amount_after_discount, $tax_component_percentage);
            //         $tax_components[$key]['tax_percentage'] = $tax_component_percentage;
            //         $tax_components[$key]['tax_amount'] = number_format((float)$tax_component_amount, 2, '.', '');
            //     }
            // }

            if ($product_slack != '') {
                $product_data = ProductModel::select('products.id', 'products.slack', 'products.product_code', 'products.quantity')
                    ->where('products.slack', '=', $product_slack)
                    ->categoryJoin()
                    // ->supplierJoin()
                    // ->taxcodeJoin()
                    // ->discountcodeJoin()
                    ->categoryActive()
                    // ->supplierActive()
                    // ->taxcodeActive()
                    ->first();
                if (empty($product_data)) {
                    throw new Exception($product_name . " " . trans("Product is not currently available"), 400);
                }
                // dd($product_data);

                if (($product_data->quantity < $quantity) && ($product_data->quantity != '-1.00')) {
                    throw new Exception($product_name . " " . trans(" is out of stock"), 400);
                }

                if ($this->__check_ingredient_stock($product_data->id, $quantity)) {
                    throw new Exception($product_name . " " . trans(" low on Ingredient stock"), 400);
                }
            }




            $invoice_products[] = [
                'invoice_id' => 0,
                'product_slack' => ($product_slack != '') ? $product_data->slack : NULL,
                'product_id' => ($product_slack != '') ? $product_data->id : NULL,
                'product_code' => ($product_slack != '') ? $product_data->product_code : NULL,
                'name' => isset($product_name) ? $product_name : NULL,
                'description' => isset($product_description) ? $product_description : NULL,
                'measurement_id' =>  $measurement_id,
                'quantity' => $quantity,
                'amount_excluding_tax' => $unit_price,
                'subtotal_amount_excluding_tax' => $subtotal_amount_excluding_tax,
                'discount_type' => $discount_type,
                'discount_percentage' => $discount_percentage,

                'tax_code_id' => $tax_code_id,
                'tax_percentage' => $tax_percentage,
                'discount_amount' => $discount_amount,
                'total_after_discount' => $total_amount_after_discount,

                'tax_amount' => $tax_amount,
                // 'tax_components' => (count($tax_components)>0)?json_encode($tax_components):'',
                'total_amount' => $item_total,
                'product_type' => $product_type
            ];
        }

        $total_amount_excluding_tax_array = data_get($invoice_products, '*.subtotal_amount_excluding_tax', 0);
        $total_amount_excluding_tax = array_sum($total_amount_excluding_tax_array);

        $total_discount_amount_array = data_get($invoice_products, '*.discount_amount', 0);
        $total_discount_amount = array_sum($total_discount_amount_array);

        $total_after_discount_amount_array = data_get($invoice_products, '*.total_after_discount', 0);
        $total_after_discount_amount = array_sum($total_after_discount_amount_array);




        // Tax Calculation for Invoice
        $total_tax_amount_array = data_get($invoice_products, '*.tax_amount', 0);
        $total_product_tax_amount = array_sum($total_tax_amount_array);
        $total_store_tax_amount = (float) $request->invoice_tax_amount;
        $total_tax_amount = $total_store_tax_amount;

        $total_invoice_charges = 0;
        if (!empty($request->invoice_charges)) {
            $total_invoice_charges = collect(json_decode($request->invoice_charges))->sum('amount');
        }

        $total_after_discount_amount = $total_amount_excluding_tax - $total_discount_amount;
        $total_order_amount = ($total_after_discount_amount + $total_tax_amount) + $total_invoice_charges;

        $invoice_data = [
            "store_id" => $request->logged_user_store_id,
            "invoice_reference" => $this->generate_reference_no($request->logged_user_store_id),
            "invoice_date" => $request->invoice_date,
            "invoice_due_date" => $request->invoice_due_date,
            "bill_to" => $request->bill_to,
            "bill_to_id" => $bill_to_id,
            "bill_to_code" => $bill_to_code,
            "bill_to_name" => $bill_to_name,
            "bill_to_email" => $bill_to_email,
            "bill_to_contact" => $bill_to_contact,
            "bill_to_address" => $bill_to_address,
            "currency_name" => $currency_data->currency_name,
            "currency_code" => $currency_data->currency_code,
            "subtotal_excluding_tax" => $total_amount_excluding_tax,
            "subtotal_including_tax" => ($total_amount_excluding_tax + $total_product_tax_amount) - $total_discount_amount,
            "total_discount_amount" => $total_discount_amount,
            "total_after_discount" => $total_after_discount_amount,
            "total_tax_amount" => $total_tax_amount,
            "total_order_amount" => $total_order_amount,
            "terms" => $request->terms,
            "invoice_color_code" => '#094269',
            "tax_option_id" => $request->tax_option
        ];
        // dd($invoice_data);

        return [
            'invoice_data' => $invoice_data,
            'invoice_products' => $invoice_products
        ];
    }

    public function form_invoice_service_array($request)
    {

        $bill_to_id = "";
        $bill_to_code = "";
        $bill_to_name = "";
        $bill_to_email = "";
        $bill_to_contact = "";
        $bill_to_address = "";
        $invoice_slack = $request->invoice_slack;

        $services = $request->products;

        if (empty((array) $services)) {
            throw new Exception(trans("Product list cannot be empty"));
        }

        if ($request->bill_to == 'SUPPLIER') {

            if ($request->bill_to_slack == 'DEFAULT') {

                $bill_to_id = "";
                $bill_to_code = "";
                $bill_to_name = "";
                $bill_to_email = "";
                $bill_to_contact = "";
                $bill_to_address = "";
            
            } else {

                $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'email', 'phone', 'address', 'pincode')
                    ->where('slack', '=', trim($request->bill_to_slack))
                    ->active()
                    ->first();
                if (empty($supplier_data)) {
                    throw new Exception(trans("Invalid Customer selected"), 400);
                }

                $bill_to_id = $supplier_data->id;
                $bill_to_code = $supplier_data->supplier_code;
                $bill_to_name = $supplier_data->name;
                $bill_to_email = $supplier_data->email;
                $bill_to_contact = $supplier_data->phone;
                $bill_to_address = $supplier_data->address . ',' . $supplier_data->pincode;
            }
        } else if ($request->bill_to == 'COMPANY' || ($request->bill_to == 'CUSTOMER' && trim($request->bill_to_slack) != "" && trim($request->bill_to_slack) != "WriteCustomer")) {
            $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                ->where('slack', '=', trim($request->bill_to_slack))
                ->active()
                ->first();
            if (empty($customer_data)) {
                throw new Exception(trans("Invalid customer selected"), 400);
            }

            $bill_to_id = $customer_data->id;
            $bill_to_code = '';
            $bill_to_name = $customer_data->name;
            $bill_to_email = $customer_data->email;
            $bill_to_contact = $customer_data->phone;
            $bill_to_address = $customer_data->address;
        }else if ($request->bill_to == 'COMPANY' || ($request->bill_to == 'CUSTOMER' && trim($request->bill_to_slack) == "WriteCustomer")) {
            // dd("WriteCustomer");
            $customer_data = CustomerModel::select('id', 'name', 'email', 'address', 'phone')
                ->where('name', '=', trim($request->write_customer_name))
                ->active()
                ->first();
            if (empty($customer_data)) {
                
                $customer_data = CustomerModel::create([
                    "slack" => $this->generate_slack("customers"),
                    'customer_type' => 'CUSTOM',
                    "name" => $request->write_customer_name
                ]);
            }

            $bill_to_id = $customer_data->id;
            $bill_to_code = '';
            $bill_to_name = $customer_data->name;
            $bill_to_email = $customer_data->email;
            $bill_to_contact = $customer_data->phone;
            $bill_to_address = $customer_data->address;
        }

        $invoice_ref_details = InvoiceModel::where([
            ['invoice_number', '=', trim($request->invoice_number)],
            ['status', '!=', 0],
        ])->when($invoice_slack, function ($query, $invoice_slack) {
            return $query->where('slack', '!=', $invoice_slack);
        })->first();
        if (!empty($invoice_ref_details)) {
            throw new Exception(trim($request->invoice_number) . " " . trans("Invoice no is already used"));
        }

        $currency_data = CountryModel::select('currency_code', 'currency_name')
            ->where('currency_code', '=', trim($request->currency))
            ->active()
            ->first();
        if (empty($currency_data)) {
            throw new Exception(trans("Invalid currency selected"), 400);
        }

        // $tax_option_data = $this->calculate_tax_component($request->tax_option);

        
        foreach ($services as $service_key => $service_value) {

            $product_name = $service_value['name'];
            $unit_price = (isset($service_value['unit_price']) && $service_value['unit_price'] != '') ? $service_value['unit_price'] : 0.00;
            $quantity = (isset($service_value['quantity']) && $service_value['quantity'] != '') ? $service_value['quantity'] : 0.00;
            $discount_type = (isset($service_value['discount_type']) && $service_value['discount_type'] != '') ? $service_value['discount_type'] : 0;
            $discount_percentage = (isset($service_value['discount_percentage']) && $service_value['discount_percentage'] != '') ? $service_value['discount_percentage'] : 0.00;
            $measurement_id = (isset($service_value['measurement_id']) && $service_value['measurement_id'] != '') ? $service_value['measurement_id'] : NULL;
            $tax_code_id = (isset($service_value['tax_percentage']) && $service_value['tax_percentage'] == 15 ) ? Taxcode::where('total_tax_percentage',$service_value['tax_percentage'])->first()->id : 0;
            $tax_percentage = (isset($service_value['tax_percentage']) && $service_value['tax_percentage'] != '') ? $service_value['tax_percentage'] : 0.00;
            $product_type = 2;

            $subtotal_amount_excluding_tax = $unit_price * $quantity;

            $discount_amount = $this->calculate_discount($subtotal_amount_excluding_tax, $discount_type, $discount_percentage);

            $total_amount_after_discount = ($subtotal_amount_excluding_tax - $discount_amount);

            $tax_amount = $this->calculate_tax($total_amount_after_discount, $tax_percentage);

            $item_total = ($total_amount_after_discount + $tax_amount);

            $invoice_services[] = [
                'invoice_id' => 0,
                'name' => isset($product_name) ? $product_name : NULL,
                'measurement_id' =>  $measurement_id,
                'quantity' => $quantity,
                'amount_excluding_tax' => $unit_price,
                'subtotal_amount_excluding_tax' => $subtotal_amount_excluding_tax,
                'discount_type' => $discount_type,
                'discount_percentage' => $discount_percentage,

                'tax_code_id' => $tax_code_id,
                'tax_percentage' => $tax_percentage,
                'discount_amount' => $discount_amount,
                'total_after_discount' => $total_amount_after_discount,

                'tax_amount' => $tax_amount,
                // 'tax_components' => (count($tax_components)>0)?json_encode($tax_components):'',
                'total_amount' => $item_total,
                'product_type' => $product_type
            ];
        }

        $total_amount_excluding_tax_array = data_get($invoice_services, '*.subtotal_amount_excluding_tax', 0);
        $total_amount_excluding_tax = array_sum($total_amount_excluding_tax_array);

        $total_discount_amount_array = data_get($invoice_services, '*.discount_amount', 0);
        $total_discount_amount = array_sum($total_discount_amount_array);

        $total_after_discount_amount_array = data_get($invoice_services, '*.total_after_discount', 0);
        $total_after_discount_amount = array_sum($total_after_discount_amount_array);




        // Tax Calculation for Invoice
        $total_tax_amount_array = data_get($invoice_services, '*.tax_amount', 0);
        $total_product_tax_amount = array_sum($total_tax_amount_array);
        $total_store_tax_amount = (float) $request->invoice_tax_amount;
        $total_tax_amount = $total_store_tax_amount;

        $total_invoice_charges = 0;
        if (!empty($request->invoice_charges)) {
            $total_invoice_charges = collect(json_decode($request->invoice_charges))->sum('amount');
        }

        $total_after_discount_amount = $total_amount_excluding_tax - $total_discount_amount;
        $total_order_amount = ($total_after_discount_amount + $total_tax_amount) + $total_invoice_charges;

        $invoice_data = [
            "store_id" => $request->logged_user_store_id,
            "invoice_reference" => $this->generate_reference_no($request->logged_user_store_id),
            "invoice_date" => $request->invoice_date,
            "invoice_due_date" => $request->invoice_due_date,
            "bill_to" => $request->bill_to,
            "bill_to_id" => $bill_to_id,
            "bill_to_code" => $bill_to_code,
            "bill_to_name" => $bill_to_name,
            "bill_to_email" => $bill_to_email,
            "bill_to_contact" => $bill_to_contact,
            "bill_to_address" => $bill_to_address,
            "currency_name" => $currency_data->currency_name,
            "currency_code" => $currency_data->currency_code,
            "subtotal_excluding_tax" => $total_amount_excluding_tax,
            "subtotal_including_tax" => ($total_amount_excluding_tax + $total_product_tax_amount) - $total_discount_amount,
            "total_discount_amount" => $total_discount_amount,
            "total_after_discount" => $total_after_discount_amount,
            "total_tax_amount" => $total_tax_amount,
            "total_order_amount" => $total_order_amount,
            "terms" => $request->terms,
            "invoice_color_code" => '#094269',
            "tax_option_id" => $request->tax_option
        ];
        
        return [
            'invoice_data' => $invoice_data,
            'invoice_services' => $invoice_services
        ];
    }

    public function calculate_tax($item_total, $tax_percentage)
    {
        $tax_amount = ($tax_percentage / 100) * $item_total;
        return $tax_amount;
    }

    public function calculate_discount($item_total, $discount_type, $discount_percentage)
    {
        if($discount_type==1){
            $discount_amount = $discount_percentage;
        }else{
            $discount_amount = ($discount_percentage / 100) * $item_total;
        }

        return $discount_amount;
    }

    public function calculate_tax_component($tax_option_constant)
    {

        $response = [];

        $response['tax_option_id'] = '';
        $response['tax_components'] = [];

        $tax_components = [];

        if (isset($tax_option_constant) && $tax_option_constant != '') {

            $tax_option_data = MasterTaxOptionModel::select('id', 'component_count', 'component_1', 'component_2', 'component_3')
                ->where('tax_option_constant', '=', $tax_option_constant)
                ->active()
                ->first();

            $response['tax_option_id'] = $tax_option_data->id;

            if ($tax_option_data->component_count > 0) {
                for ($i = 1; $i <= $tax_option_data->component_count; $i++) {
                    $component_name = 'component_' . $i;
                    $tax_components[]['name'] = $tax_option_data->$component_name;
                }

                $response['tax_components'] = $tax_components;
            }
        }
        return $response;
    }

    public function load_bill_to_list(Request $request)
    {
        try {

            $type = $request->type;
            $keywords = $request->keywords;

            if ($type == "") {
                return;
            }

            if ($type == "SUPPLIER") {
                $list_data = SupplierModel::select('slack', DB::raw("CONCAT(COALESCE(supplier_code, ''),' - ',COALESCE(name, '')) as label"))
                    ->where('name', 'like', $keywords . '%')
                    ->orWhere('supplier_code', 'like', $keywords . '%')
                    ->active()
                    ->get();
            } else if ($type == "CUSTOMER") {
                $list_data = CustomerModel::select('slack', DB::raw("CONCAT(COALESCE(name, ''), ',', COALESCE(email, ''), ',', COALESCE(phone, '')) as label"))
                    ->where('name', 'like', $keywords . '%')
                    ->orWhere('email', 'like', $keywords . '%')
                    ->orWhere('phone', 'like', $keywords . '%')
                    ->active()
                    ->skipDefaultCustomer()
                    ->get();
            }

            return response()->json($this->generate_response(
                array(
                    "message" => "List filtered successfully",
                    "data" => $list_data
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

    public function filter_invoices(Request $request)
    {
        try {

            $keyword = $request->keyword;

            $invoice_list = InvoiceModel::select("*")
                ->where('invoice_number', 'like', $keyword . '%')
                ->orWhere('invoice_reference', 'like', $keyword . '%')
                ->orWhere('bill_to_code', 'like', $keyword . '%')
                ->limit(25)
                ->get();

            $invoices = InvoiceResource::collection($invoice_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoices filtered successfully",
                    "data" => $invoices
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

    public function get_invoice_pending_payment_data($slack)
    {
        try {

            if (!check_access(['A_DETAIL_INVOICE'], true)) {
                throw new Exception("Invalid request", 400);
            }

            $invoice = InvoiceModel::where('slack', '=', $slack)->first();

            $transaction_type_data = MasterTransactionTypeModel::select('id')
                ->where('transaction_type_constant', '=', trim('INCOME'))
                ->first();

        

            $total_amount = $invoice->subtotal_including_tax;
            $total_amount_including_tax =  $invoice->subtotal_including_tax;
            $total_invoice_charges = $this->get_total_invoice_charge($invoice->id);
            $total_amount = $total_amount_including_tax + $total_invoice_charges;

            $paid_amount = TransactionModel::select('id')->where([
                ['bill_to', '=', 'INVOICE'],
                ['bill_to_id', '=', $invoice->id],
                ['transaction_type', '=', $transaction_type_data->id],
            ])->sum('amount');

            $invoice_transaction_details = TransactionModel::select('id', 'created_at', 'amount', 'payment_method', 'notes', 'transaction_date')->where([
                ['bill_to', '=', 'INVOICE'],
                ['bill_to_id', '=', $invoice->id],
                ['transaction_type', '=', $transaction_type_data->id],
            ])->orderBy('id', 'DESC')->get();


            $pending_amount = bcsub($total_amount, $paid_amount, 2);

            $response = [
                'total_amount' => $total_amount,
                'paid_amount' => $paid_amount,
                'pending_amount' => $pending_amount,
                'invoice_transaction_details' => $invoice_transaction_details,
            ];

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

    public function update_color_code(Request $request)
    {


        try {

            InvoiceModel::where('id', $request->invoice_id)->update(['invoice_color_code' => $request->color_code]);

            return response()->json($this->generate_response(
                array(
                    "message" => "Invoice Color Code Updated",
                    "data" => '',
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

    public function validate_request($request)
    {
        $request->merge(['products' => json_decode($request->products, true)]);
        if ($request->bill_to == "COMPANY") {
            $validator = Validator::make($request->all(), [
                'bill_to_slack' => $this->get_validation_rules("slack", true),
                'invoice_date' => 'date|required',
                'invoice_due_date' => 'date|required',
                'terms' => $this->get_validation_rules("text", false),
                'products.*.name' => $this->get_validation_rules("name_label", true),
                'products.*.quantity' => $this->get_validation_rules("numeric", true),
                'products.*.unit_price' => $this->get_validation_rules("numeric", true),
                'products.*.discount_percentage' => $this->get_validation_rules("numeric", false),
                'products.*.tax_percentage' => $this->get_validation_rules("numeric", false)
            ]);
        } else if ($request->bill_to == "CUSTOMER") {
            $validator = Validator::make($request->all(), [
                'invoice_date' => 'date|required',
                'invoice_due_date' => 'date|required',
                'terms' => $this->get_validation_rules("text", false),
                'products.*.name' => $this->get_validation_rules("name_label", true),
                'products.*.quantity' => $this->get_validation_rules("numeric", true),
                'products.*.unit_price' => $this->get_validation_rules("numeric", true),
                'products.*.discount_percentage' => $this->get_validation_rules("numeric", false),
                'products.*.tax_percentage' => $this->get_validation_rules("numeric", false)
            ]);
        }
        if ($request->bill_to_slack == "WriteCustomer") {
            $validator = Validator::make($request->all(), [ 
                'write_customer_name' => 'required',
            ]);
        }
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }


    public function invoice_return_list(Request $request)
    {
        try {
            $total_order_amount = 0.0;
            $return_products = array();
            $invoice_slack = $request->invoice_slack;
            $products = $request->products;
            $invoice_basic = $request->invoice_basic;
            $return_products = json_decode($products, true);
            $invoice_basic = json_decode($invoice_basic, true);

            DB::beginTransaction();

            // $invoice_date_detail = InvoiceModel::select('invoice_date')->where('store_id', $request->logged_user_store_id)->where('status', 1)->orderBy('id', 'desc')->first();
            // if (isset($invoice_basic['transactions']) && !empty($invoice_basic['transactions'])) {
            //     $invoice_basic_transactions = $invoice_basic['transactions'];
            //     foreach ($invoice_basic_transactions as $invoice_basic_transaction) {
            //         $transaction_slack = $invoice_basic_transaction['slack'];
            //         $transaction_amount = $invoice_basic_transaction['amount'];
            //         $transaction_data = TransactionModel::where('slack', $transaction_slack)->first();
            //         $transaction_data->amount = bcsub($transaction_amount, $invoice_basic['total_order_amount'], 2);
            //         $transaction_data->save();
            //     }
            // }

            $item = InvoiceModel::select('*')->where('slack', $invoice_slack)->first();

            $old_invoice_status = $item->status;

            $invoice_product = InvoiceProductModel::where('invoice_id', $item['id'])->get();
            $payment_slack = isset($request->payment_slack)?$request->payment_slack:'';
            $payment_method = PaymentMethodModel::where('slack',$payment_slack)->first();
            $payment_method_label = isset($payment_method->label) ? $payment_method->label : null;
            $payment_method_id = isset($payment_method->id) ? $payment_method->id : null;
            /*$return_order_check = InvoiceReturnModel::select('*')
                ->where('invoice_slack', $invoice_slack)
                ->first();

            if (isset($return_order_check->id)) {
                throw new Exception("Invoice is already returned.", 400);
            }*/

            $return_invoice_slack = $invoice['slack'] = $this->generate_slack("invoices_return");
            $invoice['store_id'] = $item->store_id;
            $invoice['return_invoice_number'] = $this->getLastReturnInvoiceNumber();
            $invoice['invoice_slack'] = trim($invoice_slack);
            $invoice['invoice_number'] = $item->invoice_number;
            $invoice['invoice_reference'] = $item->invoice_reference;
            $invoice['invoice_date'] = $item->invoice_date;
            $invoice['invoice_due_date'] = $item->invoice_due_date;
            $invoice['parent_po_id'] = $item->parent_po_id;
            $invoice['bill_to'] = $item->bill_to;
            $invoice['bill_to_id'] = $item->bill_to_id;
            $invoice['bill_to_code'] = $item->bill_to_code;
            $invoice['bill_to_name'] = $item->bill_to_name;
            $invoice['bill_to_email'] = $item->bill_to_email;
            $invoice['bill_to_contact'] = $item->bill_to_contact;
            $invoice['bill_to_address'] = $item->bill_to_address;
            $invoice['currency_name'] = $item->currency_name;
            $invoice['currency_code'] = $item->currency_code;
            $invoice['tax_option_id'] = $item->tax_option_id;
            $invoice['subtotal_excluding_tax'] = number_format($invoice_basic['subtotal_excluding_tax'], 2, '.', '');
            $invoice['subtotal_including_tax'] = number_format($invoice_basic['subtotal_including_tax'], 2, '.', '');
            $invoice['total_discount_amount'] = number_format($invoice_basic['total_discount_amount'], 2, '.', '');
            $invoice['total_after_discount'] = number_format($invoice_basic['total_after_discount'], 2, '.', '');
            $invoice['total_tax_amount'] = number_format($invoice_basic['total_tax_amount'], 2, '.', '');
            $invoice['shipping_charge'] = $item->shipping_charge;
            $invoice['packing_charge'] = $item->packing_charge;
            $invoice['total_order_amount'] = $total_order_amount = number_format($invoice_basic['total_order_amount'], 2, '.', '');
            $invoice['notes'] = $item->notes;
            $invoice['terms'] = $item->terms;
            $invoice['status'] = 1;
            $invoice['created_by'] = $request->logged_user_id;
            $invoice['updated_by'] = $item->updated_by;
            $invoice['created_at'] = now();
            $invoice['updated_at'] = $item->updated_at;
            $invoice['invoice_color_code'] = $item->invoice_color_code;
            $invoice['reason'] = $request->return_reason;
            $invoice['payment_method_id'] = $payment_method_id;
            $invoice['payment_method_slack'] = isset($payment_method->slack) ? $payment_method->slack : null;
            $invoice['payment_method'] = $payment_method_label;

            $invoice_item = InvoiceReturnModel::create($invoice);
            $return_invoice_id = $invoice_item->id;
            $return_invoice_slack = $invoice_item->slack;

            $code_start_config = Config::get('constants.unique_code_start.return_invoice');
            $code_start = (isset($code_start_config)) ? $code_start_config : 100;

            $return_invoice_number = [
                "return_invoice_number" => $this->getNextReturnInvoiceNumber()
            ];
            InvoiceReturnModel::where('slack', $return_invoice_slack)->update($return_invoice_number);

            $i = 0;
            $quantity_check = TRUE;
            if (count($return_products) > 0) {
                foreach ($return_products as $product) {
                    //Check Return Quantity is > 0
                    if($product['quantity']>0){
                        if($product['product_slack'] != ''){
                            $product_data = ProductModel::where('slack', $product['product_slack'])->get();

                            $product_data = $product_data->first();

                            $product_data_temp = ProductModel::find($product_data->id);

                            if (isset($product['is_wastage']) && $product['is_wastage'] == true) {
                                // no need to increase quantity
                            } else {
                                $quantity = 0;
                                if ($product_data->quantity != -1.00) {
                                    $quantity = $product['quantity'];
                                    // Add quantity history
                                    $this->addQuantityHistory($this->generate_slack('quantity_history'),$product_data->id,request()->logged_user_store_id,'INVOICE_RETURN','INCREMENT',$product['quantity'],$product_data->id);
                                }
                                $product_data_temp->increment('quantity', $quantity);
                            }
                        }

                        //Product and Service Return Add Entry
                        $return_product_item = [];

                        $return_product_item['slack'] = $this->generate_slack("invoice_return_products");
                        $return_product_item['return_invoice_id'] = $return_invoice_id;
                        $return_product_item['return_invoice_slack'] = $return_invoice_slack;
                        $return_product_item['product_id'] = isset($product_data->id) ? $product_data->id : NULL;
                        $return_product_item['product_slack'] = isset($product['product_slack']) ? $product['product_slack'] : NULL;
                        $return_product_item['product_code'] = isset($product['product_code']) ? $product['product_code'] : NULL;
                        $return_product_item['name'] = $product['name'];
                        $return_product_item['quantity'] = $product['quantity'];
                        $return_product_item['amount_excluding_tax'] = isset($product['amount_excluding_tax']) ? $product['amount_excluding_tax'] : NULL;
                        $return_product_item['subtotal_amount_excluding_tax'] = isset($product['subtotal_amount_excluding_tax']) ? $product['subtotal_amount_excluding_tax'] : NULL;

                        $return_product_item['discount_percentage'] = isset($product['discount_percentage']) ? $product['discount_percentage'] : NULL;
                        $return_product_item['tax_code_id'] = isset($product['tax_code_id']) ? $product['tax_code_id'] : NULL;
                        $return_product_item['tax_code'] = isset($product['tax_code']) ? $product['tax_code'] : NULL;
                        $return_product_item['tax_percentage'] = isset($product['tax_percentage']) ? $product['tax_percentage'] : NULL;
                        $return_product_item['discount_amount'] = isset($product['discount_amount']) ? $product['discount_amount'] : NULL;
                        $return_product_item['total_after_discount'] = isset($product['total_after_discount']) ? $product['total_after_discount'] : NULL;
                        $return_product_item['tax_amount'] = isset($product['tax_amount']) ? $product['tax_amount'] : NULL;
                        $return_product_item['tax_components'] = isset($product['tax_components']) ? json_encode($product['tax_components']) : NULL;
                        $return_product_item['total_amount'] = isset($product['amount']) ? $product['amount'] : NULL;
                        $return_product_item['measurement_id'] = isset($product_data->measurement_id) ? $product_data->measurement_id : NULL;
                        $return_product_item['product_type'] = isset($product['product_type']) ? $product['product_type'] : NULL;
                        $return_product_item['status'] = 1;
                        $return_product_item['created_by'] = $request->logged_user_id;
                        $return_product_item['created_at'] = now();
                        $return_product_item['is_wastage'] = (isset($product['is_wastage']) && $product['is_wastage'] == true) ? true : false;
                        $invoice_id = InvoiceReturnProductsModel::create($return_product_item)->id;

                    }

                    if (isset($product['max_quantity']) && isset($product['quantity'])) {
                        if ($product['quantity'] < $product['max_quantity']) {
                            $quantity_check = FALSE;
                        }
                    }
                    $i++;
                }
            }

            $invoice_product_count = count($invoice_product);

            if ($invoice_product_count == $i &&  $quantity_check == TRUE) {

                $update_order_data = [
                    "status" => 8,
                    "return_invoice_amount" => $total_order_amount
                ];
            } else {
                $update_order_data = [
                    // "status" => 1,
                    "return_invoice_amount" => $total_order_amount
                ];
            }

            InvoiceModel::where('slack', $invoice_slack)->update($update_order_data);

            // $transaction_type_data = MasterTransactionTypeModel::select('id')
            //     ->where('transaction_type_constant', '=', 'INCOME')->first();
            $transaction_data = TransactionModel::select('account_id','transaction_type')->where([
                    ['bill_to', '=', 'INVOICE'],
                    ['bill_to_id', $item['id']]
                ])->first();

            $transaction = [
                "slack" => $this->generate_slack("transactions"),
                "store_id" => $item->store_id,
                "transaction_code" => Str::random(6),
                "account_id" => $transaction_data->account_id,
                "transaction_type" => $transaction_data->transaction_type,
                "payment_method_id" => $payment_method_id,
                "payment_method" => $payment_method_label,
                "bill_to" => 'INVOICE',
                "bill_to_id" => $return_invoice_id,
                "bill_to_name" => (isset($item->bill_to_name)) ? $item->bill_to_name : 'Walkin Customer',
                "bill_to_contact" => $item->bill_to_contact,
                "bill_to_address" => (isset($item->address)) ? $item->address : '',
                "currency_code" => $item->currency_code,
                "amount" => (-$total_order_amount),
                "pg_transaction_id" => '',
                "pg_transaction_status" => '',
                "notes" => '',
                "transaction_date" => date('Y-m-d'),
                "created_by" => request()->logged_user_id,
                "return_bill_to_id" => $item['id'],
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
                $order_return_detail = InvoiceReturnModel::where('id',$return_invoice_id)->first();
                $this->qoyod_create_credit_note($order_return_detail,'Invoice');
            }

            DB::commit();
            $item_data = new InvoiceReturnResource($invoice_item);

            $forward_link = route('invoice_return_receipt', ['slack' => $invoice['slack']]);

            if (isset($invoice)) {

                return response()->json($this->generate_response(
                    array(
                        "message" => "Return Order created successfully",
                        "data" => $invoice['slack'],
                        "link" => $forward_link,
                        "order" => $item_data
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


    public function record_invoice_transaction_hrm($invoice_slack, $transaction_receipt_link)
    {
        $invoice_detail = InvoiceModel::select('*')->where('slack', $invoice_slack)->first();
        $acc_transaction = AccTransactionModel::orderBy('id', 'DESC')->first();

        $transaction_id = isset($acc_transaction->ID) ? $acc_transaction->ID : 0;
        $Vtype = "JV";
        $voucher_no = "Journal-" . bcadd($transaction_id, 1);

        $bill_to_name = isset($invoice_detail->bill_to_name) ? $invoice_detail->bill_to_name : NULL;
        $id = isset($invoice_detail->id) ? $invoice_detail->id : NULL;

        //Sales Tax check

        $sales_vat_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'like', '%Sales VAT%')
            ->first();
        if (empty($sales_vat_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($invoice_detail->total_tax_amount) && $invoice_detail->total_tax_amount != 0.00) {
            $sales_vat_debit =  0;
            $sales_vat_credit = isset($invoice_detail->total_tax_amount) ? $invoice_detail->total_tax_amount : 0;

            $sales_vat_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_vat_headcode->HeadCode) ? $sales_vat_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Entry',
                'Debit'          =>  empty($sales_vat_debit) ? 0 : $sales_vat_debit,
                'Credit'         =>  empty($sales_vat_credit) ? 0 : $sales_vat_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_vat_transaction)->id;
        }

        //Sales check
        $sales_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'Sales')
            ->first();
        if (empty($sales_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }

        if (isset($invoice_detail->total_after_discount)) {

            $sales_debit =  0;
            $sales_credit = isset($invoice_detail->total_after_discount) ? $invoice_detail->total_after_discount : 0;

            $sales_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($sales_headcode->HeadCode) ? $sales_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Entry',
                'Debit'          =>  empty($sales_debit) ? 0 : $sales_debit,
                'Credit'         =>  empty($sales_credit) ? 0 : $sales_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_transaction)->id;
        }


        //Accounts Receivable check
        $account_receivable_headcode = AccCoaModel::select('HeadCode')
            ->where('HeadName', 'Accounts Receivable')
            ->first();
        if (empty($account_receivable_headcode)) {
            return false;
            throw new Exception("Invalid Account selected", 400);
        }


        if (isset($invoice_detail->total_order_amount)) {

            $sales_debit = isset($invoice_detail->total_order_amount) ? $invoice_detail->total_order_amount : 0;
            $sales_credit = 0;

            $sales_transaction = [
                'VNo'            =>  $voucher_no,
                'Vtype'          =>  $Vtype,
                'VDate'          =>  date('Y-m-d'),
                'COAID'          =>  isset($account_receivable_headcode->HeadCode) ? $account_receivable_headcode->HeadCode : '',
                'Narration'      =>  $bill_to_name . ' - Invoice Entry',
                'Debit'          =>  empty($sales_debit) ? 0 : $sales_debit,
                'Credit'         =>  empty($sales_credit) ? 0 : $sales_credit,
                'transaction_id' => isset($id) ? $id : NULL,
                'transaction_receipt_link' => isset($transaction_receipt_link) ? $transaction_receipt_link : NULL,
                'order_slack' => $invoice_slack,
                'IsPosted'       => 1,
                'CreateBy'       => request()->logged_user_id,
                'CreateDate'     => date('Y-m-d'),
                'IsAppove'       => 0
            ];


            $transaction_id = AccTransactionModel::create($sales_transaction)->id;
        }
    }

    public function get_total_invoice_charge($invoice_id)
    {
        return (float) InvoiceChargeModel::where('invoice_id', $invoice_id)->sum('amount');
    }
}
