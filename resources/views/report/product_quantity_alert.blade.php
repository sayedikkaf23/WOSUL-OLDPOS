@extends('layouts.layout')

@section("content")
<productquantityalertcomponent></productquantityalertcomponent>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/product_quantity_alert.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>

    <script>
        'use strict';
        var product_quantity_alert = new Product_quantity_alert();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        product_quantity_alert.load_listing_table(commonDatatablesData);
    </script>
@endpush