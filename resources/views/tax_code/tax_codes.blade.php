@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Tax Codes") }}</span>
            </div>
            <div class="mr-5">
                @if (check_access(array('A_ADD_TAXCODE'), true))
                    <a href="{{ route('add_tax_code')}}" role="button" class="btn btn-primary">{{ __("New Tax Code") }}</a>
                @endif
            </div>
            <div class="">
                @if (check_access(array('A_ADD_TAXNAME'), true))
                    <a href="{{ route('add_tax_name')}}" target="_blank" role="button" class="btn btn-primary">{{ __("New Tax Name") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Label") }}</th>
                        <th>{{ __("Tax Code") }}</th>
                        <th>{{ __("Tax Percentage") }}</th>
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
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/taxcodes.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var taxcodes = new Taxcodes();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        taxcodes.load_listing_table(commonDatatablesData);
    </script>
@endpush
