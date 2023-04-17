@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Quotations") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_QUOTATION'), true))
                    <a href="{{ route('add_quotation')}}" role="button" class="btn btn-primary">{{ __("New Quotation") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Quotation Number") }}</th>
                        <th>{{ __("Quotation Reference #") }}</th>
                        <th>{{ __("Bill To") }}</th>
                        <th>{{ __("Bill To Name") }}</th>
                        <th>{{ __("Quotation Date") }}</th>
                        <th>{{ __("Quotation Due Date") }}</th>
                        <th>{{ __("Amount") }}</th>
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
    <script src="{{ asset('js/pages/quotations.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var quotations = new Quotations();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        quotations.load_listing_table(commonDatatablesData);

        $(document).on('click','.quotation_to_invoice', function(){

            $(this).attr('disabled',true);

            let api_url = $(this).attr('data-href');
            var formData = new FormData();
            formData.append('access_token',window.settings.access_token);
            //formData.append('api_id',api_id);
            $.ajax({
                url : api_url,
                type : 'POST',
                dataType : 'JSON',
                processData: false,
                contentType: false,
                data : formData,
                success : function(res){
                    console.log(res);
                    // return;
                    if(res.status_code == 200){
                        alert('Invoice Created successfully!');
                        location.reload();
                        // window.location.href = '{{url('invoice')}}/'+res.data;
                        window.open('{{url('invoice')}}/'+res.data);
                        // setTimeout(function(){
                            // window.location.href = '{{url('invoice')}}/'+res.data;
                            //location.reload();
                        // },1000);   
                    }else{
                        alert(res.msg);
                    }
                }
            });
        });
    </script>
@endpush