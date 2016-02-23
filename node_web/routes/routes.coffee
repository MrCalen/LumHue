class exports.Routes
  constructor: (@app) ->

  init: =>
    @homeRoutes()
    @listingRoutes()

  homeRoutes: =>
    home = require(global.appRoot + '/routes/home').HomeRoute
    homeRoute = new home(@app)
    homeRoute.registerHandlers()

  listingRoutes: =>
    listing = require(global.appRoot + '/routes/listing').ListingRoutes
    listing = new listing(@app)
    listing.registerHandler()
