@extends('layouts.layout')

@section("content")
<productbarcodecomponent 
    :product_data="{{ json_encode($product_data) }}" 
    :barcode_data="{{ json_encode($barcode_data) }}"
    :product_barcode_details="{{ json_encode($productBarcodeDetails) }}"
>
</productbarcodecomponent>
@endsection