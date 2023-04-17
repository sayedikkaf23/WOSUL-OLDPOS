@extends('layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="d-flex flex-wrap mb-4">
                <div class="mr-auto">
                    <span class="text-title">{{ __('Stores') }}</span>
                </div>
                <div class="">
                    @if (check_access(['A_ADD_STORE'], true))
                        <a href="{{ route('add_store') }}" role="button" class="btn btn-primary">{{ __('New Store') }}</a>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table id="listing-table" class="table display nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Store Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created On') }}</th>
                            <th>{{ __('Updated On') }}</th>
                            <th>{{ __('Created By') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div style="display: none;" id="store_tax_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="tax_selected_store_name"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="store_tax_details_html">
                    <div class="form-group text-center"><i id="fa_loader" class="fa fa-spinner fa-pulse fa-4x fa-fw"></i></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" onclick="showTaxSubmitConfirm()"
                        {{-- data-toggle="modal" data-target="#store_tax_confirm_modal" --}}
                        class="btn btn-default btn-success">{{ __('Update') }}</button>
                </div>

            </div>
        </div>
    </div>

    <div style="display: none;" id="store_tax_confirm_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="alert alert-danger" style="display:none"></div>
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Store Tax Update Confirmation!') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <h4 class="text-danger" for="name">
                            {{ __('Are you sure want to update all products tax, then click on Proceed.') }} </h4>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-default btn-success" onclick="updateStoreTax()">{{ __('Proceed') }}</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/stores.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        var stores = new Stores();
        var commonDatatable = new CommonDatatableLanguage;
        var commonDatatablesData = commonDatatable.commonDatatable();
        stores.load_listing_table(commonDatatablesData);

        // $('#addProductModal').on('hidden.bs.modal', function(e) {
        //     $(this)
        //         .find("input")
        //         .val('')
        //         .end();
        //     jQuery('.alert-danger').hide();
        // })

        function showTaxSubmitConfirm() {
            $('.tax_error_fields').addClass('d-none');
            let tax_percentage = $('#tax_code_id').find(':selected').attr('tax_percentage');
            var error_flag = 0;
            if(tax_percentage == '15.00' ){
                var vat_number = $('#vat_number').val();
                var tax_registration_name = $('#tax_registration_name').val();
                if(vat_number == ''){
                    $('#vat_number_error').removeClass('d-none').html('VAT Number is required');
                    error_flag = 1;  
                }
                if(tax_registration_name == ''){
                    $('#tax_registration_name_error').removeClass('d-none').html('Tax Registration Name is required');
                    error_flag = 1;  
                }
            }else{
                $('#vat_number').val('');
            }
            if(error_flag == 0){
                $('#store_tax_confirm_modal').addClass('show').modal('toggle');
            }
        }

        function updateStoreTax() {
            var access_token = window.settings.access_token;
            var slack = $('#selected_store_slack').val();
            var tax_code_id = $('#tax_code_id').val();
            var tobacco_tax_val = $('#tobacco_tax_val').prop('checked') ? 100 : 0;
            var vat_number = $('#vat_number').val();
            var tax_registration_name = $('#tax_registration_name').val();

            var formData = {
                "tax_code_id": tax_code_id,
                "tobacco_tax_val": tobacco_tax_val,
                "vat_number": vat_number,
                "tax_registration_name": tax_registration_name,
                "access_token": access_token,
            }
            $.ajax({
                type: "POST",
                url: '/api/store_tax_details_update/'+slack,
                cache: false,
                data: formData,
                success: function(response) {
                    if (response.status_code == 200) {
                        console.log(response);
                        //return;
                        if (response.data == 'error') {
                            alert(response.msg);
                        } else {
                            
                            if(response.products_update_count > 0){
                                alert('\n'+response.msg+'\n'+response.products_update_count+' Products tax details are updated!');
                            }else{
                                alert(response.msg);
                            }
                            $('#store_tax_modal').modal('hide');
                            $('#store_tax_confirm_modal').modal('hide');
                            window.location.reload();
                        }
                    } else {
                        alert(response.msg);
                        console.log('Error');
                    }
                },
                error: function() {
                    alert("Error");
                }
            });
        }

        function getStoreTaxDetails(slack,store_name) {
            var access_token = window.settings.access_token;
            var formData = {
                "slack": slack,
                "access_token": access_token,
            }
            $.ajax({
                type: "POST",
                url: '/api/get_store_tax_details',
                cache: false,
                data: formData,
                success: function(response) {
                    if (response.status_code == 200) {
                        if (response.data == 'error') {
                            alert(response.msg);
                        } else {
                            $('#store_tax_details_html').html(response.data);
                            $('#tax_selected_store_name').html('Store Tax Details of  '+store_name);
                        }
                    } else {
                        alert(response.msg);
                        console.log('Error');
                    }
                },
                error: function() {
                    alert("Error");
                },
                complete: function(){
                    let tax_percentage = $('#tax_code_id').find(':selected').attr('tax_percentage');
                    if(tax_percentage == '15.00' ){
                        $('#tobacco_input_div').removeClass('d-none');
                        $('#vat_number_div').removeClass('d-none');
                        $('#tax_registration_name_div').removeClass('d-none');
                    }else{
                        $('#tobacco_tax_val').prop('checked', false);
                        $('#tobacco_input_div').addClass('d-none');
                        $('#vat_number_div').addClass('d-none');
                        $('#tax_registration_name_div').addClass('d-none');
                    }
                }
            });
        }

        $(document).on('change','#tax_code_id',function(e){
            let tax_percentage = $(this).find(':selected').attr('tax_percentage');
            if(tax_percentage == '15.00' ){
                $('#tobacco_input_div').removeClass('d-none');
                $('#vat_number_div').removeClass('d-none');
                $('#tax_registration_name_div').removeClass('d-none');
            }else{
                $('#tobacco_input_div').addClass('d-none');
                $('#tobacco_tax_val').prop('checked', false);
                $('#vat_number_div').addClass('d-none');
                $('#tax_registration_name_div').addClass('d-none');
            }
        });

    </script>
@endpush
