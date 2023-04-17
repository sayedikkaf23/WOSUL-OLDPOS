<div class="form-group">
    <label for="name">{{ __('Select Tax') }} </label>
    <select name="tax_code" id="tax_code_id" class="form-control form-control-custom custom-select">
        @foreach ($tax_codes as $key => $value)
            <option value="{{ $value->id }}" @if($store_tax_data['tax_code_id'] == $value->id ) selected  @endif tax_percentage={{$value->total_tax_percentage}}>
                {{ $value->label }}
            </option>
        @endforeach
    </select>
    <label class="text-danger" for="name">{{ __('This store tax change will update all products tax. ') }} </label>
</div>
<input type="hidden" id="selected_store_slack" value="{{$store_tax_data->slack}}">
<div class="form-group d-none" id="tobacco_input_div">
    <label for="name">{{ __('Tobacco Tax') }} </label>
    <input type="checkbox" name="tobacco_tax_val" id="tobacco_tax_val" {{ $store_tax_data->tobacco_tax_val == 100 ? ' checked' : '' }} />
        {{-- <input type="number" name="tobacco_tax_val" id="tobacco_tax_val" maxlength="50" value="{{$store_tax_data->tobacco_tax_val}}"
        class="form-control form-control-custom" placeholder="0.00" min="0" max="999" step="0.01" autocomplete="off" /> --}}
</div>

<div class="form-group " id="vat_number_div">
    <label for="vat_number">{{ __('VAT Number') }}</label>
    <input type="text" name="vat_number" id="vat_number" maxlength="50" value="{{$store_tax_data->vat_number}}"
        class="form-control form-control-custom" placeholder="Enter VAT Number" autocomplete="off" />
    <span class="error d-none tax_error_fields" id="vat_number_error"></span>
</div>
<div class="form-group " id="tax_registration_name_div">
    <label for="tax_registration_name">{{ 'Tax Registration Name' }}</label>
    <input type="text" name="tax_registration_name" id="tax_registration_name" maxlength="50" value="{{$store_tax_data->tax_registration_name}}"
        class="form-control form-control-custom" placeholder="enter_tax_registration_name" autocomplete="off" />
    <span class="error d-none tax_error_fields" id="tax_registration_name_error"></span>
</div>