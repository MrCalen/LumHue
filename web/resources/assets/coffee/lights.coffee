################################## LIGHTS ######################################

app.controller 'LightController', ($scope, $http, $timeout, $window) ->
    $scope.base_url = window.base_url
    $scope.token = window.token

    $scope.recordedInput = {}

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

    ########## WEB SOCKETS ########

    conn = new WebSocket("wss://lumhue.mr-calen.eu/wsaudio");
    conn.binaryType = 'arraybuffer';

    $scope.pingServer = ->
      conn.send JSON.stringify
        'protocol' : 'chat',
        'type' : 'ping',
        'user' : $scope.username
      $timeout $scope.pingServer, 30000

    conn.onopen = (e) ->
      $scope.pingServer()

    conn.onmessage =  (e) ->
      message = JSON.parse e.data
      console.log e

    $scope.sendMessage = (blob) ->
      conn.send blob, { binary: true }

    ######### AUDIO RECORD #########
    $scope.initRecord = ->
      navigator.userMedia = (
        $window.navigator.getUserMedia ||
          $window.navigator.webkitGetUserMedia ||
          $window.navigator.mozGetUserMedia ||
          $window.navigator.msGetUserMedia)

      navigator.getUserMedia {audio : true, video : false},
        (stream) ->
          $scope.recordRTC = RecordRTC(stream, { type: 'audio', mimeType: 'audio/ogg' })
          return
        (err) ->
          console.log(err)
          return


    $scope.recording = false

    $scope.startRecord = () ->
      $scope.recordRTC.startRecording()
      $scope.recording = true
    $scope.stopRecord = ->
      $scope.recording = false
      $scope.recordRTC.stopRecording (audioUrl) ->
        blob = $scope.recordRTC.getBlob()
        $scope.sendMessage(blob)

    $scope.initRecord()
