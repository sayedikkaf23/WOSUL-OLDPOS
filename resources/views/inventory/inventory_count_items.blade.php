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
        .table thead th {
            font-size: 11px;
        }
        .inventory-count-title {
            background: white;
            padding: 28px;
            border-radius: 8px;
            margin-bottom: 16px;
            border: 1px solid rgba(0, 0, 0, .05);
        }
        .inventory-count-title h3 {
            color: #6c757d;
            padding-top: 5px;
        }
        .btn {    
            line-height: 1.8 !important;
            border-radius: 0.25rem !important;
        }
        .card {
            padding: 25px !important;
        }
    </style>
@endpush

@section("content")
    <inventorycountitemscomponent :store="{{ json_encode($store) }}" :stores="{{ json_encode($stores) }}" :ic_data="{{ json_encode($ic_data) }}"></inventorycountitemscomponent>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/inventory_count_items.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        // 'use strict';
        var inventory_count_item = new Inventory_count_item();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        inventory_count_item.load_listing_table(commonDatatablesData);
    </script>
@endpush