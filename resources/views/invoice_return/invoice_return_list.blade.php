@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Invoice Return") }}</span>
            </div>
            <div class="col-md-2 " style="width: 20%">
                {{__("From") }} <input type="datetime-local" name="from_date" id="from_date" class="form-control" value="{{ $store_opening_time }}"  onchange="date_range_filter()"  />
           </div>
           <div class="col-md-2 ">
               {{__("To") }} <input type="datetime-local" name="to_date" id="to_date" class="form-control"  value="{{ $store_closing_time }}" onchange="date_range_filter()" />
           </div>
           <div class="col-md-2 w-25" >
           {{__("Status") }}
              <select class="form-control" id="invoice_status" name="invoice_status" onchange="date_range_filter()">
               <option value="">{{__("Status") }}</option>

               @if($return_statuses)
                 @foreach($return_statuses as $status)
                 <option value="{{$status->value}}">{{$status->label}}</option>
                 @endforeach
               @endif
              </select>
           </div>
           <div class="col-md-3" style="margin-top:25px">
               <button class="btn btn-outline-primary mr-1" id="download_excel" title='{{ __("Download Excel") }} ' onclick='download_excel()'> {{ __("Download Excel") }} </button>
               <span id="processing_excel"></span>&nbsp;<button class="ml-1 btn btn-outline-primary" id="download_pdf" title='{{ __("Download PDF") }} ' onclick='download_pdf()'> {{ __("Download PDF") }} </button>
               <span id="processing_pdf"></span>
           </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Invoice Return Number") }}</th>
                        <th>{{ __("Bill To") }}</th>
                        <th>{{ __("Bill Name") }}</th>
                        <th>{{ __("Bill Email Id") }}</th>
                        <th>{{ __("Bill Contact") }}</th>
                        <th>{{ __("Store") }}</th>
                        <th>{{ __("Total Discount Amount") }}</th>
                        <th>{{ __("Total Tax Amount") }}</th>
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
    <script src="{{ asset('js/pages/invoice_return.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        'use strict';
        
        window.onload = function() {
            let date = new Date();
            let month = new Date().getMonth();
            let prevMonth = date.setMonth(month - 1)
            let formatPrevMonth = new Date(date.setMonth(month - 1));

            // document.getElementById('from_date').value = formatPrevMonth.toISOString().substring(0, 10);
            // document.getElementById('to_date').value= new Date().toISOString().substring(0, 10);
            date_range_filter();
        };
        function date_range_filter() {
            var invoices = new InvoiceReturn();
            var commonDatatable = new CommonDatatableLanguage;
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            var invoice_status = document.getElementById('invoice_status').value;

            var commonDatatablesData = commonDatatable.commonDatatable();

            invoices.load_listing_table(commonDatatablesData,to_date, from_date,invoice_status); 
        }

        
        function download_excel(){
                let from_date = document.getElementById('from_date').value;
                let to_date = document.getElementById('to_date').value;
                let invoice_status = document.getElementById('invoice_status').value;
            
                let access_token= window.settings.access_token;
                let formData = {
                    "from_created_date" :from_date,
                    "to_created_date" :to_date,
                    "access_token" :access_token,
                    "invoice_status":invoice_status
                }

                document.getElementById('processing_excel').innerHTML="Processing..";
                document.getElementById('download_excel').disabled=true;
                
                $.ajax({
                url: '/api/invoice_return_report',
                type: "POST",
                data:formData,
                success: function(response) {
                    document.getElementById('processing_excel').innerHTML="";
                    document.getElementById('download_excel').disabled=false;
                if(response.status_code == 200) {
                    if(typeof response.link != 'undefined' && response.link != ""){                
                        const link = document.createElement('a');
                        link.href = response.link;
                        document.body.appendChild(link);
                        link.click();

                    }else{
                        // location.reload();
                    }
                } else{
                    console.log('Error')
                }
                }
            });
        }
        function download_pdf(){
                var from_date = document.getElementById('from_date').value;
                var to_date = document.getElementById('to_date').value;
                var access_token= window.settings.access_token;
                let invoice_status = document.getElementById('invoice_status').value;
                let formData = {
                    "from_date" :from_date,
                    "to_date" :to_date,
                    "access_token" :access_token,
                    "invoice_status":invoice_status
                }
                document.getElementById('processing_pdf').innerHTML="Processing..";
                document.getElementById('download_pdf').disabled=true;
                
                $.ajax({
                url: '/api/generate_invoice_return_pdf',
                type: "POST",
                data:formData,
                success: function(response) {
                
                if(response.status_code == 200) {
                    document.getElementById('processing_pdf').innerHTML="";
                    document.getElementById('download_pdf').disabled=false;
                    if(typeof response.link != 'undefined' && response.link != ""){  

                        const link = document.createElement('a');
                        link.href = response.link;
                        document.body.appendChild(link);
                    
                        //link.click();
                        window.open(link, '_blank');

                    }else{
                        // location.reload();
                    }
                } else{
                    console.log('Error')
                }
            }
        });
        }   

    </script>
    
@endpush