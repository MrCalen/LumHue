class exports.Routes
  constructor: (@app) ->

  init: =>
    @homeRoutes()
    @listingRoutes()
    @defaultRoutes()

  homeRoutes: =>
    home = require(global.appRoot + '/routes/home').HomeRoute
    homeRoute = new home(@app)
    homeRoute.registerHandlers()

  listingRoutes: =>
    listing = require(global.appRoot + '/routes/listing').ListingRoutes
    listing = new listing(@app)
    listing.registerHandler()

  defaultRoutes: =>
    defaultR = require(global.appRoot + '/routes/default').DefaultRoutes
    defaultR = new defaultR(@app)
    defaultR.registerHandler()
