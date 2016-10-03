app.controller 'LightController', ($rootScope, $scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    $scope.recordedInput = {}
    $scope.first = true

    $rootScope.$on 'refresh', ->
      $scope.refreshLights ->


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
      $scope.applying = true
      $scope.applyText = "Applying new light..."
      $http.post $scope.base_url + '/api/lights?access_token=' + window.token,
          id: $scope.currentLight.id
          on: $scope.currentLight.on
          color: $scope.currentLight.color
      .success (data, status) ->
        $scope.applyText = "Refreshing light states..."
        $scope.refreshLights ->
          $scope.applying = ""
          $scope.applying = false
          $("#modalEditLight").modal('toggle')
          return

    # Refresh light status Logic
    $scope.loop = (time = 30) ->
      $timeout ->
          $scope.refreshLights()
      , time * 1000

    $scope.refreshLights = (callback = null)->
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
              $scope.loading = false
              if callback
                callback()
              else
                $scope.loop()
          .error ->
              $scope.loading = false
              if callback
                callback()
              else
                $scope.loop()
    $timeout ->
      if $scope.first
        $scope.first = false
        $scope.refreshLights()
    , 200