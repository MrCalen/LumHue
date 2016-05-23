@extends('templates/basic_layout')
@section('body')
  <div class="center-block">
  <center>
    {{ Form::open(array('url' => 'signup')) }}
    {{ Form::text('name', Input::old('name'), array('placeholder' => 'User name', 'class' => 'form-control login-field'))}}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif

    {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control login-field')) }}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif

    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif

    {{ Form::password('passwordVerificaton', array('placeholder' => 'Password Verification', 'class' => 'form-control login-field')) }}
    @if ($errors->has('passwordVerificaton')) <p class="help-block">{{ $errors->first('passwordVerificaton') }}</p> @endif

    {{ Form::submit('Sign up', array('class' => 'btn btn-1 btn-1e')) }}
    {{ Form::close() }}
  </center>
</div>
@endsection
