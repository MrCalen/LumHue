@extends('templates/basic_nav')
@section('ngApp')ng-app="HueChat"@endsection
@section('ngController')ng-controller="HueChatController"@endsection

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/chat/chat.css') }}">
@endsection

@section('javascript')
  <script>
    var username = @if (Auth::check()) "{{ Auth::user()->name}}" @else 'user' + Math.floor((Math.random() * 100) + 1)@endif;
  </script>
  <script src="{{ elixir('js/app.js') }}"></script>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="content container-fluid bootstrap snippets">
      <div class="row row-broken">
        <div class="col-sm-3 col-xs-12">
          <div class="col-inside-lg decor-default chat" tabindex="5000">
            <div class="chat-users">
              <h6>Online</h6>
              <div class="user" ng-repeat="connected_user in users track by $index">
                <div class="avatar">
                  <img src="http://91.234.35.26/iwiki-admin/v1.0.0/admin/img/images/chat/40.png" alt="User name">
                  <div class="status online"></div>
                </div>
                <div class="name" ng-bind="connected_user">User name</div>
                <br/>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-9 col-xs-12 chat" style="overflow: auto; outline: none;" tabindex="5001">
          <div class="col-inside-lg decor-default">
            <div class="chat-body">
              <h6>Chat</h6>
              <div ng-repeat="message in messages track by $index" class="answer {$ message.author == username ? 'right' : 'left' $}">
                <div class="avatar">
                  <img src="http://91.234.35.26/iwiki-admin/v1.0.0/admin/img/images/chat/40.png" alt="User name">
                  <div class="status online"></div>
                </div>
                <div class="name" ng-bind="message.author"></div>
                <div class="text" ng-bind="message.content"></div>
                <div class="time" ng-bind="message.date"></div>
              </div>
              <form class="answer-add" ng-submit="sendMessage(); currentMessage = ''">
                <input placeholder="Write a message" ng-model="currentMessage">
                <span class="answer-btn answer-btn-2" ng-click="sendMessage(); currentMessage = ''"></span>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
