app = window.app

app.controller "DashboardController", ($scope, $timeout, localStorageService) ->

  $scope.type = [
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

  $scope.granularities = [
    'none'
    'days'
    'hours'
  ]

  defaultModel = {
    dropzones: {
      "A": [
        {
          "type": "item",
          "widget_type": "plot",
          "size": "large",
          "id": "1"
        },
        {
          "type": "item",
          "size": "medium",
          "id": "2",
          "widget_type": "weather"
        },
        {
          "type": "item",
          "size": "small",
          "id": "3",
          "widget_type": "history"
        }
      ],
    }
  };

  $scope.applyNewSize = (size, widgetid) ->
    $scope.models.dropzones['A'][widgetid].size = size

  $scope.removeWidget = (widgetid) ->
    delete $scope.models.dropzones['A'][widgetid]

  $scope.models = localStorageService.cookie.get('dashboard')
  if !$scope.models?
    $scope.models = defaultModel

  $timeout ->
    $scope.$watch 'models.dropzones', (model) ->
      model =
        dropzones: model
      localStorageService.cookie.set('dashboard', model)
    , true
  , 1000