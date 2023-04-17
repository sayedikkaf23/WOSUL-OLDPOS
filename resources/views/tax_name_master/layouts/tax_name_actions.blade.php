<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_TAXNAME'), true))
            <a href="{{ $tax_code['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_TAXNAME'), true) && $tax_code['is_default'] == 0)
            <a href="edit_tax_name/{{ $tax_code['id'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
    </div>
</div>