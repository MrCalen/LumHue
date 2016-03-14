var HueChatApp = angular.module('HueChat', [], function($interpolateProvider) {
  $interpolateProvider.startSymbol('{$');
  $interpolateProvider.endSymbol('$}');
});

HueChatApp.controller('HueChatController', function ($scope, $http, $timeout)
{
  $scope.username = window.username
  $scope.messages = [];

  if (!WebSocket)
    console.error('No Websocket');
  var conn = new WebSocket("wss://lumhue.mr-calen.eu/ws");

  $scope.pingServer = function() {
    conn.send(JSON.stringify({
      'protocol' : 'chat',
      'type' : 'ping',
      'user' : $scope.username,
    }));
    $timeout($scope.pingServer, 30000);
  }

  conn.onopen = function (e) {
    conn.send(JSON.stringify({
      'protocol' : 'chat',
      'type' : 'auth',
      'data' : {
        'name' : window.username
      }
    }));
    $scope.pingServer();
  };

  conn.onmessage = function (e) {
    var message = JSON.parse(e.data);
    if (message.type === 'auth')
      $scope.users = message.users;
    else if (message.type === 'message')
      $scope.messages.push({
        'content' : message.content,
        'author'  : message.author,
        'date' : message.date });
    $scope.$apply();
  };

  $scope.sendMessage = function () {
    var message = angular.copy($scope.currentMessage);
    conn.send(JSON.stringify({
      'protocol' : 'chat',
      'type' : 'message',
      'content' : message,
      'author' : window.username
    }));
  };

});
