@extends('templates/basic_nav')

@section('ngApp')ng-app="light"@endsection
@section('ngController')ng-controller="LightController" ng-cloak @endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/light/light.css') }}">
@endsection

@section('javascript')
    @parent
    <script src="{{ elixir('js/app.js') }}"></script>
    <script>
      var token = '{{ $token }}';
      var base_url = '{{ URL::to('/') }}';

    </script>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row text-center light_info">Lights</div>
        <div class="col-md-12" ng-repeat="light in lights">
            <div class="row light_info_row">
                <img id="lamp_reachable_{$light.state.reachable$}">
                <span>{$light.name$}</span>
            </div>
        </div>
    </div>

@endsection
