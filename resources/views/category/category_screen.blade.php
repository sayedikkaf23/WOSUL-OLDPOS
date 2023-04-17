@extends('layouts.layout')
@push("styles")
<style>
.box.over {
  border: 3px dotted #666;
}
</style>
@endpush
@section("content")
<div class="row">
    <div class="col-md-12">
     <div class="alert alert-success" role="alert" id="successAlert" style="display:none;">
      </div>
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Sort Categories") }}</span>
            </div>
        </div>

        <div class="d-flex flex-row flex-wrap container" id="parent" >
           @php
             $index = 0;
           @endphp
           @if(count($categories)>0)
           @foreach($categories as $category)
           @php
             $index = $index+1;
          @endphp
           <div class="card m-3 box" draggable="true" id="category_{{$category['id']}}" style="box-sizing:border-box;cursor:move;">
               <div class="card-body" >
                    <p>{{$category['label']}}</p>
                    <input type="hidden" id="category_value_{{$index}}" value="{{$category['id']}}"/>
               </div>
           </div>
           @endforeach
           @else
           <div class="card m-3" style="box-sizing:border-box;">
               <div class="card-body" >
                    <p>{{__('No Categories Found')}}</p>
               </div>
           </div>
           @endif
        </div>
        <div class="d-flex flex-row justify-content-end align-items-end">
             <button class="btn btn-primary" onclick="saveOrder();">Save</button>
          </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
     $(function() {
        $(".box").draggable({revert: true, snap: 'inner'});
        $(".box").droppable({
          drop: function( event, ui ) {
            var one = $(this).html();
            var two = ui.draggable.html();
            $(this).html(two);
            ui.draggable.html(one);
          }
        });
      });

function saveOrder(){
 let categories_list = document.getElementsByClassName("box");
 let category_ids = "";
 let formData = new FormData();
 let i = 0;
 for(let category in categories_list)
 {
   i++;
   if(typeof(categories_list[category].id)!=="undefined")
   {
     category_ids+=$("#"+categories_list[category].id).find("[id^='category_value_']").val()+",";
   }
 }
      category_ids = category_ids.replace(/,$/,"");
      formData.append("access_token", window.settings.access_token);
      formData.append("category_ids",category_ids);
      axios
        .post('/api/set_category_order', formData)
        .then((response) => {
          $("#successAlert").css("display","block");
          $("#successAlert").html("Categories sorted successfully");
          setTimeout(function(){
            $("#successAlert").css("display","none");
            window.location = "{{route('categories')}}";
           },2000);
        }).catch((err)=>{
          console.error(err);
        });
}
</script>
@endpush