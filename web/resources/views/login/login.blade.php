@extends('templates/basic_nav')

@section('ngApp')ng-app="LoginApp" @endsection
@section('ngController')ng-controller="LoginController" ng-cloak @endsection

@section('content')
  <div class="container-fluid">
    <div class="col-lg-3 center-block">
      {{ Form::open(array('url' => 'login', 'ng-if' => '!open')) }}

      {{ Form::text('email', Input::old('email'), array('placeholder' => 'Your Email', 'class' => 'form-control login-field')) }}
      @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

      {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
      @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif

      {{ Form::submit('Log in', array('class' => 'btn btn-1 btn-1e')) }}
      {{ Form::close() }}
        <div>
            <a class="forgot-pwd" ng-click="open = !open"><b ng-bind="!open ? 'Forgot Password ?' : 'Back'">Forgot password ? </b></a>
            {{ Form::open(array('url' => 'forgot', 'ng-if' => 'open')) }}
            {{ Form::text('email', Input::old('email'), array('placeholder' => 'Your Email', 'class' => 'form-control login-field')) }}
            {{ Form::submit('Reset Password', array('class' => 'btn btn-1 btn-1e')) }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('javascript')
    @parent
    <script src="{{ elixir('js/app.js') }}"></script>
@endsection
