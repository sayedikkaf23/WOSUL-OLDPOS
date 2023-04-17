@extends('layouts.layout')

@section("content")
	<returnorderdetailcomponent :return_order_data="{{ json_encode($return_order_data) }}"   :print_return_order_link="{{ json_encode($print_return_order_link) }}" :print_return_pos_receipt_link="{{ json_encode($print_return_pos_receipt_link) }}"></returnorderdetailcomponent>
@endsection