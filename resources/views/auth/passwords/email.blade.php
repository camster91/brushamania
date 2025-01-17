@extends('layouts.publiclayout')

@section('content')
	<div class="card-body">
		<h2 class="title">{{ __('Reset Password') }}<</h2>
		<form method="POST" action="{{ route('password.email') }}">
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
			<div class="p-t-15">
                <button class="btn btn--radius-2 btn--blue pull-right" type="submit">{{ __('Send Password Reset Link') }}</button>
            </div>
             @if (session('status'))
	            <div class="success-message">
	            	{{ session('status') }}
	            </div>
            @endif
		</form>
	</div>
@endsection