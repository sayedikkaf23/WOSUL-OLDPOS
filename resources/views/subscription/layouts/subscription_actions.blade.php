<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_MEASUREMENT'), true))
            <a href="{{ $subscription['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_CATEGORY'), true))
            <a href="edit_subscription/{{ $subscription['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
        <a href="manage_subscription_role/{{ $subscription['slack'] }}" class="dropdown-item">{{ __("Manage Roles") }}</a>
    </div>
</div>