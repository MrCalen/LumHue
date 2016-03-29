@extends('templates/basic_nav')
@section('content')
  <div class="center-block">
  <center>
    {{ Form::open(array('url' => 'signup/reset/password')) }}
    {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control login-field')) }}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    {{ Form::hidden('token', $token, array('class' => 'form-control login-field')) }}

    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
    @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif

    {{ Form::password('passwordVerificaton', array('placeholder' => 'Password Verification', 'class' => 'form-control login-field')) }}
    @if ($errors->has('passwordVerificaton')) <p class="help-block">{{ $errors->first('passwordVerificaton') }}</p> @endif

    {{ Form::submit('Reset', array('class' => 'btn btn-1 btn-1e')) }}
    {{ Form::close() }}
  </center>
</div>
@endsection
