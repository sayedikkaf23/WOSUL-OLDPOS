@extends('layouts.layout')

@section("content")
<addtablecomponent :statuses="{{ json_encode($statuses) }}" :table_data="{{ json_encode($table_data) }}"></addtablecomponent>
@endsection