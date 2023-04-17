<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_MODIFIER'), true))
            <a href="{{ $modifier['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @if (check_access(array('A_EDIT_MODIFIER'), true))
            <a href="edit_modifier/{{ $modifier['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif
        @if (check_access(array('A_EDIT_MODIFIER'), true))
            <a onclick="return confirm('Are you sure?');" href="{{ route('delete_modifier', [ 'slack'=> $modifier['slack'] ]) }}" class="dropdown-item">{{ __("Delete") }}</a>
        @endif
    </div>
</div>