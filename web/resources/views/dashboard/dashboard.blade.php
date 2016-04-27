@extends('templates/basic_nav')

@section('ngApp') ng-app="LumHue" @endsection


@section('specific_css')
    <link href="{{ URL::asset('assets/dashboard/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/vendors/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css') }}"
          rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/css/maps/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/css/custom.css') }}" rel="stylesheet">
@endsection



@section('content')
    <div class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div role="main">
                    <graph-component granularity="hours"></graph-component>
                    <activities-component></activities-component>
                    <weather-component></weather-component>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @parent
    <script src="{{ elixir('js/app.js') }}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/nprogress/nprogress.js') }}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/iCheck/icheck.min.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/skycons/skycons.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Flot/jquery.flot.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/vendors/Flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/flot/jquery.flot.orderBars.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/flot/date.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/flot/jquery.flot.spline.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/flot/curvedLines.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/maps/jquery-jvectormap-2.0.3.min.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/moment/moment.min.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/datepicker/daterangepicker.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/custom.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/maps/jquery-jvectormap-us-aea-en.js')}}"></script>
    <script src="{{ URL::asset('assets/dashboard/js/maps/gdp-data.js')}}"></script>
    <script>
        var base_url = '{{ URL::to('/') }}';
        var token = '{{ $token }}';
    </script>

    <script type="text/ng-template" id="graph-template.html">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="dashboard_graph">

                <div class="row x_title">
                    <div class="col-md-6">
                        <h3>
                            Global Stats
                            <small></small>
                        </h3>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
                    <div id="placeholder33" style="height: 260px; display: none"
                         class="demo-placeholder"></div>
                    <div style="width: 100%;">
                        <div ng-hide="loading" id="canvas_dahs" class="demo-placeholder" style="width: 100%; height:270px;"></div>
                        <div ng-show="loading" style="width: 100%; height:270px;"><h2><i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2></div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
            <br>
        </div>
    </script>

    <script type="text/ng-template" id="activities-template.html">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Recent Activities
                        <small>Sessions</small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="dashboard-widget-content">

                        <ul class="list-unstyled timeline widget">
                            <li ng-show="loading"><h2><i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2></li>
                            <li ng-hide="loading" ng-repeat="message in messages">
                                <div class="block">
                                    <div class="block_content">
                                        <h2 class="title">
                                            <a ng-bind="message.action"></a>
                                        </h2>
                                        <div class="byline">
                                            <span ng-bind="message.date"></span> by <a ng-bind="message.user"></a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </script>
    <script type="text/ng-template" id="weather-template.html">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Weather Prevision
                            <small></small>
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Settings 1</a>
                                    </li>
                                    <li><a href="#">Settings 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div ng-show="loading"><h2><i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2></div>
                    <div class="x_content" ng-hide="loading">
                        <!-- TODAY -->
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="temperature"><b ng-bind="today.dayname">Today</b></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="weather-icon">
                                    <canvas height="84" width="84" class="{$ today.weather[0].main $}"></canvas>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="weather-text">
                                    <h2><i ng-bind="result.city.name"></i> <br><i ng-bind="today.weather[0].description"></i></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="weather-text pull-right">
                                <h3 class="degrees" ng-bind="today.temperature"></h3>
                            </div>
                        </div>
                        <!-- ! TODAY -->
                        <div class="clearfix"></div>

                        <div class="row weather-days">
                            <div class="col-sm-3" ng-repeat="day in days">
                                <div class="daily-weather">
                                    <h2 class="day" ng-bind="day.dayname"></h2>
                                    <h3 class="degrees" ng-bind="day.temperature"></h3>
                                    <canvas class="{$ day.weather[0].main $}" width="32" height="32"></canvas>
                                    <h5><i ng-bind="day.wind.speed"></i> <i>km/h</i></h5>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end of weather widget -->

    </script>
@endsection