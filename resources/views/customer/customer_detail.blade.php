@extends('layouts.layout')

@section("content")

<customerdetailcomponent :customer_data="{{ json_encode($customer_data) }}"></customerdetailcomponent>
<div class="row">
    <div class="col-md-12">
        <div class="mb-2">
            <span class="text-subhead">{{ __("POS Orders/Invoices") }}</span>
        </div>
        <input type="hidden" name="customer_slack" id="customer_slack" value="{{$customer_data->slack}}">
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <select name="order_filter" id="order_filter" class="form-control">
                    <option value="0">{{ __("POS Orders/Invoices") }}</option>
                    <option value="1">{{ __("POS Orders") }}</option>
                    <option value="2">{{ __("Invoices") }}</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                <tr>
                    <th>{{ __("Id") }}</th>
                    <th>{{ __("Type") }}</th>
                    <th>{{ __("POS Order/Invoice No.") }}</th>
                    <th>{{ __("Date") }}</th>
                    <th>{{ __("Amount") }}</th>
                    <th>{{ __("Status") }}</th>
                    <th>{{ __("Created At") }}</th>
                    <th>{{ __("Updated At") }}</th>
                    <th>{{ __("Action") }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="mb-2">
            <span class="text-subhead">{{ __("Main Counters") }}</span>
        </div>
    </div>
</div>

<customerstatisticscomponent :count_data="{{ json_encode($count_data) }}"></customerstatisticscomponent>

@endsection


@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/customer_order_invoice.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';

        $(function(){
            var customer_slack = $('#customer_slack').val();
            var list_type = 0;
            var orders = new Orders();
            var commonDatatable = new CommonDatatableLanguage;
            var commonDatatablesData = commonDatatable.commonDatatable();
            orders.load_listing_table(customer_slack,list_type,commonDatatablesData);

        });

        // Filter categories by category type
        $('#order_filter').on('change', function() {
            var customer_slack = $('#customer_slack').val();
            var list_type = $(this).val();
            $('#listing-table').DataTable().clear();
            $('#listing-table').DataTable().destroy();
            var temp = new Orders();
            var commonDatatable = new CommonDatatableLanguage;
            var commonDatatablesData = commonDatatable.commonDatatable();
            temp.load_listing_table(customer_slack,list_type,commonDatatablesData);
        });

    </script>

@endpush