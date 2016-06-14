angular.module('EditorModule', [])
.controller 'EditorController', ($scope, $http, $timeout) ->

  $scope.base_url = window.base_url
  $scope.savePlan = ->
    data = blueprint3d.model.exportSerialized();
    $http.post '/api/editor/save?access_token=' + window.token,
      data: data
    .success (data, status) ->
      console.log(data)
    .error (err) ->
      console.error(err)

  $scope.fetch = ->
    $http.get($scope.base_url + "/api/bridge",
      params:
        access_token: window.token
    )
    .success (data, status) ->
      $scope.lights = data.lights
      blueprint3d.three.getScene().getItems().forEach (elt) ->
        return unless elt.light_id? and elt.children.length
        return unless $scope.lights.hasOwnProperty elt.light_id
        light = $scope.lights[elt.light_id]
        child = elt.children[0]
        child.color = blueprint3d.three.getScene().getColor(light.rgbhex);
      $scope.refreshLightsColors()

  $scope.refreshLightsColors = ->
    $timeout ->
      $scope.fetch();
    , 10000
  $scope.fetch()