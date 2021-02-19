@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<p class="login-box-msg">Sign in to start your session</p>

<form action="{{ route('login') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="{{ __('E-Mail Address') }}">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="input-group mb-3">
        <input id="password" type="password" placeholder="{{ __('Password') }}"
            class="form-control @error('password') is-invalid @enderror" name="password"
            required autocomplete="current-password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="row">
        <div class="col-12">
            <div class="icheck-primary">
                <input type="checkbox" id="show-password" name="" class="form-check-input" onclick="showPassword()">
                <label for="show-password">Show Password</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>

<p class="mb-1 mt-4">
    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">
            {{ __('I forgot my password?') }}
        </a>
    @endif
</p>
<p class="mb-0">
    @if (Route::has('register'))
        <a href="{{ route('register') }}" class="text-center">{{ __('Register') }}</a>
    @endif
</p>
@endsection

@push('script')
    <script>
        function showPassword(){
            const password = document.getElementById('password')
            if (password.type == "password") {
                password.type = "text"
            } else {
                password.type = "password"
            }
        }
    </script>
@endpush