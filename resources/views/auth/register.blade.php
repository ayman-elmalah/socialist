@extends('layouts.app')

@section('title', 'Register Page')

@section('content')
<div class="container register">
  <div class="row">
      <div class="col-md-3 register-left">
          <img src="{{ asset('images/logo_white.png') }}" alt="Logo white" />
          <h3>Welcome</h3>
          <p>You are 30 seconds away from meeting the world!</p>
          <a href="{{ route('login') }}">Login</a>
      </div>
      <div class="col-md-9 register-right">
        <div class="tab-pane fade show active">
            <h3 class="register-heading">Easy Register</h3>
            <form method="POST" action="{{ route('register') }}">
              @csrf
              <div class="row register-form">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="text-md-right">{{ __('Name') }}</label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email" class="text-md-right">{{ __('Email') }}</label>
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="password" class="text-md-right">{{ __('Password') }}</label>
                      <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                      @if ($errors->has('password'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="password-confirm" class="text-md-right">{{ __('Confirm Password') }}</label>
                      <input id="password-confirm" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password_confirmation" required>
                      @if ($errors->has('password'))
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
                  <input type="submit" class="btnRegister"  value="Register"/>
                </div>
              </div>
            </form>
        </div>
      </div>
  </div>
</div>
@endsection
