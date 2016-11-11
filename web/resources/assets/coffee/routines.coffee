app.controller 'RoutinesController', ($rootScope, $scope, $http, $timeout) ->
  $scope.base_url = window.base_url
  $scope.token = window.token

  $scope.initRoutine = ->
    $scope.newRoutine =
      name: "Nouvelle Routine"
      lights: [false, false, false]
      days: [false, false, false, false, false, false, false]
      rec: true
      m: 0
      h: 0

  $scope.initRoutine()

  $scope.loading = false
  $scope.days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche']

  $scope.routines = []
  $scope.getRoutines = ->
    $http.get($scope.base_url + "/api/routines",
      params:
        access_token: window.token
    )
    .success (data, status) ->
      $scope.routines = data
    .error ->
      console.error 'err'

  $scope.getRoutines()

  $scope.save = (routineId) ->
    routine = $scope.routines[routineId]
    $scope.loading = true
    $http.post $scope.base_url + '/api/routines/edit?access_token=' + window.token,
      routine: routine
    .success (data, status) ->
      $scope.loading = false

  $scope.createRoutine = ->
    $scope.loading = true
    $http.post $scope.base_url + '/api/routines/create?access_token=' + window.token,
      routine: $scope.newRoutine
    .success (data, status) ->
      $scope.loading = false
      $scope.getRoutines()
      $scope.initRoutine()


  $scope.removeLight = (routine, lightId) ->
    routine.lights[lightId] = false

  $scope.addLight = (routine, lightId) ->
    routine.lights[lightId] = true