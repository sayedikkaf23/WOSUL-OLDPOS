<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <form action="{{ route('signin', app()->getLocale()) }}" method="POST">
        {{-- @csrf --}}
        {{ csrf_field() }}
        <div class="form-group">
            <label class="label">{{ __('Your Name') }} <em>*</em></label>
            <div class="form-group-icon">
                <input type="text" placeholder="{{ __('Your Name') }}*" name="email"
                    class="form-control" required />
                <div class="icon"><img src="images/field-user.svg" alt="" /></div>
            </div>
        </div>
        <div class="form-group">
            <label class="label">{{ __('Password') }}<em>*</em></label>
            <div class="form-group-icon">
                <input type="password" placeholder="{{ __('Password') }}*" name="password"
                    class="form-control" required />
                <div class="icon"><img src="images/field-password.svg" alt="" /></div>
            </div>
        </div>
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <div class="custom-checkbox">
                    <label>
                        <input type="checkbox" />
                        <span>{{ __('Remember Me') }}</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-submit-wrap text-center">
            <input type="hidden" id="txtFromPaymentForm" name="from_payment" />
            <button type="submit" class="btn btn-primary btn-m-width">{{ __('Submit') }}</button>
            
        </div>
    </form>
    
</body>
</html>