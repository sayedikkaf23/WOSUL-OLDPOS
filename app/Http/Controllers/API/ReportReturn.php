<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Exception;
use Validator;

use Illuminate\Support\Str;

use App\Imports\DataImport;
use Illuminate\Support\Facades\DB;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

use App\Exports\TransactionExport;
use App\Exports\ReturnOrderExport;

use App\Models\OrderProduct as OrderProductModel;
use App\Models\Category as CategoryModel;
use App\Models\Product as ProductModel;

use App\Http\Resources\ProductResource;

class ReportReturn extends Controller
{

    public function __construct(Request $request) {
        $this->view_path = Config::get('constants.upload.reports.view_path');
        $this->date = ($request->date != '')? $request->date : date("Y-m");
    }
    public function return_order_report(Request $request){
        try {
            $params = [
                'from_created_date' => $request->from_created_date,
                'to_created_date' => $request->to_created_date,
                'status' => $request->status,
            ];

            $filename = 'return_order_report_'.date('Y_m_d_h_i_s').'_'.uniqid().'.xlsx';

            Excel::store(
                new ReturnOrderExport(
                    $params
                ),
                'public/reports/'.$filename
            );
            
            $download_link = asset($this->view_path.$filename);

            return response()->json($this->generate_response(
                array(
                    "message" => "Return order report generated successfully",
                    "data" => '',
                    "link" => $download_link
                ), 'SUCCESS'
            ));

        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }
    }
}
