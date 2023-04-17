
@extends('layouts.layout')

@section("content")
    
    <form class="mb-3" method="POST" action="{{ route('save_import_product') }}" enctype="multipart/form-data">

        @csrf
        
        <div class="d-flex flex-wrap mb-4">
            <div class="mr-auto">
                <span class="text-title">{{ __("Import Products") }}</span>
            </div>
            <div class="d-flex">
                {{-- 
                <button class="btn btn-outline-primary mr-1" type="button" v-on:click="download_reference_sheet()" v-bind:disabled="reference_processing == true"> 
                    <i class='fa fa-circle-notch fa-spin'  v-if="reference_processing == true"></i>
                    {{ __("Download Reference Sheet") }}
                </button>

                <div class="dropdown mr-1" v-if="templates.length != 0">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __("Download Templates") }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
                        <a :href="template.template_link" class="dropdown-item" v-for="(template, index) in templates" :key="index" >{{ __(template.template_label) }} {{ __("Template") }}</a>
                    </div>
                </div>
 --}}
                <button type="submit" class="btn btn-primary">{{ __("Upload & Save") }}</button>

            </div>
        </div>
        
        <div class="form-row mb-2">
            <div class="form-group col-md-3">
                <label for="product_file">{{ __("Import File") }}</label>
                <input type="file" name="product_file" class="form-control-file" required="">
            </div>
        </div>

    </form>

@endsection
