@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">

        <div class="d-flex flex-wrap mb-4">

            <div class="mr-auto">
                <span class="text-title">{{ __("Products") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_PRODUCT'), true))
                <a href="{{ route('add_ingredient')}}" role="button" class="btn btn-success">{{ __("New Ingredient") }}</a>
                <!-- <a href="{{ route('add_product')}}" role="button" class="btn btn-primary">{{ __("New Product") }}</a> -->
                <a data-toggle="modal" data-target="#addProductModal" role="button" class="btn btn-primary">{{ __("New Product") }}</a>
                @endif
            </div>
        </div>

        <div class="form-row mb-1">
            {{-- @if($restaurant_mode) --}}
            <div class="form-group col-md-3">
                <label for="product_type_filter">{{ __("Filter Product") }}</label>
                <select name="product_type_filter" id="product_type_filter" class="form-control form-control-custom custom-select">
                    <option value="all">{{ __("All") }}</option>
                    <option value="billing_products" selected>{{ __("Billing Products") }}</option>
                    <option value="ingredients">{{ __("Ingredients") }}</option>
                </select>
            </div>
            {{-- @endif --}}
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Product Code") }}</th>
                        <th>{{ __("Thumbnail")}}</th>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Supplier") }}</th>
                        <th>{{ __("Category") }}</th>
                        <th>{{ __("Discount") }}</th>
                        <th>{{ __("Quantity") }}</th>
                        <th>{{ __("Amount") }}</th>
                        <th>{{ __("Is Taxable") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Created On") }}</th>
                        <th>{{ __("Updated On") }}</th>
                        <th>{{ __("Created By") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal -->

<div style="display: none;" id="addProductModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title">{{ __("Add Product") }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="name">{{ __("Name") }}</label>
                    <input type="text" name="product_name" class="form-control" id="product_name" placeholder="{{ __("Product Name") }}">
                </div>
                <div class="form-group">
                    <label for="arabic_name">{{ __("Arabic Name") }}</label>
                    <input type="text" name="arabic_name" class="form-control" id="arabic_name" placeholder="{{ __("Product Arabic Name") }}">
                </div>
                <div class="form-group">
                    <label for="product_code">{{ __("Product Code") }}</label>
                    <input type="text" name="product_code" class="form-control" id="product_code" placeholder="{{ __("Product Code") }}">
                </div>
                <div class="form-group">
                    <label for="category">{{ __("Category") }}</label>
                    <select class="form-control" name="category_id" id="category_id" placeholder="{{ __("Choose Category..") }}">

                        <option value="">{{ __("Choose Category..") }} </option>

                        @foreach ($main_categories as $key => $value)
                        <option value="{{ $value->id }}">
                            {{ $value->label }} @if($value->label_ar != '') | {{ $value->label_ar }} @endif
                        </option>
                        @endforeach
                    </select>
                    <!-- <input type="text" name="category" class="form-control"> -->
                </div>

                <div class="form-group">
                    <label for="email">{{ __("Purchase Price Excluding Tax (SAR)") }}</label>
                    <input type="number" name="purchase_price" class="form-control" id="purchase_price" placeholder="{{ __("Purchase Price") }}">
                </div>

                <div class="form-group">
                    <label for="email">{{ __("Sale Price Excluding Tax (SAR)") }}</label>
                    <input type="number" name="sales_price" class="form-control" id="sales_price" placeholder="{{ __("Sale Price") }}">
                </div>

                <div class="form-group">
                    <label for="email">{{ __("Quantity") }}</label>
                    <input type="number" name="quantity" class="form-control" id="quantity" placeholder="{{ __("Quantity") }}">
                </div>


                <div class="form-group">

                    <label for="tax_code"> {{ __("Choose Tax") }}

                      <label id="display_tobacco_tax" for="is_tobacco_tax" style="margin:0px;">
                        <input type="checkbox" id="is_tobacco_tax" name="is_tobacco_tax" style="margin-left:160px;" />
                        {{ __("Tobacco Tax") }}
                      </label>
                    </label>

                    <select class="form-control" name="tax_code" id="tax_code_id" placeholder="{{ __("Choose Tax..") }}">

                        <option value="">{{ __("Choose Tax..") }} </option>

                        @foreach ($taxcodes as $key => $value)
                        <option value="{{ $value->id }}" {{ $value->id == session('store_tax_code') ? ' selected' : '' }}>
                            {{ $value->label }} @if($value->label_ar != '') | {{ $value->label_ar }} @endif - {{ $value->total_tax_percentage }}
                        </option>
                        @endforeach

                    </select>
                  
                </div>

            </div>
            <div class="modal-footer">
                <a  role="button" class="btn btn-primary" onclick="advanceRedirect()">{{ __("Advance") }}</a>
                <button type="submit" class="btn btn-default btn-success" onclick="submitForm()">{{ __("Save") }}</button>
            </div>

        </div>
    </div>
</div>



@endsection

@push('scripts')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/pages/products.js') }}"></script>
<script src="{{ asset('js/common.dataTable.text.js') }}"></script>

<script>
    'use strict';
    var products = new Products();
    var commonDatatable = new CommonDatatableLanguage;
    var commonDatatablesData = commonDatatable.commonDatatable();
    products.load_listing_table(commonDatatablesData);

    $(document).on('change', '#product_type_filter', function() {
        var product_type = $(this).val();
        event = new CustomEvent("product_type_filter", {
            "detail": product_type
        });
        document.dispatchEvent(event);
    });

    $(document).ready(function() {
        var product_type = $('#product_type_filter').val();
        console.log(product_type);
        
        $('#display_tobacco_tax').hide();

        event = new CustomEvent("product_type_filter", {
            "detail": product_type
        });
        document.dispatchEvent(event);



        $('#addProductModal').on('hidden.bs.modal', function(e) {
            $(this)
                .find("input")
                .val('')
                .end();
            jQuery('.alert-danger').hide();
        })


    });

    $(document).on('change', '#tax_code_id', function() {
        var tax_code = $(this).val();
        var tax = $('#tax_code_id option:selected').text().trim();
        var tax_percentage = tax.split("-")[1].trim();
        $('#is_tobacco_tax').prop('checked', false);
        if(tax_percentage == 15)
        {
            $('#display_tobacco_tax').show();
        }
        else
        {
            $('#display_tobacco_tax').hide();
        }
    });

    function advanceRedirect()
    {

        var name = $('#product_name').val();
        var arabic_name = $('#arabic_name').val();
        var product_code = $('#product_code').val();
        var category_id = $('#category_id').val();
        var purchase_price = $('#purchase_price').val();
        var sales_price = $('#sales_price').val();
        var quantity = $('#quantity').val();
        var tax_code_id = $('#tax_code_id').val();
        var is_tobacco_tax = $('#is_tobacco_tax').prop('checked') ? 1 : 0;

        jQuery('.alert-danger').html('');

      

        var formData = {
            "product_name": name,
            "arabic_product_name":arabic_name,
            "product_code": product_code,
            "main_category": category_id,
            "purchase_price": purchase_price,
            "sale_price": sales_price,
            "quantity": quantity,
            "tax_code_id": tax_code_id,
            "is_tobacco_tax": is_tobacco_tax,
            "is_taxable": true,
            "unlimited_quantity": false,
            "status": 1,
            "shows_in": 3,
            "is_ingredient": 0,
            "is_ingredient_price": 0,
            "zid_sync_option": false

        }
        localStorage.removeItem("add_product");
        localStorage.setItem("add_product", JSON.stringify(formData));
        window.location.href = "{{ route('add_product')}}";
    }

    function submitForm() {

        var name = $('#product_name').val();
        var arabic_name = $('#arabic_name').val();
        var product_code = $('#product_code').val();
        var category_id = $('#category_id').val();
        var purchase_price = $('#purchase_price').val();
        var sales_price = $('#sales_price').val();
        var quantity = $('#quantity').val();
        var tax_code_id = $('#tax_code_id').val();
        var is_tobacco_tax = $('#is_tobacco_tax').prop('checked') ? 1 : 0;

        jQuery('.alert-danger').html('');

        if (name == "") {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li> Name is required </li>');
            return false;
        }
        // if (product_code == "") {
        //     jQuery('.alert-danger').show();
        //     jQuery('.alert-danger').append('<li> Product Code is required </li>');
        //     return false;
        // }
        if (category_id == "" || category_id == "Choose Category.." ) {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li> Category is required </li>');
            return false;
        }
        if (purchase_price == "") {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li> Purchase Price is required </li>');
            return false;
        }
        if (sales_price == "") {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li> Sales Price is required </li>');
            return false;
        }
        if (quantity == "") {
            jQuery('.alert-danger').show();
            jQuery('.alert-danger').append('<li> Quantity is required </li>');
            return false;
        }


        var access_token = window.settings.access_token;
        var formData = {
            "product_name": name,
            "arabic_product_name":arabic_name,
            "product_code": product_code,
            "access_token": access_token,
            "main_category": category_id,
            "purchase_price": purchase_price,
            "sale_price": sales_price,
            "quantity": quantity,
            "tax_code_id": tax_code_id,
            "is_tobacco_tax": is_tobacco_tax,
            "is_taxable": true,
            "unlimited_quantity": false,
            "status": 1,
            "shows_in": 3,
            "is_ingredient": 0,
            "is_ingredient_price": 0,
            "zid_sync_option": false

        }
   

        $.ajax({
            type: "POST",
            url: '/api/add_product',
            cache: false,
            data: formData,
            success: function(response) {
                if (response.status_code == 200) {
                    if (response.data == 'error') {
                        alert(response.msg);
                     
                    } else {
                        alert(response.msg);
                        $('#addProductModal').modal('hide');
                        window.location.reload();
                    }
                } else {
                    alert(response.msg);
                  
                    console.log('Error');
                }
            },
            error: function() {
                alert("Error");
            }
        });
    }
</script>
@endpush