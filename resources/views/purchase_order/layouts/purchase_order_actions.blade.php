<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_PURCHASE_ORDER'), true))
            <a href="{{ $purchase_order['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_PURCHASE_ORDER'), true) && $purchase_order['status']['value'] != 4 )
            <a href="edit_purchase_order/{{ $purchase_order['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
    </div>
</div>