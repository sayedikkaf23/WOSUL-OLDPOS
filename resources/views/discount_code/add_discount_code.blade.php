@extends('layouts.layout')

@section("content")
<adddiscountcodecomponent :products="{{ json_encode($products)}}"  :categories="{{ json_encode($categories) }}" :statuses="{{ json_encode($statuses) }}" :currentdate="{{ json_encode(date('Y-m-d H:i:s')) }}" :reload_on_submit="true" :discount_code_data="{{ json_encode($discount_code_data) }}"></adddiscountcodecomponent>
@endsection