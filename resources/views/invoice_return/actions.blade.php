<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_INVOICE_RETURN'), true))
            <a href="{{ $invoice_return['print_invoice_return_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        
    </div>
</div>