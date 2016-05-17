app.controller 'AmbianceController', ($scope, $http, $timeout) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    # Refresh light status Logic
    $scope.loop = (time = 10) ->
      $timeout ->
          $scope.refreshAmbiances()
      , time * 1000

    $scope.applyAmbiance = (id) ->
      ambiance = $scope.ambiances[id]
      $http.post $scope.base_url + '/api/ambiance/apply?access_token=' + window.token,
        ambiance_id: ambiance.uniq_id
      .success (data, status) ->

    $scope.deleteAmbiance = (id) ->
      result = window.confirm("Do you really want to delete this ambiance ?")
      if result
        ambiance = $scope.ambiances[id]
        $http.post $scope.base_url + '/api/ambiance/remove?access_token=' + window.token,
          ambiance_id: ambiance.uniq_id
        .success (data, status) ->
          window.location.reload()

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
        uniq_id: oldAmbiance.uniq_id
      $('#modalUpdateAmbiance').modal('toggle')
      return

    $scope.addNewAmbianceSlide = ->
      tmp =
        duration: 10
        lightscolors: [
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
      $scope.currentAmbiance.lights.push tmp
      return

    $scope.toggleNewAmbiance = ->
      $scope.currentAmbiance =
        name: "New ambiance"
        lights: [
          duration: 10
          lightscolors: [
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

    $scope.updateAmbiance = ->
      $http.post $scope.base_url + '/api/ambiance/update?access_token=' + window.token,
        ambiance: $scope.currentAmbiance
        ambiance_id: $scope.currentAmbiance.uniq_id
      .success (data, status) ->
        $scope.savingText = "Saved"
        $scope.refreshAmbiances ->
          $scope.savingText = ""
          $scope.saving = false
          $("#modalUpdateAmbiance").modal('toggle')

    $scope.deleteSlide = (carousel) ->
      currentItem = $(carousel + " .item.active" )
      currentIndex = $(carousel + ' .item').index(currentItem)
      return if currentIndex == 0
      $(carousel).carousel('prev')
      $scope.currentAmbiance.lights.splice(currentIndex, 1)
      return

    $scope.getSlideIndex = (carousel) ->
      currentItem = $(carousel + " .item.active" )
      currentIndex = $(carousel + ' .item').index(currentItem)
      return currentIndex