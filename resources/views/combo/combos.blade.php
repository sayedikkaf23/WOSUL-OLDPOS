@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Combos") }}</span>
            </div>
            <div class="mr-5">
                @if (check_access(array('A_ADD_COMBO_GROUP'), true))
                    <a href="{{ route('combo_groups')}}" role="button" class="btn btn-danger">{{ __("View Combo Groups") }}</a>
                @endif
            </div>
            <div class="mr-5">
                @if (check_access(array('A_ADD_COMBO'), true))
                    <a href="{{ route('add_combo')}}" role="button" class="btn btn-primary">{{ __("New Combo") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Combo Name") }}</th>
                        <th>{{ __("Category") }}</th>
                        <th>{{ __("Available Sizes") }}</th>
                        <th>{{ __("No. Of Products") }}</th>
                        <th>{{ __("Discount") }}</th>
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
    <script src="{{ asset('js/pages/combos.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var combos = new Combos();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        combos.load_listing_table(commonDatatablesData);
    </script>
@endpush
