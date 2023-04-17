<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
    @if (check_access(array('A_DETAIL_STORE'), true))
            <a href="{{ $store['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_STORE'), true))
            <a href="edit_store/{{ $store['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
            {{-- <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModalCenter" class="dropdown-item">{{ __("Store Tax") }}</a> --}}
            <a style="cursor: pointer;" onclick="getStoreTaxDetails('{{$store['slack']}}','{{addslashes($store['name'])}}');" 
                data-toggle="modal" data-target="#store_tax_modal" 
                role="button" class="dropdown-item">{{ __("Store Tax") }}</a>
        @endif
    </div>
</div>