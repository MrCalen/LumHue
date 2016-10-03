app.controller 'WebSocketController', ($scope, $http, $timeout, $window, $sce, $rootScope) ->
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

  $scope.onMessage = (e) ->
    message = JSON.parse e.data
    if message.type == 'message'
      $scope.messages.push
        'content' : $sce.trustAsHtml message.content
        'author'  : message.author
        'date' : message.date
    $scope.$apply()
    $rootScope.$emit 'refresh', {}
    $('#chat').animate({
      scrollTop: $('#chat')[0].scrollHeight }, 50
    );

  ## On receive
  messageconn.onmessage = $scope.onMessage
  audioconn.onmessage = $scope.onMessage

  ######### AUDIO RECORD #########

  window.AudioContext = window.AudioContext || window.webkitAudioContext;

  audioContext = new AudioContext();
  $scope.initRecord = ->
    if (!navigator.getUserMedia)
      navigator.getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (!navigator.cancelAnimationFrame)
      navigator.cancelAnimationFrame = navigator.webkitCancelAnimationFrame || navigator.mozCancelAnimationFrame;
    if (!navigator.requestAnimationFrame)
      navigator.requestAnimationFrame = navigator.webkitRequestAnimationFrame || navigator.mozRequestAnimationFrame;

    navigator.getUserMedia {audio : true, video : false},
      (stream) ->
        inputPoint = audioContext.createGain();

        realAudioInput = audioContext.createMediaStreamSource(stream);
        audioInput = realAudioInput;
        audioInput.connect(inputPoint);
        analyserNode = audioContext.createAnalyser();
        analyserNode.fftSize = 2048;
        inputPoint.connect( analyserNode );
        $scope.audioRecorder = new Recorder( inputPoint );
        zeroGain = audioContext.createGain();
        zeroGain.gain.value = 0.0;
        inputPoint.connect( zeroGain );
        zeroGain.connect( audioContext.destination );
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
    $scope.audioRecorder.clear();
    $scope.audioRecorder.record();
    $scope.recording = true
  $scope.stopRecord = ->
    $scope.recording = false
    $scope.audioRecorder.stop()
    $scope.audioRecorder.getBuffer (buffers) ->
      $scope.audioRecorder.exportWAV (blob) ->
        $scope.sendAudioMessage(blob)
    return

  $scope.initRecord()

  # used by websockets
  $scope.removeUserInfo = ->
    $http.post $scope.base_url + '/api/preferences/chat?access_token=' + window.token, {}
    .success (data, status) ->
      $scope.messages.push
        'content' : 'Preferences removed'
        'author'  : 'LumHue Bot'
        'date' : new Date("now")

  socket = io.connect("https://calen.mr-calen.eu/socket.io", { transports: ['websocket', 'polling'] });
