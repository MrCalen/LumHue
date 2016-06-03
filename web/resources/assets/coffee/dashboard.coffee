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
    "1"
    "2"
    "3"
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
    localStorageService.cookie.set('dashboard', $scope.models)

  $scope.onChange()

  $scope.createWidget = ->
    delete $scope.currentWidget.availableStates
    $scope.models.dropzones['A'].push(angular.copy $scope.currentWidget)
    $scope.currentWidget = {}
    $scope.onChange()

  defaultModel =
    dropzones:
      "A": [
          "size": "medium"
          "widget_type": "weather"
        ,
          "size": "small"
          "widget_type": "history"
        ,
          "widget_type": "graph"
          "size": "half"
          "light": 1
          "granularity": "days"
        ,
          "widget_type": "graph"
          "size": "half"
          "light": 2
          "granularity": "days"
      ]

  $scope.applyNewSize = (size, widgetid) ->
    $scope.models.dropzones['A'][widgetid].size = size
    $scope.onChange()

  $scope.applyNewGranularity = (granularity, widgetid) ->
    $scope.models.dropzones['A'][widgetid].granularity = granularity
    $scope.onChange()

  $scope.removeWidget = (widgetid) ->
    $scope.models.dropzones['A'].splice(widgetid, 1)
    $scope.onChange()

  $scope.resetDashboard = ->
    $scope.models = defaultModel
    $scope.onChange()

  $scope.models = localStorageService.cookie.get('dashboard')
  $scope.models = defaultModel if !$scope.models?

  $timeout ->
    $scope.$watch 'models.dropzones', (model) ->
      model =
        dropzones: model
      localStorageService.cookie.set('dashboard', model)
    , true
  , 1000