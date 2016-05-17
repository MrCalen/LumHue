@extends('templates/basic_nav')

@section('ngApp') ng-app="LumHue" @endsection

@section('ngController') ng-controller="DashboardController" @endsection

@section('css')
    <link href="{{ URL::asset('components/bootstrap/dist/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/dashboard/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
@endsection

@section('nav_options')
    <li><a type="button" href="#" data-toggle="modal" data-target="#newWidgetModal">New Widget</a></li>
@endsection

@section('dropdown_options')
    <li><a class="gn-icon gn-icon-download" href="#" ng-click="resetDashboard()">Reset Dashboard</a></li>
@endsection

@section('content')
    <div class="container body">
        <div class="main_container" style="margin: 20px 20px">
            <div role="main">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div ng-repeat="(zone, list) in models.dropzones">
                            <div class="">
                                <div ng-include="'widgets.html'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="newWidgetModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Widget Creation</h4>
                </div>
                <div class="modal-body">
                    <h4>Select a Type</h4>

                    <select
                            class="form-control"
                            ng-model="currentWidget.widget_type"
                            ng-options="value for value in types track by value"
                            ng-change="onChange()">
                    </select>
                    <div ng-if="currentWidget.widget_type">
                        <h4>Select a Size</h4>
                        <select class="form-control"
                                ng-options="opt as opt for opt in currentWidget.availableStates"
                                ng-model="currentWidget.size"></select>
                    </div>
                    <div ng-if="currentWidget.widget_type =='graph'">
                        <h4>Select a Light</h4>
                        <select class="form-control"
                                ng-options="light for light in lights"
                                ng-model="currentWidget.light"></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" ng-click="createWidget()">Create Widget</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('javascript')
    @parent
    <script src="{{ elixir('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/skycons/1396634940/skycons.js"></script>

    <script src="{{ URL::asset('components/Flot/jquery.flot.js')}}"></script>
    <script src="{{ URL::asset('components/Flot/jquery.flot.pie.js')}}"></script>
    <script src="{{ URL::asset('components/Flot/jquery.flot.time.js')}}"></script>
    <script src="{{ URL::asset('components/Flot/jquery.flot.stack.js')}}"></script>
    <script src="{{ URL::asset('components/Flot/jquery.flot.resize.js')}}"></script>
    <script src="{{ URL::asset('components/flot-spline/js/jquery.flot.spline.js')}}"></script>
    <script src="//cdn.rawgit.com/MichaelZinsmaier/CurvedLines/1.1.1/curvedLines.js"></script>
    <script src="{{ URL::asset('assets/dashboard/js/custom.js')}}"></script>
    <script>
        var base_url = '{{ URL::to('/') }}';
        var token = '{{ $token }}';
        $(document).ready(function () {
            $(".selectpicker").selectpicker();
        });

    </script>

    <script type="text/ng-template" id="widgets.html">
        <div dnd-list="list" class="boxes">
            <div ng-repeat="item in list track by $index"
                 dnd-draggable="item"
                 dnd-effect-allowed="move"
                 dnd-moved="list.splice($index, 1)"
                 dnd-selected="models.selected = item"
                 ng-class="{
                                      'col-md-4 col-xs-12': item.size == 'small',
                                      'col-md-6 col-xs-12': item.size == 'half',
                                      'col-md-8 col-xs-12': item.size == 'medium',
                                      'col-md-12 col-xs-12': item.size == 'large'
                                      }">

                <div ng-if="item.widget_type == 'graph'">
                    <graph-component granularity="hours" widgetid="{$ $index $}" lightid="{$ item.light $}"></graph-component>
                </div>
                <div ng-if="item.widget_type == 'weather'">
                    <weather-component widgetid="{$ $index $}"></weather-component>
                </div>
                <div ng-if="item.widget_type == 'history'">
                    <activities-component widgetid="{$ $index $}"></activities-component>
                </div>
            </div>
        </div>
    </script>

    <script type="text/ng-template" id="graph-template.html">
        <div class="boxes">
            <div class="dashboard_graph">
                <div class="row x_title">
                    <div class="col-md-6">
                        <h3 ng-if="lightid">Stats for light <b ng-bind="lightid"></b></h3>
                        <h3 ng-if="!lightid">Global Stats</h3>
                    </div>
                    <div class="col-md-6">
                        <ul class="nav panel_toolbox">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-expanded="false"><i class="fa fa-wrench"></i>
                                </a>
                                <ul class="dropdown-menu first" role="menu">
                                    <li class="dropdown-submenu">
                                        <a href="#" tabindex="-1">Size</a>
                                        <ul class="dropdown-menu">
                                            <li ng-repeat="size in sizes">
                                                <a ng-bind="size" ng-click="applyNewSize(size, widgetid)"></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a ng-click="removeWidget(widgetid)">Remove</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-12">
                    <div style="height: 260px; display: none"
                         class="demo-placeholder"></div>
                    <div style="width: 100%;">
                        <div ng-hide="loading" id="canvas_dahs{$ widgetid $}" class="demo-placeholder"
                             style="width: 100%; height:270px;"></div>
                        <div ng-show="loading" style="width: 100%; height:270px;"><h2><i
                                        class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2></div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
            <br>
        </div>
    </script>

    <script type="text/ng-template" id="activities-template.html">
        <div class="boxes">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Recent Activities
                        <small>Sessions</small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false"><i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu first" role="menu">
                                <li class="dropdown-submenu">
                                    <a href="#" tabindex="-1">Size</a>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="size in sizes">
                                            <a ng-bind="size" ng-click="applyNewSize(size, widgetid)"></a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a ng-click="removeWidget(widgetid)">Remove</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="dashboard-widget-content">

                        <ul class="list-unstyled timeline widget">
                            <li ng-show="loading"><h2><i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2>
                            </li>
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
        <div class="boxes">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Weather Prevision</h2>
                    <ul class="nav panel_toolbox">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false"><i class="fa fa-wrench"></i>
                            </a>
                            <ul class="dropdown-menu first" role="menu">
                                <li class="dropdown-submenu">
                                    <a href="#" tabindex="-1">Size</a>
                                    <ul class="dropdown-menu">
                                        <li ng-repeat="size in sizes">
                                            <a ng-bind="size" ng-click="applyNewSize(size, widgetid)"></a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a ng-click="removeWidget(widgetid)">Remove</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>

                <div ng-show="loading"><h2><i class="left fa fa-spinner fa-spin fa-fw"></i> Refreshing</h2></div>
                <div class="x_content" ng-hide="loading">
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
                                <h2><i ng-bind="result.city.name"></i> <br><i
                                            ng-bind="today.weather[0].description"></i></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="weather-text pull-right">
                            <h3 class="degrees" ng-bind="today.temperature"></h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row weather-days">
                        <div class="col-sm-2" ng-repeat="day in days">
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
    </script>
@endsection
