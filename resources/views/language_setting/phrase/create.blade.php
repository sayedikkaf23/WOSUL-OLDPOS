@extends('layouts.layout')

@section("content")

<addlangsettingphrasecomponent :statuses="{{ json_encode($statuses) }}" :lang_data="{{ json_encode($lang_data) }}" :language_setting_data="{{ json_encode($language_setting_data) }}" ></addlangsettingphrasecomponent>
@endsection