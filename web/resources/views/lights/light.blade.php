@extends('templates/basic_nav')
@section('content')
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <div ng-app="light" ng-controller="lightControl">
        <div class="col-md-12">Lights</div>
        <div class="col-md-4" ng-repeat="light in lights">
            <span class="row">Name: {$light.name$}</span>
            <span class="row">Reachable: {$light.state.reachable$}</span>
            <span class="row">On: {$light.state.on$}</span>
        </div>
        <script>
            var app = angular.module('light', [], function($interpolateProvider) {
                $interpolateProvider.startSymbol('{$').
                endSymbol('$}')});
            app.controller('lightControl', function($scope, $http) {
                    $http.get("{{ URL::to('/')}}/api/bridge", {params: {"access_token": '{{$token}}'}})
                            .success(function (data, status) {
                                console.log("success");
                                console.log(data[0].status.lights[1]);
                                $scope.lights = data[0].status.lights;
                                $scope.light = data[0].status.lights[1];
                                $scope.name = $scope.light.name;
                            })
                            .error(function (data, status) {
                        console.log("error");
                    });
            });
        </script>
    </div>

@endsection
