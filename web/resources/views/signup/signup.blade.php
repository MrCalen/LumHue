@extends('templates/basic_nav')
@section('content')
  <div class="center-block">
  <center>
    {{ Form::open(array('url' => 'signup')) }}
    {{ Form::text('name', Input::old('name'), array('placeholder' => 'User name', 'class' => 'form-control login-field'))}}
    {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control login-field')) }}
    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control login-field')) }}
    {{ Form::submit('Sign up', array('class' => 'btn btn-1 btn-1e')) }}
    {{ Form::close() }}
  </center>
</div>
@endsection
