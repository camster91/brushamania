@extends('layouts.publiclayout')

@section('content')
    <div class="card-body">
        <h2 class="title">{{ __('Reset Password') }}</h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Email</label>
                    <input id="email" type="email" class="input--style-4 @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <div class="invalid-input">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Password</label>
                    <input id="password" type="password" class="input--style-4 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="invalid-input">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Confirm Password</label>
                    <input id="password-confirm" type="password" class="input--style-4" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>
            <div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">{{ __('Reset Password') }}</button>
            </div>
        </form>
    </div>
@endsection