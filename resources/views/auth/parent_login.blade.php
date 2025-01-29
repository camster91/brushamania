@extends('layouts.publiclayout')

@section('content')
    <div class="card-body">
        <logos-row-component></logos-row-component>
        <div class="row parent-registration-text parent-login-text">
            <h1 class="title">Brushtracker</h1>
            <h1 class="title">Thank you for participating in Brush-a-mania 2025. </h1>
            <a href="https://brushamania.ca/wp-content/uploads/sites/2/2024/08/Parent-instructions.pdf" target="_blank">
                <h2 class="title">Parent Instructions PDF</h2>
            </a>
        </div>
        <form method="POST" action="/parent-login">
            @csrf
            <div class="row row-space">
                <div class="col-md-12">
                    <label class="label">Enter your telephone number to track your child’s brushes or <a
                            href="/parent-registration" style="color: #0c71c3">click here to register</a>.</label>
                    <input type="tel" class="input--style-4" id="phone_login" name="phone" value="{{ old('phone') }}"
                        required autocomplete="phone" autofocus>

                    <div class="invalid-input">
                        {{ session('message') }}
                        @if (session('message'))
                            Please try again or <a href="/parent-registration">click here</a> to register.
                        @endif
                    </div>
                </div>
            </div>
            <div class="p-t-15" style="text-align: center">
                <button class="btn btn--radius-2 btn--blue" type="submit">{{ __('Track Brushes') }}</button>
            </div>
        </form>
        <div class="row parent-registration-text parent-login-text">
            <h1 class="title">Contest open April 1 to May 31</h1>
            <h1 class="title">Winners will be notified after June 7</h1>
            <h1 class="title">For all elementary students up to Grade 6</h1>
        </div>
        <br>
        <parent-login-logos-row-component></parent-login-logos-row-component>
    </div>
@endsection
