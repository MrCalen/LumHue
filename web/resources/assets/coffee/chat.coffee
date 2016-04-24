window.app.controller 'HueChatController', ($scope, $http, $timeout) ->
  $scope.username = window.username
  $scope.messages = [];

  if (!WebSocket)
      console.error('No Websocket')
  conn = new WebSocket("wss://lumhue.mr-calen.eu/ws");

  $scope.pingServer = ->
    conn.send JSON.stringify
      'protocol' : 'chat',
      'type' : 'ping',
      'user' : $scope.username
    $timeout $scope.pingServer, 30000


  conn.onopen = (e) ->
    conn.send JSON.stringify {
      'protocol' : 'chat',
      'type' : 'auth',
      'data' : {
        'name' : window.username
      }
    }
    $scope.pingServer()

  conn.onmessage =  (e) ->
    message = JSON.parse e.data
    console.log message
    if (message.type == 'auth')
      $scope.users = message.users;
    else if (message.type == 'message')
      $scope.messages.push {
        'content' : message.content,
        'author'  : message.author,
        'date' : message.date
    }
    $scope.$apply();

  $scope.sendMessage = ->
    message = angular.copy $scope.currentMessage
    conn.send(JSON.stringify
      'protocol' : 'chat',
      'type' : 'message',
      'content' : message,
      'author' : window.username
    )

  $scope.sendBotMessage = ->
    message = angular.copy $scope.currentMessage;
    conn.send(JSON.stringify
      'protocol' : 'bot',
      'type' : 'bot',
      'content' : message,
      'author' : window.username
    )
