@extends('templates/basic_nav')

@section('ngApp')ng-app="light"@endsection
@section('ngController')ng-controller="LightController" ng-cloak @endsection

@section('specific_css')
  <link rel="stylesheet" href="{{ URL::asset('css/light/light.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/spinners/loader.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('components/angular-bootstrap-colorpicker/css/colorpicker.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('components/angular-ui-switch/angular-ui-switch.min.css') }}">
@endsection

@section('javascript')
  @parent
  <script src="{{ elixir('js/app.js') }}"></script>
  <script src="{{ URL::asset('components/angular-bootstrap-colorpicker/js/bootstrap-colorpicker-module.min.js')}}"></script>
  <script src="{{ URL::asset('components/angular-ui-switch/angular-ui-switch.min.js')}}"></script>
  <script>
    var token = '{{ $token }}';
    var base_url = '{{ URL::to('/') }}';
  </script>
@endsection

@section('content')
  <div class="container-fluid" id="content">
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
              <button class="col-xs-1 col-xs-offset-10 modal-toggle"
                      ng-click="toggleModal($index)">
              <i class="fa fa-edit"></i>
            </button>
            <div class="row">
              <section class="stage">
                <figure class="ball"
                        style="background: radial-gradient(circle at 80% 100%, {$ light.rgbhex $} , #0a0a0a 80%, #000000 100%)">
                  <span class="shadow"></span>
                </figure>
              </section>
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

  @section('modals')
    @parent
    <div class="modal fade" tabindex="-1" role="dialog" id="modalEditLight">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title">Edit light</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <div class="row" ng-show="currentLight.reachable">
                <h6 class="light-modal-title">Change Lamp Color</h6>
                <button colorpicker="rgb" type="button"
                        colorpicker-position="top"
                        class="light-modal-text"
                        ng-model="currentLight.color">Change Color</button>
                <h6 class="light-modal-title">On / Off</h6>
                <switch id="enabled" name="enabled" ng-model="currentLight.on" class="green"></switch>
              </div>
              <div class="row" ng-hide="currentLight.reachable">
                <h6 class="light-modal-title">Light is not reachable</h6>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div ng-show="applying"><span class="modal-title" ng-bind="applyText"></span><i class="fa fa-spinner fa-spin fa-fw black"></i></div>
            <button type="button" class="btn btn-default" ng-click="applyLight()" ng-hide="applying">
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  @endsection
