<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_BILLING_COUNTER'), true))
            <a href="{{ $billing_counter['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_BONATSTORECOUNTERPOINTS_SETTING'), true))
            <a href="bonat_store_counter_points_settings/{{ $billing_counter['slack'] }}" class="dropdown-item">{{ __("View Bonat Settings") }}</a>
        @endif
    </div>
</div>