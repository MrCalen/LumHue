app = window.app

app.controller "DashboardController", ($scope, $timeout, localStorageService) ->

  $scope.types = [
    'graph'
    'weather'
    'history'
  ]

  $scope.sizes =
    'graph': [
      'small'
      'half'
      'medium'
      'large'
    ]
    'weather': [
      'small'
      'half'
    ]
    'history': [
      'small'
      'half'
      'medium'
    ]
  $scope.lights = [
    "1",
    "2",
    "3",
    "all"
  ]

  $scope.granularities = [
    'none'
    'days'
    'hours'
  ]
  $scope.currentWidget =
    widget_type: $scope.types[0]

  $scope.onChange = ->
    $scope.currentWidget.availableStates = $scope.sizes[$scope.currentWidget.widget_type]
    $scope.currentWidget.size = $scope.currentWidget.availableStates[0]

  $scope.onChange()

  $scope.createWidget = ->
    delete $scope.currentWidget.availableStates
    console.log $scope.currentWidget
    $scope.models.dropzones['A'].push(angular.copy $scope.currentWidget)
    console.log $scope.models
    $scope.currentWidget = {}

  defaultModel = {
    dropzones: {
      "A": [
        {
          "widget_type": "graph",
          "size": "large",
        },
        {
          "size": "medium",
          "widget_type": "weather"
        },
        {
          "size": "small",
          "widget_type": "history"
        }
      ],
    }
  };

  $scope.applyNewSize = (size, widgetid) ->
    $scope.models.dropzones['A'][widgetid].size = size

  $scope.removeWidget = (widgetid) ->
    $scope.models.dropzones['A'].splice(widgetid, 1)

  $scope.resetDashboard = ->
    $scope.models = defaultModel

  $scope.models = localStorageService.cookie.get('dashboard')
  if !$scope.models?
    console.log 'default'
    $scope.models = defaultModel

  $timeout ->
    $scope.$watch 'models.dropzones', (model) ->
      model =
        dropzones: model
      localStorageService.cookie.set('dashboard', model)
    , true
  , 1000