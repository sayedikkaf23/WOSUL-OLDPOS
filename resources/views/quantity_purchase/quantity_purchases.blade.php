@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Quantity Purchases") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_QUANTITY_PURCHASE'), true))
                    <a href="{{ route('add_quantity_purchase')}}" role="button" class="btn btn-primary">{{ __("New Quantity Purchase") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("PO Number") }}</th>
                        <th>{{ __("PO Reference #") }}</th>
                        <th>{{ __("Supplier Name") }}</th>
                        <th>{{ __("Order Date") }}</th>
                        <th>{{ __("Order Due Date") }}</th>
                        <th>{{ __("Amount") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Created On") }}</th>
                        <th>{{ __("Updated On") }}</th>
                        <th>{{ __("Created By") }}</th>
                        <th>{{ __("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/quantity_purchases.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var quantity_purchases = new QuantityPurchase();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        quantity_purchases.load_listing_table(commonDatatablesData);
    </script>
@endpush