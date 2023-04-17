<div class="dropdown">
    <button class="btn btn-sm btn-outline-primary dropdown-toggle actions-dropdown-btn" type="button" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-h actions-dropdown"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown">
        @if (check_access(array('A_DETAIL_QUOTATION'), true))
            <a href="{{ $quotation['detail_link'] }}" class="dropdown-item">{{ __("View") }}</a>
        @endif
        @php
            $converted_invoice_slack = $quotation['converted_invoice_slack'];
        @endphp
        @if (check_access(array('A_DETAIL_INVOICE'), true) && $quotation['status']['value'] == 2)
            @if(!empty($converted_invoice_slack))
                <a href="{{ route('invoice_detail', ['slack' => $converted_invoice_slack ]) }}" target="_blank" class="dropdown-item">
                    {{ __("View Converted Invoice")  }} </a>
            @else
                <a data-href="{{ route('quotation_to_invoice',$quotation['slack']) }}" href="javascript:void()" class="dropdown-item quotation_to_invoice">
                    {{ __("Convert to Invoice") }}</a>
            @endif
        @endif
        {{-- @if (check_access(array('A_EDIT_QUOTATION'), true))
            <a href="edit_quotation/{{ $quotation['slack'] }}" class="dropdown-item">{{ __("Edit") }}</a>
        @endif --}}
    </div>
</div>