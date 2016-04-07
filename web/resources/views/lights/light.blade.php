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

        <div class="container-fluid row">
          <div class="row" ng-if="lights">
            <div ng-repeat="light in lights">
              <div class="col-lg-3 col-md-3 light_info_row">
                <div class="container-fluid">
                  <div class="row">
                    <img id="lamp_reachable_{$light.state.reachable && light.state.on $}" class="img img-responsive">
                  </div>
                  <div class="row">
                    <span class="center">{$light.name$}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endsection
