@extends('layouts.empty_layout')
<style type="text/css">
   
   .container input {
			
		position: relative !important; 
		opacity: 10 !important; 
		cursor: pointer !important; 
		height: auto !important; 
		width: 80% !important;
		
  	}
  </style>  
@section("content")
    <forgotpasswordcomponent :company_logo = {{ json_encode($company_logo)}}></forgotpasswordcomponent>
@endsection