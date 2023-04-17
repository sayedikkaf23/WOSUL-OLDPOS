@extends('layouts.layout')

@section("content")
<div class="row">
    <div class="col-md-12">

        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Quantity Adjustments") }}</span>
            </div>
            <div class="">
                @if (check_access(array('A_ADD_QUANTITY_ADJUSTMENT'), true))
                <a data-toggle="modal" data-target="#addProductModal" role="button"
                    class="btn btn-primary text-white">{{ __("New Quantity Adjustment") }}</a>
                @endif
            </div>
        </div>

        <div class="form-row mb-1">
            <div class="form-group col-md-3">
                <label for="quantity_adjustment_status">{{ __("Status") }}</label>
                <select name="quantity_adjustment_status" id="quantity_adjustment_status"
                    class="form-control form-control-custom custom-select">
                    <option value="all" selected>{{ __("All") }}</option>
                    <option value="draft">{{ __("Draft") }}</option>
                    <option value="closed">{{ __("Closed") }}</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="listing-table" class="table display nowrap w-100">
                <thead>
                    <tr>
                        <th>{{ __("Reference") }}</th>
                        <th>{{ __("Branch")}}</th>
                        <th>{{ __("Action") }}</th>
                        <th>{{ __("Reason") }}</th>
                        <th>{{ __("Status") }}</th>
                        <th>{{ __("Business Date") }}</th>
                        <th>{{ __("Created") }}</th>
                        <th>{{ __("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Modal -->

<div style="display: none;" id="addProductModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title">{{ __("New Quantity Adjustment") }}</h5>
                <button type="button" class="close" id="closeBtn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="quantity_adjustment_branch">{{ __("Branch") }}</label>
                    <select name="quantity_adjustment_branch" id="quantity_adjustment_branch"
                        class="form-control form-control-custom custom-select">
                        <option value="0" selected>{{ __("Select") }}</option>
                        @foreach($stores as $store)
                        <option value="{{$store->id}}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity_adjustment_action">{{ __("Action") }}</label>
                    <select name="quantity_adjustment_action" id="quantity_adjustment_action"
                        class="form-control form-control-custom custom-select">
                        <option value="DECREMENT" selected>{{ __("Decrement") }}</option>
                        <option value="INCREMENT">{{ __("Increment") }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity_adjustment_reason">{{ __("Reason") }}</label>
                    <select name="quantity_adjustment_reason" id="quantity_adjustment_reason"
                        class="form-control form-control-custom custom-select">
                        @foreach($reasons as $reason)
                        <option value="{{$reason}}">{{$reason}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <a role="button" class="btn btn-primary text-white"
                    onclick="document.getElementById('closeBtn').click();">{{ __("Close") }}</a>
                <button type="submit" class="btn btn-default btn-success text-white"
                    onclick="addQuantityAdjustment()">{{ __("Save") }}</button>
            </div>

        </div>
    </div>
</div>



@endsection

@push('scripts')

<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/datatable.js') }}"></script>
<script src="{{ asset('js/pages/quantity_adjustment.js') }}"></script>
<script src="{{ asset('js/common.dataTable.text.js') }}"></script>
<script src="{{ asset('theme/libs/moment/moment.js') }}"></script>

<script>
'use strict';

window.onload = function() {
    load_quantity_adjustment_table();
}

function load_quantity_adjustment_table() {
    var quantity_adjustments = new QuantityAdjustment();
    var commonDatatable = new CommonDatatableLanguage;
    var commonDatatablesData = commonDatatable.commonDatatable();
    quantity_adjustments.load_listing_table(commonDatatablesData, $("#quantity_adjustment_status").val());
}
$(document).on('change', '#quantity_adjustment_status', function() {
    var quantity_adjustment_type = $(this).val();
    event = new CustomEvent("quantity_adjustment_type_filter", {
        "detail": quantity_adjustment_type
    });
    document.dispatchEvent(event);
});

function addQuantityAdjustment() {
    if (typeof(window.localStorage) !== "undefined") {
        localStorage.setItem("branch_id", document.getElementById("quantity_adjustment_branch").value.split(":")[0]);
        localStorage.setItem("action", document.getElementById("quantity_adjustment_action").value);
        localStorage.setItem("reason", document.getElementById("quantity_adjustment_reason").value);
        window.location = "{{route('add_quantity_adjustment')}}";
    }
}
</script>
@endpush