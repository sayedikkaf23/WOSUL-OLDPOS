<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_ORDER'), true))
            <a href="{{ $order['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_ORDER'), true) && ( isset($order['status']['value']) && (!in_array($order['status']['value'], ['1','6','7'])) ) )
            <a href="edit_order/{{ $order['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
    </div>
</div>