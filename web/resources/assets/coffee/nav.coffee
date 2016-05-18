app.controller 'VoiceController', ($scope, $http, $timeout) ->
  $scope.base_url = window.base_url
  $scope.token = window.token
