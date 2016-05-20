<!DOCTYPE html>
<html lang="en" ng-app="LumHue" class="no-js">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LumHue @yield('title')</title>
    @include('templates/header')

    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
          crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
          integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flat-ui/2.2.2/css/flat-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/chat/chat.css') }}">

    @yield('css')
</head>
<body @yield('ngController')>
<div class="container">
    <ul id="gn-menu" class="gn-menu-main">
        <li class="gn-trigger">
            <a class="gn-icon gn-icon-menu"><span>Menu</span></a>
            <nav class="gn-menu-wrapper">
                <div class="gn-scroller">
                    <ul class="gn-menu">
                        @yield('dropdown_options')
                        <li><a class="gn-icon gn-icon-download" href="/app/#/dashboard">Dashboard</a></li>
                        <li><a class="gn-icon gn-icon-cog" href="/app/#/lights">Lights</a></li>
                        <li><a class="gn-icon gn-icon-help" href="/app/#/ambiances">Ambiances</a></li>
                        <li><a class="gn-icon gn-icon-archive">Log out</a></li>
                    </ul>
                </div>
            </nav>
        </li>
        {{--<li><a href="#">LumHue</a></li>--}}
        @yield('nav_options')
        <li></li>
    </ul>
    <header class="codrops-header">@yield('header')</header>
    <section>
        {{--@yield('content')--}}
        <div ng-view></div>
    </section>
    <div class="dummy-fixed" ng-controller="WebSocketController">
        <div class="checkout">
            <a class="checkout__button" href="#">
                <span class="checkout__text">
                    <span class="checkout__text-inner checkout__initial-text">Micro / Text</span>
                    <span class="checkout__text-inner checkout__final-text">Record</span>
                </span>
            </a>
            <div class="checkout__order">
                <div class="checkout__order-inner">
                    <div class="checkout__summary">
                        <ul class="chat chat-body row" style="overflow-y: scroll; outline: none; max-height: 250px;" id="chat">
                            <li ng-repeat="message in messages track by $index"
                                class="answer {$ message.author == username ? 'right' : 'left' $}">
                                <div class="avatar">
                                    <img src="http://downloadicons.net/sites/default/files/user-group-icon-18526.png"
                                         alt="User name"
                                         class="img img-responsive">
                                    <div class="status online"></div>
                                </div>
                                <div class="name" ng-bind="message.author"></div>
                                <div class="text" data-ng-bind-html="message.content"></div>
                                <div class="time" ng-bind="message.date"></div>
                            </li>
                        </ul>
                        <ul class="chat-body">
                            <li>
                                <form class="answer-add" ng-submit="sendMessage(); currentMessage = ''">
                                    <input placeholder="Write a message" ng-model="currentMessage">
                                    <span class="answer-btn answer-btn-2"
                                          ng-click="sendMessage(); currentMessage = ''"></span>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <button class="checkout__close checkout__cancel"><i class="icon fa fa-fw fa-close"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.0/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.5/angular-sanitize.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>

<script>
    var token = '{{ $token }}';
    var username = "{{ Auth::user()->name}}";
    var base_url = '{{ URL::to('/') }}';
</script>
<script src="{{ URL::asset('js/lumhue.js')}}"></script>

@yield('javascript')

<script src="{{ URL::asset('components/angular-route/angular-route.min.js')}}"></script>
<script src="{{ URL::asset('components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.min.js')}}"></script>
<script src="{{ URL::asset('components/angular-ui-switch/angular-ui-switch.min.js')}}"></script>
<script src="{{ URL::asset('components/angular-drag-and-drop-lists/angular-drag-and-drop-lists.js')}}"></script>
<script src="{{ URL::asset('components/angular-local-storage/dist/angular-local-storage.min.js') }}"></script>
<script src="//www.WebRTC-Experiment.com/RecordRTC.js"></script>

<script src="{{ elixir('js/app.js') }}"></script>

</body>
</html>