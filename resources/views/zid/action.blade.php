@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        <h2>Sync Zid</h2>
    </div>
    <div class="col-md-12 mt-3">
        <div class="alert alert-success product-sync-msg" style="display:none;">Products are Synced Successfully</div>
    </div>
    <div class="col-md-12 text-center product-sync-loader" style="display:none;">
        <img src="{{ asset('/theme/assets/images/list-loading.gif') }}" alt="" style="width:100px;">
        <h3 style="color:#3981ad;">Please Wait, We are Synchronizing Products with Zid...</h3>
    </div> 
</div>

<div class="row mt-5">
    <div class="col-md-12">
        
        <div class="table-responsive">
            <table class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("API") }}</th>
                        <th>{{ __("Action") }}</th>
                        <th>{{ __("Last Sync At") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($actions as $row)
                    <tr>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->action }}</td>
                        <td>
                            @if( isset($row->last_sync_at)  && $row->last_sync_at->count() > 0) 
                                {{ $row->last_sync_at->first()->created_at  }}
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary sync-now" data-id="{{ $row->id }}"> <i class=""></i>Sync Now</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No Records Found!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
<div class="row mt-5">
    <div class="col-md-12">
        <h4 class="text-muted">Sync History</h4>
        
    </div>
    <div class="col-md-6 mt-5">
        
        <div class="table-responsive">
            <table class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Sync At") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sync_history as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans() }}</td>
                        <td>{{ $row->remark }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No Records Found!</td>
                    </tr>
                    @endforelse
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
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>

        $(document).on('click','.sync-now', function(){

            $(this).attr('disabled',true);
            $('.product-sync-loader').show();

            let api_id = $(this).attr('data-id');
            var formData = new FormData();
            formData.append('access_token',window.settings.access_token);
            formData.append('api_id',api_id);
    
            $.ajax({
                url : '/api/zid/sync',
                type : 'POST',
                dataType : 'JSON',
                processData: false,
                contentType: false,
                data : formData,
                success : function(res){
                    // console.log(res);
                    if(res.status == 1){
                        $('.product-sync-loader').hide();
                        $('.product-sync-msg').show();
                        $('.sync-now').removeAttr('disabled');
                        setTimeout(function(){
                            $('.product-sync-msg').hide();
                            location.reload();
                        },2000);   
                    }
                }
            })

        })


    </script>

@endpush