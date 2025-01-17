@extends('layouts.publiclayout')

@section('content')
	<div class="card-body">
		<h2 class="title">Login</h2>
		<form method="POST" action="{{ route('login') }}">
			@csrf
			<div class="row row-space">
				<div class="col-md-12">
					<label class="label">Email</label>
					<input type="email" class="input--style-4 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
					<input type="password" class="input--style-4 @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="current-password">
					@error('password')
                        <div class="invalid-input">
	                        {{ $message }}
	                    </div>
                    @enderror
				</div>
			</div>
			<div class="row row-space">
				<div class="col-md-6">
					<div class="checkbox">
                        <label class="control control--checkbox">{{ __('Remember Me') }}
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
                            <div class="control__indicator"></div>
                        </label>
                    </div>
				</div>
				<div class="col-md-6">
					<a href="{{ route('password.request') }}" class="pull-right">{{ __('Forgot Your Password?') }}</a>
				</div>
			</div>
			<div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">{{ __('Login') }}</button>
            </div>
		</form>
	</div>
@endsection