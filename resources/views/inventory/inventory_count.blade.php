@extends('layouts.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/inventory_count.css') }}">
    <style>
        .select2-container--open .select2-dropdown--below {
            z-index: 10000;
        }
        div.dataTables_filter input, .datatable-custom-select {
            height: calc(1.9em + 0.75rem + 2px) !important;
            border-radius: 4px !important;
        }
        .table {
            font-size: 12px !important;
        }
        .table thead th {
            font-size: 12px;
        }
        .inventory-count-title {
            background: white;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, .05);
        }
        .inventory-count-title h3 {
            color: #6c757d;
            padding-top: 5px;
        }
        #listing-table tbody tr {
            cursor: pointer;
            background: transparent;
            transition: background 0.5s ease-in-out;
        }
        #listing-table tbody tr:hover {
            background: #fffbf0;
        }
        .status-selected {
            border-bottom: 2px solid blue;
        }
        .d-grid {
            display: grid !important;
        }
        .daterangepicker:before {
            top: initial !important;
            bottom: initial !important;
            border-right: 6px solid transparent !important;
            border-bottom: 6px solid #fff !important;
            border-left: 6px solid transparent !important;
        }
        .daterangepicker:after {
            top: initial !important;
            border-right: 7px solid transparent !important;
            border-left: 7px solid transparent !important;
            border-top: 7px solid #ccc !important;
        }
        .daterangepicker {
            bottom: 29px !important;
        }
    </style>
@endpush

@section("content")
    <inventorycountcomponent :stores="{{ json_encode($stores)  }}" ></inventorycountcomponent>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/inventory_count.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        // 'use strict';
        var view_route = "{{ url('/api/inventory-count/generate-view') }}";
        var inventory_count = new Inventory_count();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        inventory_count.load_listing_table(commonDatatablesData);
    </script>
@endpush