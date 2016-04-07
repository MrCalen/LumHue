app = angular.module 'light', [], ($interpolateProvider) ->
    $interpolateProvider.startSymbol('{$').endSymbol('$}')

app.controller 'LightController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    $scope.loop = (time = 30) ->
      $timeout ->
          $scope.refreshLights()
      , time * 1000

    $scope.refreshLights = ->
      if window.blurred
        $scope.loop(10)
        return

      $scope.loading = true
      $http.get($scope.base_url + "/api/bridge",
                  params:
                    access_token: window.token
                )
          .success (data, status) ->
              $scope.lights = data.lights
              $scope.light = data.lights[1]
              $scope.name = $scope.light.name
              $scope.loading = false
              $scope.loop()
          .error ->
              $scope.loop()

    $timeout ->
        $scope.refreshLights()
    , 200
