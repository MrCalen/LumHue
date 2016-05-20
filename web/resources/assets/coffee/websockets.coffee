app.controller 'WebSocketController', ($scope, $http, $timeout, $window, $sce) ->
  $scope.base_url = window.base_url
  $scope.token = window.token
  $scope.username = window.username

  $scope.recordedInput = {}
  $scope.messages = []

  audioconn = new WebSocket("wss://lumhue.mr-calen.eu/wsaudio")
  messageconn = new WebSocket("wss://lumhue.mr-calen.eu/ws")
  audioconn.binaryType = 'arraybuffer'

  $scope.pingServer = ->
    if messageconn.readyState == 1
      messageconn.send JSON.stringify
        'protocol' : 'chat'
        'type' : 'ping'
        'data':
          'user' : $scope.username
        'token': $scope.token
    if audioconn.readyState == 1
      audioconn.send JSON.stringify
        'protocol' : 'chat'
        'type' : 'ping'
        'user' : $scope.username
    $timeout $scope.pingServer, 30000

  ## Auth
  audioconn.onopen = (e) ->
    json = JSON.stringify
      'type' : 'auth'
      'data':
        'token': window.token
        'name': window.username
    audioconn.send json
    $scope.pingServer()

  messageconn.onopen = (e) ->
    messageconn.send JSON.stringify
      'protocol' : 'chat'
      'type' : 'auth'
      'data' :
        'name' : window.username
      'token' : window.token

    $scope.pingServer()


  ## Send messages
  $scope.sendAudioMessage = (blob) ->
    audioconn.send blob, { binary: true }

  $scope.sendMessage = ->
    message = angular.copy $scope.currentMessage
    return if message == ''
    messageconn.send JSON.stringify
      'type': 'message'
      'content' : message
      'author' : window.username
      'token': window.token
      'message': message

  ## On receive
  messageconn.onmessage = (e) ->
    console.log e
    message = JSON.parse e.data
    if message.type == 'message'
      $scope.messages.push
        'content' : $sce.trustAsHtml message.content
        'author'  : message.author
        'date' : message.date
    $('#chat').animate({
      scrollTop: $('#chat')[0].scrollHeight }, 50
    );
    $scope.$apply();


  audioconn.onmessage = (e) ->
    console.log e
    $scope.$apply();


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

  $scope.toggleRecording = ->
    if !$scope.recording
      $scope.startRecord()
    else
      $scope.stopRecord()

  $scope.startRecord = ->
    $scope.recordRTC.startRecording()
    $scope.recording = true
  $scope.stopRecord = ->
    $scope.recording = false
    $scope.recordRTC.stopRecording (audioUrl) ->
      blob = $scope.recordRTC.getBlob()
      $scope.sendAudioMessage(blob)

  $scope.initRecord()
