window.app.controller 'AmbianceController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    # Refresh light status Logic
    $scope.loop = (time = 30) ->
      $timeout ->
          $scope.refreshLights()
      , time * 1000

    $scope.applyAmbiance = (id) ->
      ambiance = $scope.ambiances[id]
      $http.post $scope.base_url + '/api/ambiance/apply?access_token=' + window.token,
        ambiance_id: ambiance.uniq_id
      .success (data, status) ->
        console.log data

    $scope.refreshAmbiances = (callback = null)->
      if window.blurred
        $scope.loop(10)
        return

      $scope.loading = true
      $http.get($scope.base_url + "/api/ambiance",
                  params:
                    access_token: window.token
                )
          .success (data, status) ->
              tmp = []
              for key, value of data
                value.ambiance.uniq_id = value._id['$oid']
                tmp.push value.ambiance
              $scope.ambiances = tmp
              $scope.loading = false
              if callback
                callback()
              else
                $scope.loop()
          .error ->
              if callback
                callback()
              else
                $scope.loop()
    $timeout ->
        $scope.refreshAmbiances()
    , 200

    $scope.toggleEditAmbiance = (i) ->
      oldAmbiance = $scope.ambiances[i]
      $scope.currentAmbiance =
        name: oldAmbiance.name
        lights: oldAmbiance.lights
      $('#modalCreateAmbiance').modal('toggle')
      return

    $scope.toggleNewAmbiance = ->
      $scope.currentAmbiance =
        name: "new ambiance",
        lights: [
          id: 0,
          color:"rgb(255, 0, 0)",
          on: true
        ,
          id: 1,
          color:"rgb(0, 255, 0)",
          on: true
        ,
          id: 2,
          color:"rgb(0, 0, 255)",
          on: true
        ]
      $('#modalCreateAmbiance').modal('toggle')
      return

    $scope.saveNewAmbiance = ->
      $scope.saving = true
      $scope.savingText = "Saving new ambiance..."
      $http.post $scope.base_url + '/api/ambiance/create?access_token=' + window.token,
          ambiance: $scope.currentAmbiance
      .success (data, status) ->
        $scope.savingText = "Saved"
        $scope.refreshAmbiances ->
          $scope.savingText = ""
          $scope.saving = false
          $("#modalCreateAmbiance").modal('toggle')

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
              $scope.light = data.lights[1]
              $scope.name = $scope.light.name
              $scope.loading = false
              if callback
                callback()
              else
                $scope.loop()
          .error ->
              if callback
                callback()
              else
                $scope.loop()
    $timeout ->
        $scope.refreshLights()
    , 200
