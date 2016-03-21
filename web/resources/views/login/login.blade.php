@extends('templates/basic_nav')
@section('content')
  <center>
    {{ Form::open(array('url' => 'login')) }}
    {{ Form::text('email', Input::old('email'), array('placeholder' => 'User ID', 'class' => 'form-control login-field')) }}
    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
    {{ Form::submit('Log in', array('class' => 'btn btn-primary btn-lg btn-block')) }}
    {{ Form::close() }}
  </center>
@endsection
