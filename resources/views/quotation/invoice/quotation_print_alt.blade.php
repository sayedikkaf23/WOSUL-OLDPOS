<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>WOSUL - Thanks</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Reports" name="description" />
        <meta content="Wasl Certificate" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('theme/assets/images/favicon.ico') }}">

        <!-- DataTables -->
        <link href="{{ asset('theme/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('theme/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('theme/assets/css/font-awesome.min.css') }}">

        <!-- Responsive datatable examples -->
        <link href="{{ asset('theme/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />     

        <!-- Bootstrap Css -->
        <link href="{{ asset('theme/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('theme/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('theme/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    </head>

    <body data-sidebar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title 
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="page-title-box">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="mb-0 font-size-16">Expences</h4>
                                        </div>
                                        <div class="col-6">  
                                            <div class="dropdown">                                    
                                              <a href="#" class="icon-1 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                              <a href="#" class="icon-1"><i class="fa fa-signal" aria-hidden="true"></i></a>
                                              <div class="dropdown-menu mt-4 user-dropdown dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                              </div>
                                            </div>
                                            <button class="btn btn-drop">
                                                Addition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>     
                         end page title -->

                        <div class="row">
                            <div class="col-lg-7 mx-auto">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 text-center mb-2">
                                                <img src="{{ asset('theme/assets/images/logo.jpg') }}" class="img-fluid" alt="Logo" width="130" />
                                            </div>
                                            
                                            <div class="col-lg-12 border-bottom ">
                                            <?php /*
                                                <h5 class="border-bottom pb-2">{{ __("Quotation To") }}
                                                    <a href="{{ URL('invoice/'.$data->resource->slack) }}">
                                                        <span style="font-size:85%;" class="pull-right">
                                                            <i style="font-size:90%;" class="fa fa-share-square" aria-hidden="true"></i>
                                                            {{ __("Quotation") }}
                                                        </span>
                                                    </a>
                                                </h5>
                                                 */ ?>
                                            </div>
                                           
                                            <div class="col-lg-6">
                                                <div class="invoice-to-logo">
                                                    {{-- <img src="{{ asset('theme/assets/images/logo.jpg') }}" class="img-fluid mb-3" alt="Logo" width="100" /> --}}
                                                    <p>{{ $data->resource->bill_to }}</p>
                                                    <p><b>{{ $data->resource->bill_to_name }}</b></p>
                                                    {{-- <p>{{ $supplier->address }}</p> --}}
                                                    {{-- <p>Tel : {{ $supplier->phone }}</p> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">

                                                <table class="table table-borderless table-responsive"style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <tr>
                                                    <td>{{ __("Quotation Number") }}:</td>
                                                        <td>#{{ $data->resource->quotation_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __("Quotation Date") }}:</td>
                                                        <td>{{ $data->resource->quotation_date }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>{{ __("Date of Payment") }}:</td>
                                                        <td>-</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-lg-12">
                                                <span class="pull-right">
                                                    <button data-jscolor="{
                                                        onChange: 'update(this, \'#pr1\')',
                                                        onInput: 'update(this, \'#pr2\')',
                                                        alpha:1, value:'094269'}">
                                                            
                                                    </button> {{ __("Edit") }}
                                                </span>

                                                <table class="table table-custom dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead id="pr2" style="background:#094269;color:#fff;">
                                                        <tr>
                                                            <th style="width:50%">{{ __("Products") }}</th>
                                                            <th>{{ __("Quantity") }}</th>
                                                            <th>{{ __("Price") }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        
                                                        @php $total = 0; @endphp
                                                        @if(isset($quotation_products))
                                                            @foreach($quotation_products as $rs)
                                                                <tr>
                                                                    <td>{{ $rs->name }}</td>
                                                                    <td>{{ $rs->quantity }}</td>
                                                                    <td>{{ $rs->total_amount }}</td>
                                                                </tr>
                                                            @php $total += $rs->total_amount @endphp
                                                            @endforeach
                                                        @endif

                                                    </tbody>                           
                                                </table>                                    
                                            </div>
                                            <div class="col-lg-6 ml-auto">
                                                {{-- <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">Tax rate:</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">-</span>
                                                </div> --}}
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">{{ __("Sub Total") }}</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">{{ $data->resource->subtotal_excluding_tax }}</span>
                                                </div>
                                                
                                                
                                                @if($data->resource->total_tax_amount > 0)
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">{{ __("Tax Amount") }}</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">{{ $data->resource->total_tax_amount }}</span>
                                                </div>
                                                @endif

                                                @if($data->resource->shipping_charge > 0)
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">{{ __("Shipping Charge") }}</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">{{ $data->resource->shipping_charge }}</span>
                                                </div>
                                                @endif

                                                @if($data->resource->packing_charge > 0)
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">{{ __("Packing Charge") }}</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">{{ $data->resource->packing_charge }}</span>
                                                </div>
                                                @endif

                                                @if($data->resource->total_discount_amount > 0)
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14" style="margin-left: 5px;">{{ __("Discount") }}</span>
                                                </div>
                                                <div style="float: left; width: 50%;  margin-bottom: 10px;">
                                                    <span class="font-size-14">{{ $data->resource->total_discount_amount }}</span>
                                                </div>
                                                @endif

                                                <div id="pr1" class="blue-box" style="background:#094269!important;color:#fff;">
                                                    <div style="float: left; width: 50%;">
                                                        <span class="font-size-14">{{ __("Quotation Total") }}</span>
                                                    </div>
                                                    <div style="float: left; width: 50%;">
                                                        <span class="font-size-14">{{ $data->resource->total_order_amount }}</span> Riyals
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function update(picker, selector) {
                                                    document.querySelector(selector).style.background = picker.toBackground()
                                                }

                                                // triggers 'onInput' and 'onChange' on all color pickers when they are ready
                                                jscolor.trigger('input change');
                                                </script>
                                            <div class="col-lg-12 mt-5 text-center">
                                                <h5>{{ __("We thank you for doing business with us") }}</h5>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                
                <footer class="sticky-footer">
                    2020 © Wosul
                  </footer>
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('theme/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('theme/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- Buttons examples -->
        <script src="{{ asset('theme/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
        <!-- Responsive examples -->
        <script src="{{ asset('theme/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('theme/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Datatable init js -->
        <script src="{{ asset('theme/assets/js/pages/datatables.init.js') }}"></script>

        <script src="{{ asset('theme/assets/js/app.js') }}"></script>

        <script src="{{ asset('theme/assets/js/jscolor.js') }}"></script>

    </body>
</html>
