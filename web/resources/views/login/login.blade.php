@extends('templates/basic_nav')
@section('content')
  <div class="center-block">
    <center>
      {{ Form::open(array('url' => 'login')) }}
      {{ Form::text('email', Input::old('email'), array('placeholder' => 'User ID', 'class' => 'form-control login-field')) }}
      {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
      {{ Form::submit('Log in', array('class' => 'btn btn-1 btn-1e')) }}
      {{ Form::close() }}
    </center>
  </div>
@endsection
