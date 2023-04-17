@extends('layouts.layout')

@section('content')

    <div class="row mb-5">
        <div class="col-md-2 w-25">
            <div class=" mr-auto">
                <span class="text-title">{{ __('Inventory Report') }}</span>
            </div>
        </div>
        
        
        
    </div>
    <div class="row mb-5">
    
        <div class="col-md-2 " style="width: 20%">
            {{ __('From') }} <input type="date" name="from_date" id="from_date" class="form-control"
                onchange="date_range_filter()" value="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}" />
        </div>
        <div class="col-md-2 ">
            {{ __('To') }} <input type="date" name="to_date" id="to_date" class="form-control"
                onchange="date_range_filter()" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
        </div>
        <div class="col-md-2 ">
            {{ __('Select Store') }} 
            <select name="store_id" id="store_id" class="form-control" onchange="date_range_filter()">
                @if(request()->logged_user_store_id == 1)
                <option value="">All Stores</option>
                @endif
                @foreach($all_store_names as $store)
                    <option @if(request()->logged_user_store_id == $store->id) {{ "selected" }} @endif value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 ">
            {{ __('Select Product Type') }} 
            <select name="product_type" id="product_type" class="form-control" onchange="date_range_filter()">
                <option>All</option>
                <option value="0">Products</option>
                <option value="1">Ingredients</option>
            </select>
        </div>
        <div class="col-md-3" style="margin-top:25px">
            <button class="btn btn-outline-primary mr-1" id="download_excel" title='{{ __('Download Excel') }} '
                onclick='download_excel()'> {{ __('Download Excel') }} </button>
            <span id="processing_excel"></span>&nbsp;
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive">
                <table id="listing-table" class="table display nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Product Name') }}</th>
                            <th>{{ __('Branch') }}</th>
                            <th>{{ __('Opening Quantity') }}</th>
                            <th>{{ __('Purchase Quantity') }}</th>
                            <th>{{ __('Transfer From Quantity') }}</th>
                            <th>{{ __('Transfer To Quantity') }}</th>
                            <th>{{ __('Sold Quantity') }}</th>
                            <th>{{ __('Returned Quantity') }}</th>
                            <th>{{ __('Damaged Quantity') }}</th>
                            <th>{{ __('Adjustment Quantity') }}</th>
                            <th>{{ __('Stock Returned Quantity') }}</th>
                            <th>{{ __('Available Quantity') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>


                </table>
            </div>

        </div>
    </div>
@endsection
<script type="text/javascript" language="javascript"></script>

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/inventory_report.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script src="{{ asset('theme/libs/moment/moment.js') }}"></script>

    <script>
        'use strict';
        window.onload = function() {
            let date = new Date();
            let month = new Date().getMonth();
            let prevMonth = date.setMonth(month - 1)
            let formatPrevMonth = new Date(date.setMonth(month - 1));

            date_range_filter();
        };

        function date_range_filter() {
            var inventory_report = new InventoryReport();
            var commonDatatable = new CommonDatatableLanguage;
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            var store_id = document.getElementById('store_id').value;
            var product_type = document.getElementById('product_type').value;

            var commonDatatablesData = commonDatatable.commonDatatable();

            inventory_report.load_listing_table(commonDatatablesData, to_date, from_date,store_id,product_type);
        }

        function download_excel() {
            let from_date = document.getElementById('from_date').value;
            let to_date = document.getElementById('to_date').value;
            let store_id = document.getElementById('store_id').value;
            let product_type = document.getElementById('product_type').value;

            let access_token = window.settings.access_token;
            let formData = {
                "from_created_date": from_date,
                "to_created_date": to_date,
                "store_id": store_id,
                "product_type": product_type,
                "access_token": access_token,
            }

            document.getElementById('processing_excel').innerHTML = "Processing..";
            document.getElementById('download_excel').disabled = true;

            $.ajax({
                url: '/api/inventory_report/download_excel',
                type: "POST",
                data: formData,
                success: function(response) {
                    document.getElementById('processing_excel').innerHTML = "";
                    document.getElementById('download_excel').disabled = false;
                    if (response.status_code == 200) {
                        if (typeof response.link != 'undefined' && response.link != "") {
                            const link = document.createElement('a');
                            link.href = response.link;
                            document.body.appendChild(link);
                            link.click();

                        } else {
                            // location.reload();
                        }
                    } else {
                        console.log('Error')
                    }
                }
            });
        }

        
    </script>
@endpush
