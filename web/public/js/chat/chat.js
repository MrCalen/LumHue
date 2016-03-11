var app = angular.module('HueChat', []);
app.controller('HueChatController', function ($scope, $http, $timeout)
{
    if (!WebSocket)
        console.error('No Websocket');
    $scope.conn = new WebSocket("ws://mr-calen.eu:9090");

    $scope.conn.onopen = function (e) {
        $scope.conn.send('something');
    };

    $scope.conn.onmessage = function (e) {
        console.log('got something: ' + e.data);
    };

    $scope.conn.onmessage = function (event) {
        console.log("coucou");
    };
});
