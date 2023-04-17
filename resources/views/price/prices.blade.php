@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
            
        <div  class="row d-flex flex-wrap mb-4">
            <div class="col-4 mr-auto">
                <span class="text-title">{{ __("Prices") }}</span>
            </div>
            <div class="col-8">
                <div class="row">
                    <div class="col-9 text-right">
                        <priceswitchcomponent></priceswitchcomponent>
                    </div>
                    <div class="col-3 text-right">
                        @if (check_access(array('A_ADD_PRICE'), true))
                        <a href="{{ route('add_price')}}" role="button" class="btn btn-primary">{{ __("New Price") }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Name (Arabic)") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Created On") }}</th>
                        <th>{{ __("Updated On") }}</th>
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
    <script src="{{ asset('js/pages/prices.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var prices = new Prices();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();

        prices.load_listing_table(commonDatatablesData);
        // console.log(prices);
    </script>
@endpush