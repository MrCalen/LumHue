@extends('templates/basic_nav')

@section('framework')
    @parent
    @if (\Auth::check())
        <link rel="stylesheet" href="{{ URL::asset('css/chat/chat.css') }}">
    @endif
@endsection

@section('content')
    @parent
    @if (\Auth::check())
        @include('chat/chat_tabs')
    @endif
@endsection
