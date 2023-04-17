<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EdfapayTransaction as EdfaTransactionModel;

class EdfaTransaction extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try {
            DB::beginTransaction();

            $data = [];
            $dataset = [
                "order_id" => $request->order_id>0?$request->order_id:0,
                "order_json" => $request->order_json!=null?$request->order_json:null,
                "transaction_id" => $request->transaction_id,
                "amount" => $request->amount,
                "status" => $request->status,
            ];
            EdfaTransactionModel::create($dataset);
            $data[] = $dataset;

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("EDFAPay transaction data inserted successfully"),
                ),
                'SUCCESS'
            ));

        }catch (Exception $e) {
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //Update status
    public function update_status(Request $request){
        DB::beginTransaction();

        $update = [
            'status'=>$request->status,
            'amount'=>$request->amount
        ];
        EdfaTransactionModel::where('transaction_id',$request->transaction_id)->update($update);
        DB::commit();

        return response()->json($this->generate_response(
            array(
                "message" => trans("EDFAPay transaction data updated successfully"),
            ),
            'SUCCESS'
        ));
    }

    //Check Transaction Status
    public function check_transaction_status(Request $request){
        DB::beginTransaction();
        $data = [];
        $transaction = EdfaTransactionModel::where('transaction_id',$request->transaction_id)->first();
        DB::commit();

        return response()->json($this->generate_response(
            array(
                "message" => trans("Status get successfully!"),
                "data"=>array('transaction_status'=>$transaction->status)
            ),
            'SUCCESS'
        ));
    }
}
