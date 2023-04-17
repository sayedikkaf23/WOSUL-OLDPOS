<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        <a href="{{ $merchant['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        <a href="edit_merchant/{{ $merchant['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        <a onclick="return confirm('Are you sure?');" href="{{ route('delete_merchant', [ 'slack' => $merchant['slack'] ]) }}" class="dropdown-item">{{ __("Delete Merchant") }}</a>
    </div>
</div>