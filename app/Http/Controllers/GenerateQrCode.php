<?php

namespace App\Http\Controllers;

use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\MasterStatus as MasterStatusModel;
use App\Models\MasterTaxOption as MasterTaxOptionModel;
use App\Models\Product as ProductModel;
use App\Models\Store;
use App\Models\User;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Auth;
use QrCode;
class GenerateQrCode extends Controller
{
    //This is the function that loads the listing page
    public function index(Request $request){
        //check access
        $data['menu_key'] = 'MM_QR_CODE';
        $data['sub_menu_key'] = 'MM_QR_CODE';
      
        check_access(array($data['menu_key']));
        $data['user_id']=session()->get('user_id')!=null?session()->get('user_id'):'';
        $user=User::where('id',$data['user_id'])->first();
       	$data['qrcode']=0;
        $data['restaurant_id']="";
        $data['store_id']=isset($user->store_id)?$user->store_id:'';
       	if($data['store_id']){
       	 
         $qrcode= DB::table('qr_codes')->select('id','restaurant_id')->where('store_id','=',$user->store_id)->get();
         $data['qrcode']=$qrcode->count();

         if($data['qrcode'])
          $data['restaurant_id']=$qrcode[0]->restaurant_id;
        
       	 $store_name=Store::where('id',$user->store_id)->first()->name;

       	 $data['store_url']=env('WOSULIN_URL').'restaurant/'.$this->makeAlias($store_name);

       	}
       
        return view('qr_code.qrcode', $data);
    }

    

    public function makeAlias($name)   {
        $cyr = [
            'ж',  'ч',  'щ',   'ш',  'ю',  'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ъ', 'ь', 'я',
            'Ж',  'Ч',  'Щ',   'Ш',  'Ю',  'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ъ', 'Ь', 'Я', ];
        $lat = [
            'zh', 'ch', 'sht', 'sh', 'yu', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'y', 'x', 'q',
            'Zh', 'Ch', 'Sht', 'Sh', 'Yu', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'c', 'Y', 'X', 'Q', ];
        $name = str_replace($cyr, $lat, $name);

        return strtolower(preg_replace('/[^A-Za-z0-9]/', '', $name));
    }



    
}
