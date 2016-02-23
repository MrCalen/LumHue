class Routes
  constructor: (@app) ->

  init: =>
    @homeRoutes()

  homeRoutes: =>
    home = require(global.appRoot + '/routes/home')
    homeRoute = new home(@app)
    homeRoute.registerHandlers()

module.exports = Routes
