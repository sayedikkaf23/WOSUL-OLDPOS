<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
       
        @if (check_access(array('A_EDIT_LANGUAGE'), true))
            <a href="/edit/language_phrase/{{$slack.'/'. $lang['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif

       
    </div>
</div>