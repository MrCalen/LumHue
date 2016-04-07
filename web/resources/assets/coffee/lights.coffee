app = angular.module 'light', ['colorpicker.module', 'uiSwitch'], ($interpolateProvider) ->
    $interpolateProvider.startSymbol('{$').endSymbol('$}')

app.controller 'LightController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    # View controls Logic
    $scope.toggleModal = (i) ->
      oldLight = $scope.lights[i + 1]
      $scope.currentLight =
        reachable: oldLight.state.reachable
        on: oldLight.state.on
        color: oldLight.rgbstr
        id: i + 1
      $('#modalEditLight').modal('toggle')
      return

    $scope.applyLight = ->
      # $scope.applying = true
      $http.post $scope.base_url + '/api/lights?access_token=' + window.token,
          id: $scope.currentLight.id
          on: $scope.currentLight.on
          color: $scope.currentLight.color
      .success (data, status) ->
        $scope.applying = false
        console.log data

    # Refresh light status Logic
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
