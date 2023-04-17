@extends('layouts.layout')

@section("content")

    <paymentreportcomponent ref="payment"></paymentreportcomponent>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/payment_report.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var payment_report = new Payment_report();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        payment_report.load_listing_table(commonDatatablesData);
    </script>
@endpush