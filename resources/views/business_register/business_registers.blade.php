@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Business Registers") }}</span>
            </div>
            <div class="">
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("User") }}</th>
                        <th>{{ __("Counter Name") }}</th>
                        <th>{{ __("Store Name") }}</th>
                        <th>{{ __("Opened On") }}</th>
                        <th>{{ __("Opening Amount") }}</th>
                        <th>{{ __("Closing Cash Amount") }}</th>
                        <th>{{ __("Expected Cash Amount") }}</th>
                        <th>{{ __("Variance Cash") }}</th>
                        <th>{{ __("Closing Credit Amount") }}</th>
                        <th>{{ __("Expected Credit Amount") }}</th>
                        <th>{{ __("Variance Credit") }}</th>
                        <th>{{ __("Manual Drawer Opens") }}</th>
                        <th>{{ __("Closed On") }}</th>
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
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/business_registers.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var businessRegisters = new BusinessRegisters();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        businessRegisters.load_listing_table(commonDatatablesData);
    </script>
@endpush