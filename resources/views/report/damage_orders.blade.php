@extends('layouts.layout')

@section('content')

    <div class="row mb-5">
        <div class="col-md-2 w-25">
            <div class=" mr-auto">
                <span class="text-title">{{ __('Damage Orders') }}</span>
            </div>
        </div>
        <div class="col-md-2 " style="width: 20%">
            {{ __('From') }} <input type="datetime-local" name="from_date" id="from_date"
                value="{{ $order_start_date }}" class="form-control" onchange="date_range_filter()" />

        </div>
        <div class="col-md-2 ">
            {{ __('To') }} <input type="datetime-local" name="to_date" id="to_date" value="{{ $order_end_date }}"
                class="form-control" onchange="date_range_filter()" />

        </div>
        <div class="col-md-3" style="margin-top:25px">
            <button class="btn btn-outline-primary mr-1" id="download_excel" title='{{ __('Download Excel') }} '
                onclick='download_excel()'> {{ __('Download Excel') }} </button>
            <span id="processing_excel"></span>&nbsp;<button class="ml-1 btn btn-outline-primary" id="download_pdf"
                title='{{ __('Download PDF') }} ' onclick='download_pdf()'> {{ __('Download PDF') }} </button>
            <span id="processing_pdf"></span>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <div class="table-responsive">
                <table id="listing-table" class="table display nowrap w-100">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Branch') }}</th>
                            <th>{{ __('Branch Reference') }}</th>
                            <th>{{ __('Order Type') }}</th>
                            <th>{{ __('Added By') }}</th>
                            <th>{{ __('Order Reference') }}</th>
                            <th>{{ __('Time') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('Reason') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfooter>
                    
         <tr id="footerTotal"><td><strong>
             {{ __('Total') }}
         </strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td id="totalQuantity">0</td><td id="totalAmount">0</td></tr>
                    </tfooter>

                </table>
            </div>

        </div>
    </div>
@endsection
<script type="text/javascript" language="javascript"></script>

@push('scripts')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}"></script>
    <script src="{{ asset('js/pages/damage_orders.js') }}"></script>
    <script src="{{ asset('js/common.dataTable.text.js') }}"></script>
    <script src="{{ asset('theme/libs/moment/moment.js') }}"></script>

    <script>
        'use strict';
        window.onload = function() {
            let date = new Date();
            let month = new Date().getMonth();
            let prevMonth = date.setMonth(month - 1)
            let formatPrevMonth = new Date(date.setMonth(month - 1));

            // alert(document.getElementById('from_date').value);

            // document.getElementById('from_date').value = moment.utc(document.getElementById('from_date').value).local().format();
            // alert(document.getElementById('from_date').value);
            // document.getElementById('from_date').value = formatPrevMonth.toISOString().substring(0, 10);
            // document.getElementById('to_date').value= new Date().toISOString().substring(0, 10);
            date_range_filter();
        };

        function date_range_filter() {
            var orders = new DamageOrders();
            var commonDatatable = new CommonDatatableLanguage;
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            
            var commonDatatablesData = commonDatatable.commonDatatable();

            orders.load_listing_table(commonDatatablesData, to_date, from_date);
        }

        function download_excel() {
            let from_date = document.getElementById('from_date').value;
            let to_date = document.getElementById('to_date').value;
            
            let access_token = window.settings.access_token;
            let formData = {
                "from_created_date": from_date,
                "to_created_date": to_date,
                "access_token": access_token
            }

            document.getElementById('processing_excel').innerHTML = "Processing..";
            document.getElementById('download_excel').disabled = true;

            $.ajax({
                url: '/api/damage_order_report',
                type: "POST",
                data: formData,
                success: function(response) {
                    document.getElementById('processing_excel').innerHTML = "";
                    document.getElementById('download_excel').disabled = false;
                    if (response.status_code == 200) {
                        if (typeof response.link != 'undefined' && response.link != "") {
                            const link = document.createElement('a');
                            link.href = response.link;
                            document.body.appendChild(link);
                            link.click();

                        } else {
                            // location.reload();
                        }
                    } else {
                        console.log('Error')
                    }
                }
            });
        }

        function download_pdf() {
            var from_date = document.getElementById('from_date').value;
            var to_date = document.getElementById('to_date').value;
            var access_token = window.settings.access_token;
            let formData = {
                "from_date": from_date,
                "to_date": to_date,
                "access_token": access_token
            }
            document.getElementById('processing_pdf').innerHTML = "Processing..";
            document.getElementById('download_pdf').disabled = true;

            $.ajax({
                url: '/api/damage_order/generate_pdf',
                type: "POST",
                data: formData,
                success: function(response) {

                    if (response.status_code == 200) {
                        document.getElementById('processing_pdf').innerHTML = "";
                        document.getElementById('download_pdf').disabled = false;
                        if (typeof response.link != 'undefined' && response.link != "") {

                            const link = document.createElement('a');
                            link.href = response.link;
                            document.body.appendChild(link);

                            //link.click();
                            window.open(link, '_blank');

                        } else {
                            // location.reload();
                        }
                    } else {
                        console.log('Error')
                    }
                }
            });
        }
    </script>
@endpush
