@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                {{-- <span class="text-title">{{ __("All Categories") }}</span> --}}

                <select name="category_filter" id="category_filter" class="form-control">
                    <option value="0">{{ __("All Categories") }}</option>
                    <option value="1">{{ __("Categories") }}</option>
                    <option value="2">{{ __("Sub Categories") }}</option>
                </select>

            </div>
            <div class="">
                @if (check_access(array('A_ADD_CATEGORY'), true))
                    <a href="{{ route('category_screen')}}" role="button" class="btn btn-primary">{{ __("Sort Categories") }}</a>
                    <a href="{{ route('add_main_category')}}" role="button" class="btn btn-success">{{ __("New Category") }}</a>
                    <a href="{{ route('add_category')}}" role="button" class="btn btn-primary">{{ __("New Sub Category") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Category Name") }}</th>
                        <th>{{ __("Category Type") }}</th>
                        <th>{{ __("Category Code") }}</th>
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
    <script src="{{ asset('js/pages/categories.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';

        $(function(){
    
            var category_type = 0;
            var categories = new Categories();
            var commonDatatable = new CommonDatatableLanguage;
            var commonDatatablesData = commonDatatable.commonDatatable();
            categories.load_listing_table(category_type,commonDatatablesData);

        });

        // Filter categories by category type
        $('#category_filter').on('change', function() {
            var category_type = $(this).val();
            $('#listing-table').DataTable().clear();
            $('#listing-table').DataTable().destroy();
            var temp = new Categories();
            var commonDatatable = new CommonDatatableLanguage;
            var commonDatatablesData = commonDatatable.commonDatatable();
            temp.load_listing_table(category_type,commonDatatablesData);
        });


    </script>

@endpush