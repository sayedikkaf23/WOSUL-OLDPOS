@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Merchants") }}</span>
            </div>
            <div class="">
               {{--  @if (check_access(array('A_ADD_MEASUREMENT'), true))
                    <a href="{{ route('add_merchant')}}" role="button" class="btn btn-primary">{{ __("New Merchant") }}</a>
                @endif --}}
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Company Name") }}</th>
                        <th>{{ __("Company URL") }}</th>
                        <th>{{ __("Phone") }}</th>
                        <th>{{ __("Email") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>Number of branches</th>
                        <th>Expire Date</th>
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
    <script src="{{ asset('js/pages/merchants.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var lang = window.settings.language;
        var totalBranchesCount = @json($total_branches_count);
        var sInfoVal = lang == 'ar' ? "إظهار _START_ إلى _END_ من أصل _TOTAL_ مُدخل" : "Showing _START_ to _END_ of _TOTAL_ merchants and " + totalBranchesCount + " branches";
        var merchants = new Merchants();
        var commonDatatable = new CommonDatatableLanguage('sInfo', sInfoVal);
        var commonDatatablesData = commonDatatable.commonDatatable();
        merchants.load_listing_table(commonDatatablesData);
        console.log(merchants);
    </script>
@endpush