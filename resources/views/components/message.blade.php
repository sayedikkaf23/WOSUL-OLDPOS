@if ($errors->any())
    <div class="alert alert-danger mt-5 mb-5">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session()->has('success'))
    <div class="alert alert-success mt-5 mb-5">
        {{ session('success') }}
    </div>
@endif
