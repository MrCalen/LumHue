@extends('templates/basic_nav')

@section('ngApp')ng-app="light"@endsection
<!--FIXME ambiance-->
@section('ngController')ng-controller="AmbianceController" ng-cloak @endsection

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
                <div ng-show="loading && ambiances">
                    <i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing
                </div>
                <div ng-hide="loading && ambiances">Ambiances</div>
            </div>
        </div>

        <center class="sk-folding-cube" ng-show="!ambiances">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
        </center>

        <div class="container-fluid row">
            <div class="row" ng-if="ambiances">
                <div ng-repeat="ambiance in ambiances">
                    <div class="col-lg-3 col-md-3 light_info_row">
                        <div class="container-fluid">
                            <button class="col-xs-1 col-xs-offset-10 modal-toggle"
                                    ng-click="toggleEditAmbiance($index)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <div class="row">
                                <section class="stage">
                                    <div ng-repeat="light in ambiance.lights">
                                        <figure class="ball"
                                                style="background: radial-gradient(circle at 80% 100%, {$ light.rgbhex $} , #0a0a0a 80%, #000000 100%)">
                                            <span class="shadow"></span>
                                        </figure>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="col-lg-1 col-md-1 light_info_row">
                    <div class="container-fluid">
                        <button class="modal-toggle" style="margin: 0 auto"
                                ng-click="toggleNewAmbiance()">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endsection

        @section('modals')
            @parent
            <div class="modal fade" tabindex="-1" role="dialog" id="modalCreateAmbiance">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">
                                Edit ambiance</h4>
                        </div>
                        <div class="modal-body">

                            <div ng-repeat="light in currentAmbiance.lights">
                                <div class="container-fluid">

                                        <h6 class="light-modal-title">Change Lamp Color</h6>
                                        <button colorpicker="rgb" type="button"
                                                colorpicker-position="top"
                                                class="light-modal-text"
                                                ng-model="light.color">Change Color</button>
                                        <h6 class="light-modal-title">On / Off</h6>
                                        <switch id="enabled" name="enabled" ng-model="light.on" class="green"></switch>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div ng-show="saving"><span class="modal-title" ng-bind="savingText"></span><i class="fa fa-spinner fa-spin fa-fw black"></i></div>
                            <button type="button" class="btn btn-default" ng-click="saveNewAmbiance()" ng-hide="saving">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
