@extends('layouts.layout')

@section("content")
<branddetailcomponent :brand_data="{{ json_encode($brand_data) }}"></branddetailcomponent>
@endsection
