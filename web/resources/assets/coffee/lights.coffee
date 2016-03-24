app = angular.module 'light', [], ($interpolateProvider) ->
    $interpolateProvider.startSymbol('{$').endSymbol('$}')

app.controller 'LightController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    $timeout ->
      $http.get($scope.base_url + "/api/bridge",
                  params:
                    access_token: window.token
                )
          .success (data, status) ->
              $scope.lights = data[0].status.lights
              $scope.light = data[0].status.lights[1]
              $scope.name = $scope.light.name
          .error ->
              console.log("error")
    , 200
