
@extends('layouts.layout')

@section("content")

<quantitypurchasedetailcomponent 

	:po_statuses="{{ json_encode($po_statuses) }}" 

	:quantity_purchase_data="{{ json_encode($quantity_purchase_data) }}" 

	:delete_po_access="{{ json_encode($delete_po_access) }}" 

	:create_invoice_from_po_access="{{ json_encode($create_invoice_from_po_access) }}"

></quantitypurchasedetailcomponent>

@endsection