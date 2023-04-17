@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Languages") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_LANGUAGE'), true))
                    <a href="{{ route('lang.add')}}" role="button" class="btn btn-primary">{{ __("Add Language") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Language Culture") }}</th>
                        <th>{{ __("Code") }}</th>
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
    <script src="{{ asset('js/pages/language_setting.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var language_setting = new Language_Setting();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        language_setting.load_listing_table(commonDatatablesData);
    </script>
@endpush