LoginApp = angular.module 'LoginApp', [], ($interpolateProvider) ->
  $interpolateProvider.startSymbol('{$')
  $interpolateProvider.endSymbol('$}')

LoginApp.controller 'LoginController', ($scope) ->
    
