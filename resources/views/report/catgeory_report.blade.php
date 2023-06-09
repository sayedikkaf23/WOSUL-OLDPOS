@extends('layouts.layout')

@section("content")
<catgeoryreportcomponent :store="{{ json_encode($store) }}"></catgeoryreportcomponent>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/category_report.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var category_report = new Category_report();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        category_report.load_listing_table(commonDatatablesData);
    </script>
@endpush