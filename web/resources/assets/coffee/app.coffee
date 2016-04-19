app = angular.module 'LumHue', ['colorpicker.module', 'uiSwitch'], ($interpolateProvider) ->
  $interpolateProvider.startSymbol('{$');
  $interpolateProvider.endSymbol('$}');
window.app = app