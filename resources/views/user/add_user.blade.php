@extends('layouts.layout')

@section("content")
<addusercomponent 
    :roles="{{ json_encode($roles) }}" 
    :statuses="{{ json_encode($statuses) }}" 
    :stores="{{ json_encode($stores) }}"  
    :is_super_admin="{{ json_encode($is_super_admin) }}" 
    :user_data="{{ json_encode($user_data) }}"
    :is_master_status="{{ json_encode($is_master_status) }}"
    :all_available_stores="{{ json_encode($all_available_stores) }}">
</addusercomponent>
@endsection