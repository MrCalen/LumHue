app = angular.module 'LumHue', ['colorpicker.module', 'uiSwitch', 'ngSanitize'], ($interpolateProvider) ->
  $interpolateProvider.startSymbol('{$');
  $interpolateProvider.endSymbol('$}');
window.app = app