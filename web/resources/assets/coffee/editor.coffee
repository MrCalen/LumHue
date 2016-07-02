angular.module('EditorModule', [])
.controller 'EditorController', ($scope, $http, $timeout, $window) ->

  $scope.beaconOptions = [];
  for i in [1...3]
    $scope.beaconOptions.push
      label: 'Light Off Light ' + i,
      value: -i
    $scope.beaconOptions.push
      label: 'Light On Light ' + i,
      value: i

  $scope.base_url = window.base_url
  $scope.$watch ->
    return $window.currentItem
  , ->
    $window.currentItem.isLight = $window.currentItem.metadata.itemName == 'Floor Lamp' if $window.currentItem?.metadata?
    $window.currentItem.isBeacon = $window.currentItem.metadata.itemName == 'Beacon' if $window.currentItem?.metadata?
    $scope.currentItem = $window.currentItem

  $scope.savePlan = ->
    data = blueprint3d.model.exportSerialized();
    $http.post '/api/editor/save?access_token=' + window.token,
      data: data
    .success (data, status) ->
      console.log(data)
    .error (err) ->
      console.error(err)

  $scope.addLightToItem = ->
    item = $window.currentItem
    color = blueprint3d.three.getScene().getColor $scope.lights[item.light_id].rgbhex
    if item.children.length
      item.children[0].color = color
    else
      light = blueprint3d.model.scene.addLight color
      $window.currentItem.add light

  $scope.fetch = ->
    $http.get $scope.base_url + "/api/bridge",
      params:
        access_token: window.token
    .success (data, status) ->
      $scope.lights = data.lights
      blueprint3d.three.getScene().getItems().forEach (elt) ->
        if elt.light_id? and elt.children.length
          light = $scope.lights[elt.light_id]
          child = elt.children[0]
          child.color = blueprint3d.three.getScene().getColor(light.rgbhex);
      $scope.refreshLightsColors()

  $scope.refreshLightsColors = ->
    $timeout ->
      $scope.fetch()
    , 10000

  $timeout ->
    $scope.fetch()
  , 2000