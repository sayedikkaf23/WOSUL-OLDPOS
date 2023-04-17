@extends('layouts.layout')

@section("content")

        <div class="row mb-5">
          <div class="col-md-2 w-25">
            <div class=" mr-auto">
                <span class="text-title">{{ __("QR Code") }}</span>
            </div>
          </div>  
                   
       
            <div class="col-md-2 text-right w-25" style="margin-top:25px">
            @if($qrcode==0)
              <a href="javascript:void(0)" role="button" class="btn btn-primary" id="btn_qr_code"  onclick="create_qr_code()">{{ __("Create QR Code") }}</a>
           @else
            @if(isset($store_url))
              {!! QrCode::size(250)->generate($store_url); !!} 
            @endif             
          @endif  
       </div>
        

          <div class="col-md-2 text-right w-25" style="margin-top:25px">
              <a href="javascript:void(0)" role="button" class="btn btn-primary" id="btn_sync_data" onclick="sync_cat_product()">{{ __("Sync Data") }}</a>
          </div>  
           
</div>
 <div class="row mb-5"><div class="col-md-12 text-center print-message"></div></div>

@endsection

@push('scripts')
  
    <script type="text/javascript">
      
      function create_qr_code(){

            let access_token= window.settings.access_token;
            let formData = {
               "user_id" :"{{$user_id??''}}",
               "access_token" :access_token,
               
            }
           $(".print-message").html('<h3>Processing..</h3>');
           $("#btn_qr_code").attr("disabled",true);
            $.ajax({
            url: "api/create_qr_code",
            type: "POST",
            data:formData,
           
            success: function(response) {
              console.log('response',response);
            
             if(response.status_code == 200) {
                
                  if(response.data=='error'){
                    $(".print-message").html('<span class="alert alert-danger alert-dismissible fade show">'+response.msg+'</span>');
                    $("#btn_qr_code").css("display","inline");
                    $("#btn_qr_code").removeAttr("disabled");
                  }else{
                    $(".print-message").html('<span class="alert alert-success alert-dismissible fade show">'+response.msg+'</span>');
                    $("#btn_qr_code").css("display","none");
                    
                    setTimeout(function() {
                      window.location.reload();
                    }, 2000);
                   
                   
                  }
                
              } else{
                  console.log('Error');
              }
            },
            error: function(error) {
              console.log('Error',error);
            }
        });
     }

     function sync_cat_product(){

            let access_token= window.settings.access_token;
            let formData = {
                "user_id" :"{{$user_id??''}}",
                "restaurant_id" :"{{$restaurant_id??''}}",
                "store_id" :"{{$store_id??''}}",
                
                "access_token" :access_token,
               
            }
           $(".print-message").html('<h3>Processing..</h3>');
           $("#btn_sync_data").attr("disabled",true);
            $.ajax({
            url: "api/sync_category_product",
            type: "POST",
            data:formData,
                        
            success: function(response) {
              console.log('response',response);
           
           
             if(response.status_code == 200) {
                
                  if(response.data=='error'){
                    $(".print-message").html('<span class="alert alert-danger alert-dismissible fade show">'+response.msg+'</span>');
                 
                    $("#btn_sync_data").removeAttr("disabled");
                  }else{
                    $(".print-message").html('<span class="alert alert-success alert-dismissible fade show">'+response.msg+'</span>');
                                        
                    setTimeout(function() {
                      window.location.reload();
                    }, 2000);
                   
                   
                  }
                
              } else{
                  console.log('Error');
              }
            },
            error: function(error) {
              console.log('Error data',error);
            }
        });
     }
    </script>
    
@endpush