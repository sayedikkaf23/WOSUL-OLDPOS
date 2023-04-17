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

use App\Http\Resources\QuantityPurchaseResource;

use App\Models\QuantityPurchase as QuantityPurchaseModel;
use App\Models\QuantityPurchaseProduct as QuantityPurchaseProductModel;
use App\Models\Supplier as SupplierModel;
use App\Models\Product as ProductModel;
use App\Models\Country as CountryModel;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\MasterTransactionType as MasterTransactionTypeModel;
use App\Models\Transaction as TransactionModel;
use App\Models\Account as AccountModel;
use App\Models\User as UserModel;
use App\Models\MasterAccountType as MasterAccountTypeModel;

use App\Http\Resources\Collections\PurchaseOrderCollection;

use App\Http\Controllers\API\Invoice as InvoiceAPI;
use Illuminate\Support\Carbon;
use App\Http\Traits\CommonApiTrait;
use App\Http\Traits\QoyodApiTrait;

class QuantityPurchase extends Controller
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

            $data['action_key'] = 'A_VIEW_QUANTITY_PURCHASE_LISTING';
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
            
            $query = QuantityPurchaseModel::select('quantity_purchases.*', 'master_status.label as status_label', 'master_status.color as status_color', 'user_created.fullname')
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

                ->distinct()->get();

            $quantity_purchases = QuantityPurchaseResource::collection($query);

            $total_count = QuantityPurchaseModel::select("id")->get()->count();

            $item_array = [];
            foreach ($quantity_purchases as $key => $quantity_purchase) {

                $quantity_purchase = $quantity_purchase->toArray($request);

                $item_array[$key][] = $quantity_purchase['po_number'];
                $item_array[$key][] = ($quantity_purchase['po_reference'] != '') ? $quantity_purchase['po_reference'] : '-';
                $item_array[$key][] = ($quantity_purchase['supplier_name'] != '') ? $quantity_purchase['supplier_name'] : '-';
                $item_array[$key][] = ($quantity_purchase['order_date'] != '') ? $quantity_purchase['order_date'] : '-';
                $item_array[$key][] = ($quantity_purchase['order_due_date'] != '') ? $quantity_purchase['order_due_date'] : '-';
                $item_array[$key][] = $quantity_purchase['total_order_amount'];
                $item_array[$key][] = view('common.status', ['status_data' => ['label' => $quantity_purchase['status']['label'], "color" => $quantity_purchase['status']['color']]])->render();
                $item_array[$key][] = $quantity_purchase['created_at_label'];
                $item_array[$key][] = $quantity_purchase['updated_at_label'];
                $item_array[$key][] = (isset($quantity_purchase['created_by']) && isset($quantity_purchase['created_by']['fullname'])) ? $quantity_purchase['created_by']['fullname'] : '-';
                $item_array[$key][] = view('quantity_purchase.layouts.quantity_purchase_actions', array('purchase_order' => $quantity_purchase))->render();
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


    public function store(Request $request)
    {
        try {

            if (!check_access(['A_ADD_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request2($request);

            $request->po_slack = $this->generate_slack("quantity_purchases");;
            $request->po_number = $request->po_number;
            $request->po_reference = $request->po_reference;
            //            $request->order_date = $request->order_date;

            DB::beginTransaction();

            $po_data = $this->form_po_array($request);

            $po_id =null;
            if (!empty($po_data['po_data'])) {

                $po = $po_data['po_data'];

                $po['slack'] = $this->generate_slack("quantity_purchases");
                $po['created_at'] = now();
                $po['created_by'] = $request->logged_user_id;                               
                $po_id = QuantityPurchaseModel::create($po)->id;
                
                $transaction_id = $this->record_order_payment_transaction($po_id);
                // associate transaction id with quantity purchase
                QuantityPurchaseModel::where('id',$po_id)->update(['transaction_id'=>$transaction_id]);

            }
           
            if (!empty($po_data['po_products'])) {

                $po_products = $po_data['po_products'];
                array_walk($po_products, function (&$item, $key) use ($po_id, $request) {

                    $product_slack = $item['product_slack'];

                    $item['slack'] = $this->generate_slack("quantity_purchase_products");
                    $item['purchase_order_id'] = $po_id;
                    $item['created_at'] = now();
                    $item['created_by'] = $request->logged_user_id;

                    // Updating Inventory (Adding Quantity)

                    $product_quantity = ProductModel::where('slack', $product_slack)->first();
                    $updated_quanity = $product_quantity->quantity + $item['quantity'];
                    ProductModel::where('slack', $product_slack)->update(['quantity' => $updated_quanity]);

                    QuantityPurchaseProductModel::insert($item);

                    $quantity_purchase_product_id = QuantityPurchaseProductModel::where('slack', $item['slack'])->pluck('id')->first();

                     // Add quantity history
                    $this->addQuantityHistory($this->generate_slack('quantity_history'),$item['product_id'],request()->logged_user_store_id,'QUANTITY_PURCHASE','INCREMENT',$item['quantity'],$quantity_purchase_product_id);

                    //Qoyod
                    $user_qoyod = $this->qoyod_is_sync();
                    if($user_qoyod['status']){
                        $products[] = array(
                            'product_name' => $product_quantity->name,
                            'quantity' => $updated_quanity,
                            'purchase_amount' => $product_quantity->purchase_amount_excluding_tax,
                        );
                        return $this->qoyod_adjustment_inventory($user_qoyod['data']['api_key'],session('store_id'),$products,'Update');
                    }
                });
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Quantity Purchase created successfully"),
                    "data"    => $po['slack']
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

            if (!check_access(['A_DETAIL_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $item = QuantityPurchaseModel::select('*')
                ->where('slack', $slack)
                ->first();

            $item_data = new QuantityPurchaseResource($item);

            return response()->json($this->generate_response(
                array(
                    "message" => "Quantity Purchase loaded successfully",
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

            if (!check_access(['A_VIEW_QUANTITY_PURCHASE_LISTING'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $list = new PurchaseOrderCollection(QuantityPurchaseModel::select('*')
                ->orderBy('created_at', 'desc')->paginate());

            return response()->json($this->generate_response(
                array(
                    "message" => "Quantity Purchase loaded successfully",
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

            if (!check_access(['A_EDIT_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $this->validate_request($request);

            $po_details = QuantityPurchaseModel::where('slack', $slack)->first();

            $po_status = MasterStatusModel::select('value_constant')->where([
                ['value', '=', $po_details->status],
                ['key', '=', 'QUANTITY_PURCHASE_STATUS'],
                ['status', '=', '1']
            ])->active()->first();

            DB::beginTransaction();

            $request->po_slack = $slack;
            
            $po_data = $this->edit_form_po_array($request);

            
            $this->update_stock_from_po($request, $slack, $po_status->value_constant, true);

            if (!empty($po_data['po_data'])) {

                $po = $po_data['po_data'];

                $po['updated_at'] = now();
                $po['updated_by'] = $request->logged_user_id;

                $action_response = QuantityPurchaseModel::where('slack', $slack)
                    ->update($po);
            }

            $po_id = $po_details->id;
            // $this->record_order_payment_transaction($po_id);
            if (!empty($po_data['po_products'])) {

                if (count($po_data['po_products']) > 0) {
                    QuantityPurchaseProductModel::where('purchase_order_id', $po_id)->delete();
                }

                $po_products = $po_data['po_products'];

                array_walk($po_products, function (&$item, $key) use ($po_id, $request) {

                    $item['slack'] = $this->generate_slack("quantity_purchase_products");
                    $item['purchase_order_id'] = $po_id;
                    $item['updated_at'] = now();
                    $item['updated_by'] = $request->logged_user_id;

                    QuantityPurchaseProductModel::insert($item);
                });

                $this->update_stock_from_po($request, $slack, $po_status->value_constant, true);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Quantity Purchase updated successfully"),
                    "data"    => $po_details['slack']
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

    public function update_quantity_purchase(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            // $this->validate_request($request);

            $po_details = QuantityPurchaseModel::where('slack', $slack)->first();

            $po_status = MasterStatusModel::select('value_constant')->where([
                ['value', '=', $po_details->status],
                ['key', '=', 'QUANTITY_PURCHASE_STATUS'],
                ['status', '=', '1']
            ])->active()->first();

            DB::beginTransaction();

            $request->po_slack = $slack;

            $po_data = $this->edit_form_po_array($request);

            $this->update_stock_from_po($request, $slack, $po_status->value_constant, true);

            if (!empty($po_data['po_data'])) {

                $po = $po_data['po_data'];

                $po['updated_at'] = now();
                $po['updated_by'] = $request->logged_user_id;

                $action_response = QuantityPurchaseModel::where('slack', $slack)
                    ->update($po);
            }

            $po_id = $po_details->id;
            // $this->record_order_payment_transaction($po_id);
            if (!empty($po_data['po_products'])) {

                if (count($po_data['po_products']) > 0) {
                    QuantityPurchaseProductModel::where('purchase_order_id', $po_id)->delete();
                }

                $po_products = $po_data['po_products'];

                array_walk($po_products, function (&$item, $key) use ($po_id, $request) {

                    $item['slack'] = $this->generate_slack("quantity_purchase_products");
                    $item['purchase_order_id'] = $po_id;
                    $item['updated_at'] = now();
                    $item['updated_by'] = $request->logged_user_id;

                    QuantityPurchaseProductModel::insert($item);
                });

                $this->update_stock_from_po($request, $slack, $po_status->value_constant, true);
            }

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Quantity Purchase Updated Successfully"),
                    "data"    => $po_details['slack']
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

            if (!check_access(['A_DELETE_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $po_detail = QuantityPurchaseModel::select('id','transaction_id')->where('slack', $slack)->first();
            if (empty($po_detail)) {
                throw new Exception(trans("Invalid Quantity Purchase provided"), 400);
            }
            $po_id = $po_detail->id;

            DB::beginTransaction();

            $quantity_purchase_products = QuantityPurchaseProductModel::where('purchase_order_id', $po_id);
            
            foreach($quantity_purchase_products->get() as $purchase_product){

                $product = ProductModel::find($purchase_product->product_id);
                if($product->quantity != "-1"){
                    $new_quantity = $product->quantity - $purchase_product->quantity;
                    ProductModel::where('id',$product->id)->update(['quantity'=>$new_quantity]);

                     // Add quantity history

                    $this->addQuantityHistory($this->generate_slack('quantity_history'),$purchase_product->product_id,request()->logged_user_store_id,'QUANTITY_PURCHASE','DECREMENT',$purchase_product->quantity,$purchase_product->id);

                }
            }

            $quantity_purchase_products->delete();
            QuantityPurchaseModel::where('id', $po_id)->delete();

            TransactionModel::where('id',$po_detail->transaction_id)->delete();

            DB::commit();

            $forward_link = route('quantity_purchases');

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Quantity Purchase deleted successfully"),
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

    public function update_po_status(Request $request, $slack)
    {
        try {

            if (!check_access(['A_EDIT_STATUS_QUANTITY_PURCHASE'], true)) {
                throw new Exception(trans("Invalid request"), 400);
            }

            $status_constant = $request->status;

            $po_details = QuantityPurchaseModel::where('slack', $slack)->first();
            if (empty($po_details)) {
                throw new Exception(trans("Invalid Quantity Purchase selected"), 400);
            }

            $po_status = MasterStatusModel::select('value')->where([
                ['value_constant', '=', strtoupper($status_constant)],
                ['key', '=', 'QUANTITY_PURCHASE_STATUS'],
                ['status', '=', '1']
            ])->active()->first();
            if (empty($po_status)) {
                throw new Exception(trans("Invalid status provided"), 400);
            }

            DB::beginTransaction();

            $po = [];
            $po['status'] = $po_status->value;
            $po['updated_at'] = now();
            $po['updated_by'] = $request->logged_user_id;

            $action_response = QuantityPurchaseModel::where('slack', $slack)
                ->update($po);
            $this->update_stock_from_po($request, $slack, strtoupper($status_constant));

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Quantity Purchase status changed successfully"),
                    "data"    => $po_details->slack
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

    public function edit_form_po_array($request)
    {

        $po_slack = $request->po_slack;
        
        $purchase_order_detail = QuantityPurchaseModel::where('slack',$po_slack)->first();

        $products = json_decode($request->products, true);

        if (empty((array) $products)) {
            throw new Exception(trans("Product list cannot be empty"), 400);
        }

        $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'name', 'address', 'pincode')
            ->where('slack', '=', trim($request->supplier))
            ->active()
            ->first();
        if (empty($supplier_data)) {
            throw new Exception(trans("Invalid supplier selected"), 400);
        }

        // $po_number_details = QuantityPurchaseModel::where([
        //     ['po_reference', '=', trim($request->po_reference)],
        //     ['status', '!=', 0],
        // ])
        //     ->when($po_slack, function ($query, $po_slack) {
        //         return $query->where('slack', '!=', $po_slack);
        //     })->first();
        // if (!empty($po_number_details)) {
        //     throw new Exception(trim($request->po_reference)." ".trans("reference no is already used"), 400);
        // }
        
        $po_ref_details = QuantityPurchaseModel::where([
            ['po_number', '=', trim($request->po_number)],
            ['status', '!=', 0],
        ])->when($po_slack, function ($query, $po_slack) {
            return $query->where('slack', '!=', $po_slack);
        })->first();
        if (!empty($po_ref_details)) {
            throw new Exception(trim($request->po_number)." ".trans("Quantity Purchase no is already used"), 400);
        }

        $currency_data = CountryModel::select('currency_code', 'currency_name')
            ->where('currency_code', '=', trim($request->currency))
            ->active()
            ->first();
        if (empty($currency_data)) {
            throw new Exception("Invalid currency selected", 400);
        }

        $tax_option_data = $this->calculate_tax_component($request->tax_option);

        foreach ($products as $product_key => $product_value) {

            $product_slack = $product_value['product_slack'];
            $product_name = $product_value['product_name'];
            $unit_price = (isset($product_value['product_price']) && $product_value['product_price'] != '') ? $product_value['product_price'] : 0.00;
            $quantity = (isset($product_value['product_qty']) &&   $product_value['product_qty'] != '') ? $product_value['product_qty'] : 0.00;
            // $discount_percentage = (isset($product_value['discount_percentage']) && $product_value['discount_percentage'] != '') ? $product_value['discount_percentage'] : 0.00;
            $discount_percentage = 0;
            // $tax_percentage = (isset($product_value['tax_percentage']) && $product_value['tax_percentage'] != '') ? $product_value['tax_percentage'] : 0.00;
            $tax_percentage = 0;

            $subtotal_amount_excluding_tax = $unit_price * $quantity;

            // $discount_amount = $this->calculate_discount($subtotal_amount_excluding_tax, $discount_percentage);
            $discount_amount = 0;

            $total_amount_after_discount = ($subtotal_amount_excluding_tax - $discount_amount);
            
            $tax_amount = $this->calculate_tax($total_amount_after_discount, $tax_percentage);
            // $tax_amount = $request->total_tax_amount;
            
            $item_total = ($total_amount_after_discount + $tax_amount);
            
            $tax_components = $tax_option_data['tax_components'];
            if (count($tax_components) > 0) {
                foreach ($tax_components as $key => $tax_component) {
                    $tax_component_percentage = ($tax_percentage / count($tax_components));
                    $tax_component_amount = $this->calculate_tax($total_amount_after_discount, $tax_component_percentage);
                    $tax_components[$key]['tax_percentage'] = $tax_component_percentage;
                    $tax_components[$key]['tax_amount'] = number_format((float)$tax_component_amount, 2, '.', '');
                }
            }

            if ($product_slack != '') {
                $product_data = ProductModel::select('products.id', 'products.slack', 'products.product_code')
                    ->where('products.slack', '=', $product_slack)
                    ->categoryJoin()
                    ->supplierJoin()
                    ->taxcodeJoin()
                    ->discountcodeJoin()
                    // ->categoryActive()
                    // ->supplierActive()
                    // ->taxcodeActive()
                    ->first();
                if (empty($product_data)) {
                    throw new Exception($product_name ." ". trans("Product is not currently available"), 400);
                }

            }

            $po_products[] = [
                'purchase_order_id' => 0,
                'product_slack' => ($product_slack != '') ? $product_data->slack : NULL,
                'product_id' => ($product_slack != '') ? $product_data->id : NULL,
                'product_code' => ($product_slack != '') ? $product_data->product_code : NULL,
                'name' => isset($product_name) ? $product_name : NULL,

                'quantity' => $quantity,
                'amount_excluding_tax' => $unit_price,
                'subtotal_amount_excluding_tax' => $subtotal_amount_excluding_tax,
                'discount_percentage' => $discount_percentage,

                'tax_percentage' => $tax_percentage,
                'discount_amount' => $discount_amount,
                'total_after_discount' => $total_amount_after_discount,

                'tax_amount' => $tax_amount,
                'tax_components' => (count($tax_components) > 0) ? json_encode($tax_components) : '',
                'total_amount' => $item_total,
            ];

        }

        $total_amount_excluding_tax_array = data_get($po_products, '*.subtotal_amount_excluding_tax', 0);
        $total_amount_excluding_tax = array_sum($total_amount_excluding_tax_array);

        // $total_discount_amount_array = data_get($po_products, '*.discount_amount', 0);
        // $total_discount_amount = array_sum($total_discount_amount_array);
     
        $total_discount_amount = $request->total_discount_amount;
        
        // $total_after_discount_amount_array = data_get($po_products, '*.total_after_discount', 0);
        // $total_after_discount_amount = array_sum($total_after_discount_amount_array);

        $total_after_discount_amount = $total_amount_excluding_tax - $total_discount_amount;

        $total_tax_amount_array = data_get($po_products, '*.tax_amount', 0);
        $total_tax_amount = (isset($request->total_tax_amount)) ? $request->total_tax_amount : 0;
        
        // $total_tax_amount = $this->calculate_tax($total_amount_after_discount, $store_level_total_tax_percentage);
        // $total_tax_amount = number_format($total_tax_amount,2, '.', '');

        // $product_additional_discount_amount = isset($request->additional_discount_amount) ? $request->additional_discount_amount : 0.00;
        $shipping_charge = (isset($request->shipping_charge)) ? $request->shipping_charge : 0.00;
        $packing_charge = (isset($request->packing_charge)) ? $request->packing_charge : 0.00;

        $total_order_amount = ($total_after_discount_amount + $total_tax_amount + $shipping_charge + $packing_charge);
        
        $purchase_order = [
            "store_id" => $request->logged_user_store_id,
            "po_number" => $request->po_number,
            "po_reference" => $request->po_reference,
            "order_date" => $request->order_date,
            "order_due_date" => $request->order_due_date,
            "supplier_id" => $supplier_data->id,
            "supplier_code" => $supplier_data->supplier_code,
            "supplier_name" => $supplier_data->name,
            "supplier_address" => $supplier_data->address . ', ' . $supplier_data->pincode,
            "currency_name" => $currency_data->currency_name,
            "currency_code" => $currency_data->currency_code,
            "subtotal_excluding_tax" => $total_amount_excluding_tax,
            "total_discount_amount" => $total_discount_amount,
            "total_after_discount" => $total_after_discount_amount,
            "total_tax_amount" => $total_tax_amount,
            "shipping_charge" => $shipping_charge,
            "packing_charge" => $packing_charge,
            "update_stock" => ($request->update_stock == true) ? 1 : 0,
            "terms" => $request->terms,
            "total_order_amount" => $total_order_amount,
            "tax_option_id" => $tax_option_data['tax_option_id'],
            "discount_type" => $request->discount_type,
            "discount_rate" => $request->discount_rate,
        ];

        return [
            'po_data' => $purchase_order,
            'po_products' => $po_products
        ];
    }


    public function form_po_array($request)
    {

        $po_slack = $request->po_slack;

        $store_id = UserModel::find(session('user_id'))->store_id;
        $account_type_data = MasterAccountTypeModel::select('id')
            ->where('account_type_constant', '=', 'EXPENSE')
            ->first();
        if (empty($account_type_data)) {
            throw new Exception("Invalid Account type selected", 400);
        }

        $business_account = AccountModel::select('id')
            ->where('store_id', $store_id)
            ->where('account_type', $account_type_data->id)
            ->active()
            ->first();
        if (empty($business_account)) {
            throw new Exception(trans("Invalid Business Account selected"), 400);
        }

        $products = $request->products;

        if (empty((array) $products)) {
            // throw new Exception("Product list cannot be empty", 400);
            throw new Exception(trans("Product list cannot be empty"), 400);
        }

        $supplier_data = SupplierModel::select('id', 'name', 'supplier_code', 'name', 'address', 'pincode')
            ->where('slack', '=', trim($request->supplier))
            ->active()
            ->first();
        if (empty($supplier_data)) {
            throw new Exception("Invalid supplier selected", 400);
        }

        $currency_data = CountryModel::select('currency_code', 'currency_name')
            ->where('currency_code', '=', trim($request->currency))
            ->active()
            ->first();
        if (empty($currency_data)) {
            throw new Exception("Invalid currency selected", 400);
        }

        $tax_option_data = $this->calculate_tax_component($request->tax_option);

        $product_additional_discount_amount = isset($request->discount_rate) ? $request->discount_rate : 0.00;
        $store_tax_percentage = isset($request->store_tax_percentage) ? $request->store_tax_percentage : 0.00;
        $total_amount_before_additional_discount = 0.0;
        foreach ($products as $product_key => $product_value) {

            $product_slack = $product_value['product_slack'];
            //            $product_slack = $this->generate_slack("quantity_purchase_products");
            $product_name = $product_value['product_name'];
            $unit_price = (isset($product_value['product_price']) && $product_value['product_price'] != '') ? $product_value['product_price'] : 0.00;
            $quantity = (isset($product_value['product_qty']) && $product_value['product_qty'] != '') ? $product_value['product_qty'] : 0.00;
            $discount_percentage = (isset($product_value['discount_percentage']) && $product_value['discount_percentage'] != '') ? $product_value['discount_percentage'] : 0.00;
            $tax_percentage = (isset($product_value['store_tax_percentage']) && $product_value['store_tax_percentage'] != '') ? $product_value['store_tax_percentage'] : 0.00;
            // $tax_percentage = 0.00;
            // $discount_type =$request->discount_type;
            $subtotal_amount_excluding_tax = $unit_price * $quantity;

            $discount_amount = $this->calculate_discount($subtotal_amount_excluding_tax, $discount_percentage);
            $discount_amount = number_format($discount_amount, 2, '.', '');
            $total_amount_after_discount = bcsub($subtotal_amount_excluding_tax, $discount_amount, 2);
            $total_amount_after_discount = number_format($total_amount_after_discount, 2, '.', '');
            $tax_amount = $this->calculate_tax($total_amount_after_discount, $tax_percentage);
            $tax_amount = number_format($tax_amount, 2, '.', '');
            $item_total =  bcadd($total_amount_after_discount, $tax_amount, 2);
            $item_total = number_format($item_total, 2, '.', '');
            
            if ($product_slack != '') {
                $product_data = ProductModel::select('products.id', 'products.slack', 'products.product_code')
                    ->where('products.slack', '=', $product_slack)
                    ->first();
            }

            $po_products[] = [
                'purchase_order_id' => 0,
                'product_slack' => ($product_slack != '') ? $product_data->slack : NULL,
                'product_id' => ($product_slack != '') ? $product_data->id : NULL,
                'product_code' => ($product_slack != '') ? $product_data->product_code : NULL,
                'name' => isset($product_name) ? $product_name : NULL,

                'quantity' => $quantity,
                'amount_excluding_tax' => $unit_price,
                'subtotal_amount_excluding_tax' => $subtotal_amount_excluding_tax,
                'discount_percentage' => $discount_percentage,
               
                'tax_percentage' => $tax_percentage,
                'discount_amount' => $discount_amount,
                'total_after_discount' => $total_amount_after_discount,

                'tax_amount' => $tax_amount,
                'total_amount' => $item_total,
            ];

            $total_amount_before_additional_discount = bcadd($total_amount_before_additional_discount, $item_total);
        }

        if ($request->discount_type == 1) {
            $additional_discount_amount = $product_additional_discount_amount;
        } else {
            $additional_discount_amount = $this->calculate_discount($total_amount_before_additional_discount, $product_additional_discount_amount);
        }

        $additional_discount_amount = number_format($additional_discount_amount, 2, '.', '');

        $total_amount_after_discount = bcsub($total_amount_before_additional_discount, $additional_discount_amount, 2);
        $total_amount_after_discount = number_format($total_amount_after_discount, 2, '.', '');

        // $total_amount_excluding_tax_array = data_get($po_products, '*.subtotal_amount_excluding_tax', 0);
        // $total_amount_excluding_tax = array_sum($total_amount_excluding_tax_array);

        // $total_discount_amount_array = data_get($po_products, '*.discount_amount', 0);
        // $total_discount_amount = array_sum($total_discount_amount_array);

        // $total_after_discount_amount_array = data_get($po_products, '*.total_after_discount', 0);
        // $total_after_discount_amount = array_sum($total_after_discount_amount_array);

        // $total_tax_amount_array = data_get($po_products, '*.tax_amount', 0);
        // $total_tax_amount = array_sum($total_tax_amount_array);

        $total_tax_amount = $this->calculate_tax($total_amount_after_discount, $store_tax_percentage);
        $total_tax_amount = number_format($total_tax_amount, 2, '.', '');

        $total_order_amount_after_tax = bcadd($total_amount_after_discount, $total_tax_amount, 2);



        $shipping_charge = (isset($request->shipping_charge)) ? $request->shipping_charge : 0.00;
        $packing_charge = (isset($request->packing_charge)) ? $request->packing_charge : 0.00;

        $total_order_amount = bcadd($total_order_amount_after_tax, $shipping_charge, 2);
        $total_order_amount = bcadd($total_order_amount, $packing_charge, 2);

        // $total_after_discount_amount = $total_amount_excluding_tax - (float)$request->total_discount_amount;

        // $store_level_total_tax_percentage = $request->store_tax_percentage;

        // $total_tax_amount = $this->calculate_tax($total_amount_after_discount, $store_level_total_tax_percentage);
        // $total_tax_amount = number_format($total_tax_amount,2, '.', '');


        $purchase_order = [
            "store_id" => $request->logged_user_store_id,
            "po_number" => $request->po_number,
            "po_reference" => $request->po_reference,
            "order_date" => $request->order_date,
            "order_due_date" => $request->order_due_date,
            "supplier_id" => $supplier_data->id,
            "supplier_code" => $supplier_data->supplier_code,
            "supplier_name" => $supplier_data->name,
            "business_account_id" => $business_account->id,
            "supplier_address" => $supplier_data->address . ', ' . $supplier_data->pincode,
            "currency_name" => $currency_data->currency_name,
            "currency_code" => $currency_data->currency_code,
            "subtotal_excluding_tax" => $total_amount_before_additional_discount,
            "total_discount_amount" => $additional_discount_amount,
            "total_after_discount" => $total_amount_after_discount,
            "discount_type" => $request->discount_type,
            "discount_rate" => $request->discount_rate,
            "total_tax_amount" => $total_tax_amount,
            "shipping_charge" => $shipping_charge,
            "packing_charge" => $packing_charge,
            "update_stock" => ($request->update_stock == true) ? 1 : 0,
            "terms" => $request->terms,
            "total_order_amount" => $total_order_amount,
            "tax_option_id" => $tax_option_data['tax_option_id'],
            'status' => 4
        ];
       
        return [
            'po_data' => $purchase_order,
            'po_products' => $po_products
        ];
    }

    public function calculate_tax($item_total, $tax_percentage)
    {
        $tax_amount = ($tax_percentage / 100) * $item_total;
        return $tax_amount;
    }

    public function calculate_discount($item_total, $discount_percentage)
    {
        $discount_amount = ($discount_percentage / 100) * $item_total;
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

    public function filter_quantity_purchases(Request $request)
    {
        try {

            $keyword = $request->keyword;

            $po_list = QuantityPurchaseModel::select("*")
                ->where('po_number', 'like', $keyword . '%')
                ->orWhere('po_reference', 'like', $keyword . '%')
                ->orWhere('supplier_code', 'like', $keyword . '%')
                ->orWhere('supplier_name', 'like', $keyword . '%')
                ->limit(25)
                ->get();

            $pos = QuantityPurchaseResource::collection($po_list);

            return response()->json($this->generate_response(
                array(
                    "message" => "Quantity Purchases filtered successfully",
                    "data" => $pos
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

        $validator = Validator::make($request->all(), [
            'supplier' => $this->get_validation_rules("slack", true),
            'po_number' => 'max:50|required',
            'po_reference' => 'max:30',
            'order_date' => 'date|nullable',
            'order_due_date' => 'date|nullable|after_or_equal:order_date',
            'terms' => $this->get_validation_rules("text", false),
            'products.*.name' => $this->get_validation_rules("name_label", true),
            'products.*.quantity' => 'min:1|' . $this->get_validation_rules("numeric", true),
            'products.*.unit_price' => $this->get_validation_rules("numeric", true),
            'products.*.discount_percentage' => $this->get_validation_rules("numeric", false),
            'products.*.tax_percentage' => $this->get_validation_rules("numeric", false)
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function validate_request2($request)
    {
        $request->merge(['products' => json_decode($request->products, true)]);

        $validator = Validator::make($request->all(), [
            //            'supplier' => $this->get_validation_rules("slack", true),
            'po_number' => 'max:50|required',
            'po_reference' => 'max:30',
            'order_date' => 'date|nullable',
            //            'order_due_date' => 'date|nullable|after_or_equal:order_date',
            'terms' => $this->get_validation_rules("text", false),
            //            'products.*.name' => $this->get_validation_rules("name_label", true),
            //            'products.*.quantity' => 'min:1|'.$this->get_validation_rules("numeric", true),
            //            'products.*.unit_price' => $this->get_validation_rules("numeric", true),
            //            'products.*.discount_percentage' => $this->get_validation_rules("numeric", false),
            //            'products.*.tax_percentage' => $this->get_validation_rules("numeric", false)
        ]);
        $validation_status = $validator->fails();
        if ($validation_status) {
            throw new Exception($validator->errors());
        }
    }

    public function update_stock_from_po(Request $request, $slack, $po_status, $po_update = false)
    {

        $store_id = UserModel::find(session('user_id'))->store_id;
        $account_type_data = MasterAccountTypeModel::select('id')
            ->where('account_type_constant', '=', 'EXPENSE')
            ->first();
        if (empty($account_type_data)) {
            throw new Exception(trans("Invalid Account type selected"), 400);
        }

        $business_account = AccountModel::select('id')
            ->where('store_id', $store_id)
            ->where('account_type', $account_type_data->id)
            ->active()
            ->first();
        if (empty($business_account)) {
            throw new Exception(trans("Invalid Business Account selected"), 400);
        }

        $po_data = QuantityPurchaseModel::select('*')->where('slack', $slack)->first();
        if (empty($po_data)) {
            throw new Exception(trans("Invalid Quantity Purchase provided"), 400);
        }

        if ($po_data->update_stock == 0) {
            return false;
        }

        $purchase_data['business_account_id'] = $business_account->id;
        QuantityPurchaseModel::where('slack', $slack)
        ->update($purchase_data);
        $purchase_order_data = new QuantityPurchaseResource($po_data);

        $products = $purchase_order_data->products;
        dd($products);

        if (count($products) > 0) {
            foreach ($products as $product) {

                if ($product->product_id != '' && $product->quantity > 0) {

                    if ($po_update == false) {
                        if ($product->stock_update == 0 && $po_status == 'CLOSED') {
                            $product_data = ProductModel::find($product->product_id);
                            $product_data->increment('quantity', $product->quantity);

                            $item = [];
                            $item['stock_update'] = 1;
                            $item['updated_at'] = now();
                            $item['updated_by'] = $request->logged_user_id;
                            QuantityPurchaseProductModel::where('id', $product->id)
                                ->update($item);
                        }

                        if ($product->stock_update == 1 && $po_status != 'CLOSED') {
                            $product_data = ProductModel::find($product->product_id);
                            $product_data->decrement('quantity', $product->quantity);

                            $item = [];
                            $item['stock_update'] = 0;
                            $item['updated_at'] = now();
                            $item['updated_by'] = $request->logged_user_id;
                            QuantityPurchaseProductModel::where('id', $product->id)
                                ->update($item);
                        }
                    }
                    if ($po_update == true) {

                        if ($product->stock_update == 1 && $po_status == 'CLOSED') {
                            $product_data = ProductModel::find($product->product_id);
                            dd($product->quantity);
                            $product_data->decrement('quantity', $product->quantity);

                            $item = [];
                            $item['stock_update'] = 0;
                            $item['updated_at'] = now();
                            $item['updated_by'] = $request->logged_user_id;
                            QuantityPurchaseProductModel::where('id', $product->id)
                                ->update($item);
                        }

                        if ($product->stock_update == 0 && $po_status == 'CLOSED') {
                            $product_data = ProductModel::find($product->product_id);
                            $product_data->increment('quantity', $product->quantity);

                            $item = [];
                            $item['stock_update'] = 1;
                            $item['updated_at'] = now();
                            $item['updated_by'] = $request->logged_user_id;
                            QuantityPurchaseProductModel::where('id', $product->id)
                                ->update($item);
                        }
                    }
                }
            }
        }
        exit;
    }


    public function generate_invoice_from_po(Request $request, $slack)
    {


        try {
            $purchase_order = QuantityPurchaseModel::where('slack', '=', $slack)->first();

            if (empty($purchase_order)) {
                throw new Exception(trans("Unable to fetch Quantity Purchase details"), 400);
            }

            $purchase_order_data = new QuantityPurchaseResource($purchase_order);
            $purchase_order_data_decoded = json_decode(json_encode($purchase_order_data, true));

            $request->request->add([
                'bill_to' => 'SUPPLIER',
                'bill_to_slack' => $purchase_order_data_decoded->supplier->slack,
                'currency' => $purchase_order_data_decoded->currency_code,
                'invoice_date' => $purchase_order_data_decoded->order_date_raw,
                'invoice_due_date' => $purchase_order_data_decoded->order_due_date_raw,
                'invoice_reference' => 'FPO-' . strtoupper(Str::random(6)),
                'packing_charge' => $purchase_order_data_decoded->packing_charge,
                'shipping_charge' => $purchase_order_data_decoded->shipping_charge,
                'tax_option' => ($purchase_order_data_decoded->tax_option_data != null) ? $purchase_order_data_decoded->tax_option_data->tax_option_constant : 'DEFAULT_TAX',
                'terms' => $purchase_order_data_decoded->terms,
                'total_discount_amount' => $purchase_order_data_decoded->total_discount_amount,
                'total_after_discount' => $purchase_order_data_decoded->total_after_discount,
                'total_order_amount' => $purchase_order_data_decoded->total_order_amount,
            ]);

            $po_products = collect($purchase_order_data_decoded->products);
            $products = $po_products->map(function ($item, $key) {
                return [
                    'slack' => $item->product_slack,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->amount_excluding_tax,
                    'discount_percentage' => $item->discount_percentage,
                    'tax_percentage' => $item->tax_percentage,
                    'amount' => $item->total_amount,
                ];
            });

            $request->request->add([
                'products' => json_encode($products)
            ]);

            $invoice_api = new InvoiceAPI();
            $response = $invoice_api->store($request);
            $response = $response->getData();

            if ($response->status_code == 0 || $response->status_code == 400) {
                throw new Exception(trans($response->msg));
            }

            $invoice_link = route('purchase_invoice_detail', ['slack' => $response->data]);

            return response()->json($this->generate_response(
                array(
                    "message" => $response->msg,
                    "data" => $response->data,
                    "link" => $invoice_link,
                    "new_tab" => true
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

    public function load_products(Request $request)
    {

        $query = ProductModel::active();
        if ($request->category_id != 0) {
            $query->where('category_id', $request->category_id);
        }
        $product_data = $query->get();
        $product_count = $product_data->count();

        if ($product_count) {

            $response = [
                'status' => true,
                'msg' => $product_count . ' Products Found',
                'data' => $product_data,
            ];
        } else {

            $response = [
                'status' => false,
                'msg' => 'No Products Found',
                'data' => '',
            ];
        }

        return response()->json($response);
    }
    
    public function load_products_by_store(Request $request)
    {

        $product_data = ProductModel::active()->where('store_id',$request->store_id)->sortNameAsc()->get();
        $product_count = $product_data->count();

        if ($product_count) {

            $response = [
                'status' => true,
                'msg' => $product_count . ' Products Found',
                'data' => $product_data,
            ];
        } else {

            $response = [
                'status' => false,
                'msg' => 'No Products Found',
                'data' => '',
            ];
        }

        return response()->json($response);
    }

    public function record_order_payment_transaction($id)
    {
     
        $order_detail = QuantityPurchaseModel::select('*')->where('id', $id)->first();


        $transaction_type_data = MasterTransactionTypeModel::select('id')
            ->where('transaction_type_constant', '=', 'EXPENSE')
            ->first();
        if (empty($transaction_type_data)) {
            throw new Exception("Invalid transaction type selected", 400);
        }

        $transaction = [
            "slack" => $this->generate_slack("transactions"),
            "store_id" => $order_detail->store_id,
            "transaction_code" => Str::random(6),
            "account_id" => $order_detail->business_account_id,
            "transaction_type" => $transaction_type_data->id,
            "payment_method_id" => 3,
            "payment_method" => 'Cash',
            "bill_to" => 'SUPPLIER',
            "bill_to_id" => $order_detail->id,
            "bill_to_name" => (isset($order_detail->supplier_name)) ? $order_detail->supplier_name : 'Walkin Customer',
            "bill_to_contact" => (isset($order_detail->supplier->phone)) ? $order_detail->supplier->phone : '',
            "bill_to_address" => (isset($order_detail->supplier->address)) ? $order_detail->supplier->address : '',
            "currency_code" => $order_detail->currency_code,
            "amount" => $order_detail->total_order_amount,
            "pg_transaction_id" => '',
            "pg_transaction_status" => '',
            "notes" => '',
            "transaction_date" => date('Y-m-d'),
            "created_by" => request()->logged_user_id
        ];

        $transaction_id = TransactionModel::create($transaction)->id;

        $code_start_config = Config::get('constants.unique_code_start.transaction');
        $code_start = (isset($code_start_config)) ? $code_start_config : 100;

        $transaction_code = [
            "transaction_code" => ($code_start + $transaction_id)
        ];
        TransactionModel::where('id', $transaction_id)
            ->update($transaction_code);

        return $transaction_id;
    }
}
