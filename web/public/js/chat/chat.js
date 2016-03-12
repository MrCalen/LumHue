var app = angular.module('HueChat', [], function($interpolateProvider) {
        $interpolateProvider.startSymbol('{$');
        $interpolateProvider.endSymbol('$}');
    });
app.controller('HueChatController', function ($scope, $http, $timeout)
{
  console.log(window.username);
  $scope.username = window.username
  $scope.messages = [];

  if (!WebSocket)
    console.error('No Websocket');
  var conn = new WebSocket("ws://calen.mr-calen.eu:9090");

  conn.onopen = function (e) {
    conn.send(JSON.stringify({
      'type' : 'auth',
      'data' : {
        'name' : window.username
      }
    }));
  };

  conn.onmessage = function (e) {
    console.log(e.data);
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
    message = angular.copy($scope.currentMessage)
    conn.send(JSON.stringify({
      'type' : 'message',
      'content' : message,
      'author' : window.username
    }));
  };

});
