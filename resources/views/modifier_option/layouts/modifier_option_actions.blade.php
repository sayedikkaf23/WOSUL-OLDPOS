<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_EDIT_MODIFIER_OPTION'), true))
            <a href=" {{ route('edit_modifier_option', ['slack' => $modifier['slack'] ]) }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
    </div>
</div>