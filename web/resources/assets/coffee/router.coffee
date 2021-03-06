app.config ['$routeProvider', ($routeProvider) ->
  $routeProvider

  .when '/',
    redirectTo: '/dashboard'

  .when '/dashboard',
    templateUrl: '../views/dashboard.html'
    current: 'dashboard'

  .when '/lights',
    templateUrl: '../views/lights.html'
    current: 'lights'

  .when '/ambiances',
    templateUrl: '../views/ambiance.html'
    current: 'ambiances'
  .when '/routines',
    templateUrl: '../views/routines.html'
    current: 'routines'

#  .otherwise
#    redirectTo: '/dashboard'
]