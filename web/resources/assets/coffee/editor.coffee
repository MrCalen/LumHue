angular.module('EditorModule', [])
.controller 'EditorController', ($scope, $http, $timeout) ->

  $scope.savePlan = ->
    data = blueprint3d.model.exportSerialized();
    $http.post '/api/editor/save?access_token=' + window.token,
      data: data
    .success (data, status) ->
      console.log(data)
    .error (err) ->
      console.error(err)
