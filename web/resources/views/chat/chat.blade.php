@extends('templates/basic_nav')
@section('ngApp')ng-app="HueChat"@endsection
@section('ngController')ng-controller="HueChatController"@endsection

@section('javascript')
    <script src="{{ URL::asset('/js/chat/chat.js') }}"></script>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-lg-offset-1" style="background-color: red">
                <br/>
            </div>
            <div class="col-lg-4 col-lg-offset-2" style="background-color: blue">
                <br/>
            </div>
        </div>
    </div>
@endsection