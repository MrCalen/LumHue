app.config ['$routeProvider', ($routeProvider) ->
  $routeProvider

  .when '/',
    redirectTo: '/dashboard'

  .when '/dashboard',
    templateUrl: '../views/dashboard.html'

  .when '/lights',
    templateUrl: '../views/lights.html'

  .when '/ambiances',
    templateUrl: '../views/ambiance.html'

  .otherwise
    redirectTo: '/dashboard'
]