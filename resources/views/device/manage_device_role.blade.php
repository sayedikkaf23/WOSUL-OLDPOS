@extends('layouts.layout')

@section("content")
<managesubscriptionrolecomponent :statuses="{{ json_encode($statuses) }}" :menus="{{ json_encode($access_menus) }}" :role_data="{{ json_encode($role_data) }}"></managesubscriptionrolecomponent>
@endsection