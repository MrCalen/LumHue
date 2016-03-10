@extends('templates/basic_nav')
@section('content')
    {{ Form::open(array('url' => 'login')) }}
    {{ Form::text('email', Input::old('email'), array('placeholder' => 'User ID', 'class' => 'row col-md-2 col-md-offset-5')) }}
    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'row col-md-2 col-md-offset-5')) }}
    {{ Form::submit('Log in', array('class' => 'row col-md-2 col-md-offset-5')) }}
    {{ Form::close() }}
@endsection
