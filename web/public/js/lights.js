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