<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Controller
{
    public function index(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_IMPORT';
        $data['sub_menu_key'] = 'SM_IMPORT_DATA';
        check_access(array($data['menu_key'],$data['sub_menu_key']));

        $restaurant_mode = $request->logged_user_store_restaurant_mode;
        
        $options = [];
        $templates = [];
        if(check_access(['A_UPLOAD_USER'], true)){
            $options[] = [ 'key' => 'USER', 'value' => 'Users'];
            $format_file = Config::get('constants.upload.imports.user_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'User'];
        }
        if(check_access(['A_UPLOAD_STORE'], true)){
            $options[] = [ 'key' => 'STORE', 'value' => 'Stores'];
            $format_file = Config::get('constants.upload.imports.store_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Store'];
        }
        if(check_access(['A_UPLOAD_SUPPLIER'], true)){
            $options[] = [ 'key' => 'SUPPLIER', 'value' => 'Suppliers'];
            $format_file = Config::get('constants.upload.imports.supplier_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Supplier'];
        }
        if(check_access(['A_UPLOAD_CATEGORY'], true)){
            $options[] = [ 'key' => 'CATEGORY', 'value' => 'Categories'];
            $format_file = Config::get('constants.upload.imports.category_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Category'];
        }
        if(check_access(['A_UPLOAD_PRODUCT'], true)){
            $options[] = [ 'key' => 'PRODUCT', 'value' => 'Products'];
            $format_file = Config::get('constants.upload.imports.product_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Product'];
        }
        if(check_access(['A_UPLOAD_INGREDIENT'], true) && $restaurant_mode == 1){
            $options[] = [ 'key' => 'INGREDIENT', 'value' => 'Ingredient'];
            $format_file = Config::get('constants.upload.imports.ingredient_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Ingredient'];
        }
        $data['upload_options'] = $options;
        $data['templates'] = $templates;
        return view('import.import_data', $data);
    }

    public function update_data(Request $request)
    {
        //check access
        $data['menu_key'] = 'MM_IMPORT';
        $data['sub_menu_key'] = 'SM_UPDATE_DATA';
        check_access(array($data['menu_key'],$data['sub_menu_key']));
        
        $restaurant_mode = $request->logged_user_store_restaurant_mode;

        $options = [];
        $templates = [];
        if(check_access(['A_UPDATE_USER'], true)){
            $options[] = [ 'key' => 'USER', 'value' => 'Users'];
            $format_file = Config::get('constants.upload.updates.user_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'User'];
        }
        if(check_access(['A_UPDATE_STORE'], true)){
            $options[] = [ 'key' => 'STORE', 'value' => 'Stores'];
            $format_file = Config::get('constants.upload.updates.store_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Store'];
        }
        if(check_access(['A_UPDATE_SUPPLIER'], true)){
            $options[] = [ 'key' => 'SUPPLIER', 'value' => 'Suppliers'];
            $format_file = Config::get('constants.upload.updates.supplier_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Supplier'];
        }
        if(check_access(['A_UPDATE_CATEGORY'], true)){
            $options[] = [ 'key' => 'CATEGORY', 'value' => 'Categories'];
            $format_file = Config::get('constants.upload.updates.category_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Category'];
        }
        if(check_access(['A_UPDATE_PRODUCT'], true)){
            $options[] = [ 'key' => 'PRODUCT', 'value' => 'Products'];
            $format_file = Config::get('constants.upload.updates.product_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Product'];
        }
        if(check_access(['A_UPDATE_INGREDIENT'], true) && $restaurant_mode == 1){
            $options[] = [ 'key' => 'INGREDIENT', 'value' => 'Ingredient'];
            $format_file = Config::get('constants.upload.updates.ingredient_format');
            $templates[] = ['template_link' =>  asset($format_file), 'template_label' => 'Ingredient'];
        }
        $data['upload_options'] = $options;
        $data['templates'] = $templates;
        return view('import.update_data', $data);
    }

    public function add_import_product(){

        return view('import/import_product');

    }

    public function save_import_product(Request $request){

        Excel::import(new ProductsImport, $request->file('product_file'));
        
        return redirect('/')->with('success', 'All good!');

    }
}