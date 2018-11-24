@extends('layouts.app')

@section('title', Auth::user()->name)

@section('content')
  <div class="row">
		<div class="d-none d-sm-none d-md-block col-lg-4 col-md-4 pd-left-none">
			@include('layouts.partials.card')
		</div>
		<div class="col-lg-8 col-md-8 no-pd">
      <div class="tab-pane fade active show" id="nav-password" role="tabpanel" aria-labelledby="nav-password-tab" aria-expanded="true">
        <div class="acc-setting">
        @include('layouts.partials.flash-messages')
        <h3>Edit profile</h3>
        <form method="post" action="{{ route('profile.update') }}">
          @csrf
          {{ method_field('PUT') }}
          <div class="cp-field">
            <h5>Name *</h5>
            <div class="cpp-fiel">
              <input type="text" name="name" placeholder="Name !" value="{{ Auth::user()->name }}">
              <i class="fa fa-user fa-fw form-icon"></i>
              @if ($errors->has('name'))
                  <span class="help-block">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif
            </div>
          </div>

          <div class="cp-field">
            <h5>Email *</h5>
            <div class="cpp-fiel">
              <input type="email" name="email" placeholder="Email !" value="{{ Auth::user()->email }}">
              <i class="fa fa-envelope fa-fw form-icon"></i>
              @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>
          </div>

          <div class="cp-field">
            <h5>Password</h5>
            <div class="cpp-fiel">
              <input type="password" name="password" placeholder="Password !">
              <i class="fa fa-key fa-fw form-icon"></i>
              @if ($errors->has('password'))
                  <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                  </span>
              @endif
            </div>
          </div>
          <div class="save-stngs pd2">
            <ul>
              <li><button type="submit">Save</button></li>
            </ul>
          </div><!--save-stngs end-->
        </form>
      </div><!--acc-setting end-->
      </div>
		</div>
	</div>
@endsection
