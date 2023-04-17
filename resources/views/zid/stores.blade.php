@extends('layouts.layout')

@section('content')
    <div class="row mt-5">
        <div class="col-md-4 offset-4">

            <div class="table-responsive">

                @if (isset($success))
                    <div class="alert alert-success">
                        {{ $success }}
                    </div>
                @endif
                @if (isset($error))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif
                <div class="my-4">

                    @if (isset($zid_store) && $zid_store->count() > 0)
                        <h3 class="my-4">Zid Activated Store</h3>
                        <table class="table table-striped table-condensed table-bordered table-hover">
                            <tr>
                                <th> Zid Store Id</th>
                                <td>{{ $zid_store[0]->zid_store_id }}</td>
                            </tr>
                            <tr>
                                <th>Access Token</th>
                                <td>{{ \Illuminate\Support\Str::substr($zid_store[0]->access_token, 0, 70) . '...' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Authorization</th>
                                <td>{{ \Illuminate\Support\Str::substr($zid_store[0]->authorization, 0, 70) . '...' }}
                            </tr>
                            <tr>
                                <th>Expires In</th>
                                <td>{{ $zid_store[0]->expires_in }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $zid_store[0]->updated_at }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $zid_store[0]->created_at }}</td>
                            </tr>
                        </table>
                    @else
                        <a class="btn btn-primary btn-md" href="{{ $redirection_url }}">Activate Zid</a>
                    @endif

                </div>


            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
@endpush
