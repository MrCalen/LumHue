app = angular.module 'light', [], ($interpolateProvider) ->
    $interpolateProvider.startSymbol('{$').endSymbol('$}')

app.controller 'LightController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    $scope.refreshLights = ->
      $http.get($scope.base_url + "/api/bridge",
                  params:
                    access_token: window.token
                )
          .success (data, status) ->
              $scope.lights = data.lights
              $scope.light = data.lights[1]
              $scope.name = $scope.light.name
              $timeout ->
                  $scope.refreshLights()
              , 30 * 1000
          .error ->
              console.log("error")


    $timeout ->
        $scope.refreshLights()
    , 200
