<?php

namespace App\Http\Controllers\API;

use App\Models\QoyodCategory;
use Exception;
use Validator;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\MainCategoryResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Category as CategoryModel;
use App\Models\MainCategory as MainCategoryModel;
use App\Models\Store as StoreModel;

use App\Http\Resources\Collections\CategoryCollection;

use App\Http\Traits\ZidApiTrait;
use Illuminate\Support\Facades\App;
use App\Http\Traits\QoyodApiTrait;

class Expresspay extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function setting(Request $request)
    {
        try {

            DB::beginTransaction();

            DB::table('setting_app')->update([
                'expresspay_merchant_key' => $request->merchant_key,
                'expresspay_password' => $request->password,
                'expresspay_sms_template' => $request->sms_template,
            ]);

            DB::commit();

            return response()->json($this->generate_response(
                array(
                    "message" => trans("Expresspay Setting Updated Successfully"),
                    "data"    => []
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
