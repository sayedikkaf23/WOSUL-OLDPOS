@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                    <span class="text-title">{{ __('Devices') }}</span>
                </div>
                <div class="">
                    @if (check_access(['A_ADD_DEVICE'], true))
                        <a href="{{ route('add_device') }}" role="button"
                            class="btn btn-primary">{{ __('New Device') }}</a>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table id="listing-table" class="table display nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Title (Arabic)') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Description (Arabic)') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created On') }}</th>
                            <th>{{ __('Updated On') }}</th>
                            <th>{{ __('Action') }}</th>
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
    <script src="{{ asset('js/pages/devices.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>

    <script>
        'use strict';
        var devices = new Devices();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        devices.load_listing_table(commonDatatablesData);
        console.log(devices);
    </script>
@endpush
