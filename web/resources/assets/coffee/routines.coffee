app.controller 'RoutinesController', ($rootScope, $scope, $http, $timeout) ->
  $scope.base_url = window.base_url
  $scope.token = window.token

  $scope.days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']

  $scope.routines = []
  $scope.getRoutines = ->
    $http.get($scope.base_url + "/api/routines",
      params:
        access_token: window.token
    )
    .success (data, status) ->
      $scope.routines = data
      console.log $scope.routines
    .error ->
      console.error 'err'

  $scope.getRoutines()