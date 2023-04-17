@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Subscriptions") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_SUBSCRIPTION'), true))
                    <a href="{{ route('add_subscription')}}" role="button" class="btn btn-primary">{{ __("New Subscription") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Title") }}</th>
                        <th>{{ __("Short Description") }}</th>
                        <th>{{ __("Plan Tenure") }}</th>
                        <th>{{ __("Amount") }}</th>
                        <th>{{ __("Discount") }}</th>
                        <th>{{ __("Currency") }}</th>
                        <th>{{ __("Discount Description") }}</th>
                        <th>{{ __("Is Live") }}</th>
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
    <script src="{{ asset('js/pages/subscriptions.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>

    <script>
        'use strict';
        var subscriptions = new Subscriptions();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        subscriptions.load_listing_table(commonDatatablesData);
        console.log(subscriptions);
    </script>
@endpush