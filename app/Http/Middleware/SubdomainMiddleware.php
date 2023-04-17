<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User as UserModel;
use App\Models\Discountcode as DiscountcodeModel;
use Illuminate\Support\Facades\DB;

class SubdomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // \Artisan::call('cache:clear');

        $merchant_id = 0;
        $merchant_email = UserModel::find(2);

        if (isset($merchant_email)) {
            $merchant_email = $merchant_email->email;
            $connect = mysqli_connect('localhost', config('database.connections.mysql.username'), config('database.connections.mysql.password'), 'wosul_admin');
            $merchant_id = mysqli_query($connect, 'SELECT id FROM merchants WHERE email = "' . $merchant_email . '" ');
            if (mysqli_num_rows($merchant_id) > 0) {
                $merchant_id = mysqli_fetch_assoc($merchant_id);
                $merchant_id = $merchant_id['id'];
            }
            mysqli_close($connect);
        }

        if ($merchant_id > 0 || $merchant_id != '') {

            // Updating merchant id 
            config(['constants.config.merchant_id' => $merchant_id]);

            // Updating Filesystem Configuration based on Logged in Merchant
            config([

                'filesystems.disks.profile.root' => storage_path('app/public/' . $merchant_id . '/profile'),
                'filesystems.disks.profile.url' => env('APP_URL') . '/storage/' . $merchant_id . '/profile',

                'filesystems.disks.imports.root' => storage_path('app/public/' . $merchant_id . '/imports'),
                'filesystems.disks.imports.url' => env('APP_URL') . '/storage/' . $merchant_id . '/imports',

                'filesystems.disks.updates.root' => storage_path('app/public/' . $merchant_id . '/updates'),
                'filesystems.disks.updates.url' => env('APP_URL') . '/storage/' . $merchant_id . '/updates',

                'filesystems.disks.reports.root' => storage_path('app/public/' . $merchant_id . '/reports'),
                'filesystems.disks.reports.url' => env('APP_URL') . '/storage/' . $merchant_id . '/reports',

                'filesystems.disks.barcode.root' => storage_path('app/public/' . $merchant_id . '/barcode'),
                'filesystems.disks.barcode.url' => env('APP_URL') . '/storage/' . $merchant_id . '/barcode',

                'filesystems.disks.category.root' => storage_path('app/public/' . $merchant_id . '/category'),
                'filesystems.disks.category.url' => env('APP_URL') . '/storage/' . $merchant_id . '/category',

                'filesystems.disks.company.root' => storage_path('app/public/' . $merchant_id . '/company'),
                'filesystems.disks.company.url' => env('APP_URL') . '/storage/' . $merchant_id . '/company',

                'filesystems.disks.store.root' => storage_path('app/public/' . $merchant_id . '/store'),
                'filesystems.disks.store.url' => env('APP_URL') . '/storage/' . $merchant_id . '/store',

                'filesystems.disks.product.root' => storage_path('app/public/' . $merchant_id . '/product'),
                'filesystems.disks.product.url' => env('APP_URL') . '/storage/' . $merchant_id . '/product',

                'filesystems.disks.invoice.root' => storage_path('app/public/' . $merchant_id . '/invoice'),
                'filesystems.disks.invoice.url' => env('APP_URL') . '/storage/' . $merchant_id . '/invoice',

                'filesystems.disks.order.root' => storage_path('app/public/' . $merchant_id . '/order'),
                'filesystems.disks.order.url' => env('APP_URL') . '/storage/' . $merchant_id . '/order',

                'filesystems.disks.invoice_return.root' => storage_path('app/public/' . $merchant_id . '/invoice_return'),
                'filesystems.disks.invoice_return.url' => env('APP_URL') . '/storage/' . $merchant_id . '/invoice_return',

            ]);

            // Updating Filesystem Configuration based on Logged in Merchant
            config([

                'constants.upload.profile.dir' => $merchant_id . '/profile/',
                'constants.upload.profile.view_path' => '/storage/' . $merchant_id . '/profile/',
                'constants.upload.profile.upload_path' => 'storage/' . $merchant_id . '/profile/',

                'constants.upload.company.dir' => $merchant_id . '/company/',
                'constants.upload.company.view_path' => '/storage/' . $merchant_id . '/company/',
                'constants.upload.company.upload_path' => 'storage/' . $merchant_id . '/company/',

                'constants.upload.category.dir' => $merchant_id . '/category/',
                'constants.upload.category.view_path' => '/storage/' . $merchant_id . '/category/',
                'constants.upload.category.upload_path' => 'storage/' . $merchant_id . '/category/',

                'constants.upload.imports.dir' => $merchant_id . '/imports/',
                'constants.upload.imports.view_path' => '/storage/' . $merchant_id . '/imports/',
                'constants.upload.imports.upload_path' => 'storage/' . $merchant_id . '/imports/',

                'constants.upload.updates.dir' => $merchant_id . '/updates/',
                'constants.upload.updates.view_path' => '/storage/' . $merchant_id . '/updates/',
                'constants.upload.updates.upload_path' => 'storage/' . $merchant_id . '/updates/',

                'constants.upload.barcode.dir' => $merchant_id . '/barcode/',
                'constants.upload.barcode.view_path' => '/storage/' . $merchant_id . '/barcode/',
                'constants.upload.barcode.upload_path' => 'storage/' . $merchant_id . '/barcode/',

                'constants.upload.reports.dir' => $merchant_id . '/reports/',
                'constants.upload.reports.view_path' => '/storage/' . $merchant_id . '/reports/',
                'constants.upload.reports.upload_path' => 'storage/' . $merchant_id . '/reports/',

                'constants.upload.product.dir' => $merchant_id . '/product/',
                'constants.upload.product.view_path' => '/storage/' . $merchant_id . '/product/',
                'constants.upload.product.upload_path' => 'storage/' . $merchant_id . '/product/',

                'constants.upload.order.dir' => $merchant_id . '/order/',
                'constants.upload.order.view_path' => '/storage/' . $merchant_id . '/order/',
                'constants.upload.order.upload_path' => 'storage/' . $merchant_id . '/order/',

                'constants.upload.invoice_return.dir' => $merchant_id . '/invoice_return/',
                'constants.upload.invoice_return.view_path' => '/storage/' . $merchant_id . '/invoice_return/',
                'constants.upload.invoice_return.upload_path' => 'storage/' . $merchant_id . '/invoice_return/',

            ]);
        }
        $this->updateDiscountCodes($request->session()->get("store_id"));
        return $next($request);
    }

    public function updateDiscountCodes($storeid){
         if($storeid!="")
         { 
            $this->get_discounts($storeid);
         }
    }


    public function get_discounts($storeid){
        $discountcodequery = "select * from discount_codes";
        $discountcodes = DB::select($discountcodequery);
        $currentdate = date("Y-m-d H:i:sa");
        $filedata = "\n\nIn Store: {$storeid}";
        $filedata .="\n\n Fetching Discount code list : ".$discountcodequery;
        foreach($discountcodes as $code)
        {
            
            $query = "select * from discount_codes where id=".trim($code->id)." 
            and ('{$currentdate}' between discount_start_date and discount_end_date or is_always=1)
            and (limit_on_discount>0 or limit_on_discount=-1)
            and status=1";
            $filedata .= "\n\n Query To select Discount code to update : {$query}"; 
            $discountcodelist = DB::select($query);

                                                           
       if(count($discountcodelist)==0 && $code->discount_start_date <= $currentdate)
       {
          $discountupdatequery = "update discount_codes set status=0 where id=".trim($code->id);
          $filedata .= "\n\nUpdation Query : {$discountupdatequery}";
          DB::update($discountupdatequery);
          $product_list = DB::select("select * from products where discount_code_id=".trim($code->id));
          foreach($product_list as $product)
          {
            DB::update("update products set discount_code_id=-1 where id=".$product->id);
          }
          DB::commit();
       }
       else
       {
          DB::update("update discount_codes set status=1 where id=".trim($code->id));
          DB::commit();
       }
       file_put_contents(storage_path('discountcodeupdate_'.$storeid.'.txt'),$filedata);
    }
    
    }

}