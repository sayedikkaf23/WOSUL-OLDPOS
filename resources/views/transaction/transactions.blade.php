@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Transactions") }}</span>
            </div>
            <div class="col-md-2 " style="width: 20%">
                {{__("From") }} <input type="date" name="from_date" id="from_date" class="form-control"  onchange="date_range_filter()"  />
            </div>
            <div class="col-md-2 ">
               {{__("To") }} <input type="date" name="to_date" id="to_date" class="form-control"  onchange="date_range_filter()" />
           </div>
           <div class="col-md-2 w-25" >
           {{__("Status") }}
              <select class="form-control" id="transaction_status" name="transaction_status" onchange="date_range_filter()">
               <option value="">{{__("Status") }}</option>
               @if($transaction_statuses)
                 @foreach($transaction_statuses as $status)
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
            <div class="">
                @if (check_access(array('A_ADD_TRANSACTION'), true))
                    <a href="{{ route('add_transaction')}}" role="button" class="btn btn-primary">{{ __("New Transaction") }}</a>
                @endif
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Transaction Code") }}</th>
                        <th>{{ __("Transaction Date") }}</th>
                        <th>{{ __("Transaction Type") }}</th>
                        <th>{{ __("Account") }}</th>
                        <th>{{ __("Payment Method") }}</th>
                        <th>{{ __("Bill To Type") }}</th>
                        <th>{{ __("Bill To Name") }}</th>
                        <th>{{ __("Amount") }}</th>
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
    <script src="{{ asset('js/pages/transactions.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script>
        
        'use strict';
        window.onload = function() {
            let date = new Date();
            let month = new Date().getMonth();
            let prevMonth = date.setMonth(month - 1)
            let formatPrevMonth = new Date(date.setMonth(month - 1));

            document.getElementById('from_date').value = formatPrevMonth.toISOString().substring(0, 10);
            document.getElementById('to_date').value= new Date().toISOString().substring(0, 10);
            date_range_filter();
        };
        function date_range_filter() {
            var transactions = new Transactions();
            var commonDatatable = new CommonDatatableLanguage;
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            var transaction_status = document.getElementById('transaction_status').value;

            var commonDatatablesData = commonDatatable.commonDatatable();

            transactions.load_listing_table(commonDatatablesData,to_date, from_date,transaction_status); 
        }

        
        function download_excel(){
                let from_date = document.getElementById('from_date').value;
                let to_date = document.getElementById('to_date').value;
                let transaction_status = document.getElementById('transaction_status').value;
            
                let access_token= window.settings.access_token;
                let formData = {
                    "from_created_date" :from_date,
                    "to_created_date" :to_date,
                    "access_token" :access_token,
                    "transaction_status":transaction_status
                }

                document.getElementById('processing_excel').innerHTML="Processing..";
                document.getElementById('download_excel').disabled=true;
                
                $.ajax({
                url: '/api/transaction_report',
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
                let transaction_status = document.getElementById('transaction_status').value;
                let formData = {
                    "from_date" :from_date,
                    "to_date" :to_date,
                    "access_token" :access_token,
                    "transaction_status":transaction_status
                }

                document.getElementById('processing_pdf').innerHTML="Processing..";
                document.getElementById('download_pdf').disabled=true;
                
                $.ajax({
                url: '/api/transaction/generate_pdf',
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