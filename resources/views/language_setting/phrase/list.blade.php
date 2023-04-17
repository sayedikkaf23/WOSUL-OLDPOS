@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Language Phrase") }}</span>
                 &nbsp;&nbsp;<a href="{{ route('lang.list')}}" role="button" class="btn btn-primary">{{ __("Language List") }}</a>
            </div>
            <div class="">

                @if (check_access(array('A_ADD_PHRASE_LANGUAGE'), true))
                    <a href="{{ route('add.lang.phrase',$slack)}}" role="button" class="btn btn-primary">{{ __("Add Phrase") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Phrase") }}</th>
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

@php
  $slack = request()->segment(3);
@endphp

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>

       
    <script>
        'use strict';
    $(window).on('load', function () {
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
     
        var listing_table = $('#listing-table').DataTable({
            ajax: {
                url  : '{{url("api/language/phrase/".$slack)}}',
                type : 'POST',
                data : {
                    access_token : window.settings.access_token
                }
            },
            oLanguage: commonDatatablesData,
            columns: [
                { name: 'language_setting_phrases.lang_phrase' },
                { name: 'language_setting_phrases.lang_value' },
                { name: 'master_status.label' },
                { name: 'language_setting_phrases.created_at' },
                { name: 'language_setting_phrases.updated_at' },
                { name: 'user_created.fullname' },
            ],
            order: [[ 2, "desc" ]],
            columnDefs: [
                { "orderable": false, "targets": [6] }
            ]
        });
    
    });
     </script>
@endpush