@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<p class="login-box-msg">{{ __('Register a new membership') }}</p>

<form action="{{ route('register') }}" method="POST">
    @csrf
    <div class="input-group mb-3">
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Name') }}">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="input-group mb-3">
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('Email') }}">
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
        <input id="password" type="password"
            class="form-control @error('password') is-invalid @enderror" name="password"
            required autocomplete="new-password" placeholder="{{ __('Password') }}">
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
    <div class="input-group mb-3">
        <input id="password-confirm" type="password" class="form-control" placeholder="{{ __('Retype Password') }}"
            name="password_confirmation" required autocomplete="new-password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8"></div>
        <!-- /.col -->
        <div class="col-4 mb-4">
            <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
        </div>
        <!-- /.col -->
    </div>
</form>

<a href="{{ route('login') }}" class="text-center">{{ __('I already have an account') }}</a>
@endsection