@extends('templates/basic_nav')

@section('ngApp')ng-app="light"@endsection
  @section('ngController')ng-controller="LightController" ng-cloak @endsection

    @section('css')
      <link rel="stylesheet" href="{{ URL::asset('css/light/light.css') }}">
      <link rel="stylesheet" href="{{ URL::asset('css/spinners/loader.css') }}">
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
        <div class="row light_info">
          <div class="text-center">
            <div ng-show="loading && lights">
              <i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing
            </div>
            <div ng-hide="loading && lights">Lights</div>
          </div>
        </div>

        <center class="sk-folding-cube" ng-show="!lights">
          <div class="sk-cube1 sk-cube"></div>
          <div class="sk-cube2 sk-cube"></div>
          <div class="sk-cube4 sk-cube"></div>
          <div class="sk-cube3 sk-cube"></div>
        </center>

        <div class="col-md-12" ng-repeat="light in lights">
          <div class="row light_info_row">
            <img id="lamp_reachable_{$light.state.reachable && light.state.on $}">
            <span>{$light.name$}</span>
          </div>
        </div>

      @endsection
