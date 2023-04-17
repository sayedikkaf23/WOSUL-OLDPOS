@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Main Categories") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_CATEGORY'), true))
                    <a href="{{ route('add_main_category')}}" role="button" class="btn btn-primary">{{ __("New Category") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Category Name") }}</th>
                        <th>{{ __("Category Code") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Created On") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($main_categories))
                        @foreach($main_categories as $rs)
                            <tr>
                                <td>{{ $rs->label }}</td>
                                <td>{{ $rs->category_code }}</td>
                                <td>{{ $rs->status }}</td>
                                <td>{{ $rs->created_at }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-h actions-dropdown"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
                                            <a href="#" class="dropdown-item">View</a>
                                            <a href="#" class="dropdown-item">Edit</a>
                                        </div>  
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
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
    {{-- <script src="{{ asset('js/pages/categories.js') }}"></script> --}}
@endpush