@extends('layouts.app')

@section('content')

@section('title', 'Login Page')

<div class="container register">
  <div class="row">
      <div class="col-md-3 register-left">
          <img src="{{ asset('images/logo_white.png') }}" alt="Logo white" />
          <h3>Welcome</h3>
          <p>You are 30 seconds away from meeting the world!</p>
          <a href="{{ route('register') }}">Register</a>
      </div>
      <div class="col-md-9 register-right">
        <div class="tab-pane fade show active">
            <h3 class="register-heading">Easy Login</h3>
            <form method="POST" action="{{ route('login') }}">
              @csrf
            <div class="row register-form">
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
                  <div class="form-group row">
                      <div class="col-sm-12">
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                              <label class="form-check-label" for="remember">
                                  {{ __('Remember Me') }}
                              </label>
                          </div>
                      </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <input type="submit" class="btnRegister nomargin"  value="Login"/>
                </div>
              </div>
            </form>
        </div>
      </div>
  </div>
</div>
@endsection
