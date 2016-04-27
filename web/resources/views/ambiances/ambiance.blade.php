@extends('templates/basic_nav')

@section('ngApp')ng-app="LumHue"@endsection
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
//        $('ambiancePreview').carousel();
        $('ambianceCarousel').carousel();
        $('ambianceCarousel2').carousel();

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
                            <button class="col-xs-1 col-xs-offset-1 modal-toggle"
                                    ng-click="toggleEditAmbiance($index)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="col-xs-1 col-xs-offset-1"
                                    ng-click="applyAmbiance($index)">
                                <i class="fa fa-user"></i>
                            </button>
                            <div class="row">
                                <section class="flatstage">

                                  <div class="carousel slide carousel-fade" data-ride="carousel" interval="1500">
                                    <div class="carousel-inner" role="listbox">
                                      <div class="item" ng-class="{'active':$index == 0}" ng-repeat="slideLights in ambiance.lights">
                                        <div class="container-fluid">
                                          <div ng-repeat="light in slideLights.lightscolors">
                                            <div class="col-lg-4">
                                              <figure class="flatball"
                                              style="background: radial-gradient(circle at 0% 5%, {$ light.color $} , #0a0a0a 150%, #000000 150%)">
                                              <span class="shadow"></span>
                                              </figure>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>


                                  {{-- <div ng-repeat="light in ambiance.lights[0].lightscolors">
                                    <div class="col-md-4">
                                      <figure class="flatball"
                                              style="background: radial-gradient(circle at 0% 5%, {$ light.rgbhex $} , #0a0a0a 150%, #000000 150%)">
                                          <span class="shadow"></span>
                                      </figure>
                                    </div>
                                  </div> --}}
                                </section>
                            </div>
                            <div ng-bind="ambiance.name"></div>
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

            <div class="modal fade" tabindex="-1" role="dialog" id="modalCreateAmbiance" aria-labelledby="modalCreateAmbiance">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">
                                Create ambiance</h4>
                        </div>
                        <div class="modal-body">

                          <div id="ambianceCarousel" class="carousel slide carousel-fade" data-ride="carousel" data-interval="0">

                            <ol class="carousel-indicators">
                              <li data-target="#ambianceCarousel" data-slide-to="{$ $index $}" ng-class="{'active': $index == 0}" ng-repeat="slideLights in currentAmbiance.lights" ></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">

                              <div class="item" ng-class="{'active':$index == 0}" ng-repeat="slideLights in currentAmbiance.lights">

                                <div class="container-fluid">
                                  <div ng-repeat="light in slideLights.lightscolors">
                                    <div class="col-md-4">
                                      <figure class="flatball"
                                      style="background: radial-gradient(circle at 0% 5%, {$ light.color $} , #0a0a0a 150%, #000000 150%)">
                                      <span class="shadow"></span>
                                      </figure>
                                      <h6 class="light-modal-title">Change Lamp Color</h6>
                                      <button colorpicker="rgb" type="button"
                                      colorpicker-position="top"
                                      class="light-modal-text"
                                      ng-model="light.color">Change Color</button>
                                      <h6 class="light-modal-title">On / Off</h6>
                                      <switch id="enabled" name="enabled" ng-model="light.on" class="green"></switch>
                                    </div>
                                  </div>
                                  <button type="button" class="btn" style="background-color=red" ng-click="removeAmbianceSlide(currentAmbiance.lights.indexOf(slideLights))" ng-hide="saving">
                                      remove slide
                                  </button>
                                </div>
                                <div class="carousel-caption">
                                  <p>{$ slideLights.duration $}</p>
                                </div>

                              </div>

                            </div>

                            <a class="carousel-control left" href="#ambianceCarousel" data-slide="prev">
                              <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="carousel-control right" href="#ambianceCarousel" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>

                          </div>
                        </div>
                        <div class="modal-footer">
                            <input ng-model="currentAmbiance.name" class="ambiance-name"/>
                            <button type="button" class="btn" ng-click="addNewAmbianceSlide()" ng-hide="saving">
                                Add slide
                            </button>
                            <div ng-show="saving"><span class="modal-title" ng-bind="savingText"></span><i class="fa fa-spinner fa-spin fa-fw black"></i></div>
                            <button type="button" class="save-button btn" ng-click="saveNewAmbiance()" ng-hide="saving">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" tabindex="-1" role="dialog" id="modalUpdateAmbiance" aria-labelledby="modalUpdateAmbiance">
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

                          <div id="ambianceCarousel2" class="carousel slide carousel-fade" data-ride="carousel" data-interval="0">

                            <ol class="carousel-indicators">
                              <li data-target="#ambianceCarousel2" data-slide-to="{$ $index $}" ng-class="{'active': $index == 0}" ng-repeat="slideLights in currentAmbiance.lights" ></li>
                            </ol>

                            <div class="carousel-inner" role="listbox">

                              <div class="item" ng-class="{'active':$index == 0}" ng-repeat="slideLights in currentAmbiance.lights">

                                <div class="container-fluid">
                                  <div ng-repeat="light in slideLights.lightscolors">
                                    <div class="col-md-4">
                                      <figure class="flatball"
                                      style="background: radial-gradient(circle at 0% 5%, {$ light.color $} , #0a0a0a 150%, #000000 150%)">
                                      <span class="shadow"></span>
                                      </figure>
                                      <h6 class="light-modal-title">Change Lamp Color</h6>
                                      <button colorpicker="rgb" type="button"
                                      colorpicker-position="top"
                                      class="light-modal-text"
                                      ng-model="light.color">Change Color</button>
                                      <h6 class="light-modal-title">On / Off</h6>
                                      <switch id="enabled" name="enabled" ng-model="light.on" class="green"></switch>
                                    </div>
                                  </div>
                                  <button type="button" class="btn" style="background-color=red" ng-click="removeAmbianceSlide(currentAmbiance.lights.indexOf(slideLights))" ng-hide="saving">
                                      remove slide
                                  </button>
                                </div>
                                <div class="carousel-caption">
                                  <p>{$ slideLights.duration $}</p>
                                </div>

                              </div>

                            </div>

                            <a class="carousel-control left" href="#ambianceCarousel2" data-slide="prev">
                              <span class="glyphicon glyphicon-chevron-left"></span>
                            </a>
                            <a class="carousel-control right" href="#ambianceCarousel2" data-slide="next">
                              <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>

                          </div>
                        </div>
                        <div class="modal-footer">
                            <input ng-model="currentAmbiance.name" class="ambiance-name"/>
                            <button type="button" class="btn" ng-click="addNewAmbianceSlide()" ng-hide="saving">
                                Add slide
                            </button>
                            <div ng-show="saving"><span class="modal-title" ng-bind="savingText"></span><i class="fa fa-spinner fa-spin fa-fw black"></i></div>
                            <button type="button" class="save-button btn" ng-click="updateAmbiance()" ng-hide="saving">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
