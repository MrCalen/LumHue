app.controller 'NavController', ($scope, $http, $timeout, $route) ->
  $scope.route = $route

  $scope.toggleModal = ->
    $("#newWidgetModal").modal('toggle')
    return