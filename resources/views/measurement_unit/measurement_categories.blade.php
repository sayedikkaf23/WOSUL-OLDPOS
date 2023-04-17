@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Measurement Categories") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_MEASUREMENT_CATEGORY'), true))
                    <a href="{{ route('add_measurement_category')}}" role="button" class="btn btn-primary">{{ __("New Measurement Category") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Label") }}</th>
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
    <script src="{{ asset('js/pages/measurement_categories.js') }}"></script>
     <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var measurement_categories = new MeasurementCategories();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        measurement_categories.load_listing_table(commonDatatablesData);
        console.log(measurement_categories);
    </script>
@endpush