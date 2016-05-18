app.controller 'WebSocketController', ($scope, $http, $timeout, $window) ->
  $scope.base_url = window.base_url
  $scope.token = window.token

  $scope.recordedInput = {}
  conn = new WebSocket("wss://lumhue.mr-calen.eu/wsaudio");
  conn.binaryType = 'arraybuffer';

  $scope.pingServer = ->
    conn.send JSON.stringify
      'protocol' : 'chat',
      'type' : 'ping',
      'user' : $scope.username
    $timeout $scope.pingServer, 30000

  conn.onopen = (e) ->
    json = JSON.stringify
      'type' : 'auth',
      'data': {
        'token': window.token
        'name': window.username
      }
    conn.send json
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
