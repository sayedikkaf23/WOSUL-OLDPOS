<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right ml-5" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_PRODUCT'), true))
            <a href="{{ $product['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_PRODUCT'), true))
            <a href="edit_product/{{ $product['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
            <a href="clone_product/{{ $product['slack'] }}" class="dropdown-item">{{ __("Clone") }}</a>
        @endif
        <a href="generate_barcode/{{ $product['slack'] }}" class="dropdown-item">{{ __("Print Barcode") }}</a>
    </div>
</div>