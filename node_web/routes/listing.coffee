Router = require(global.appRoot + '/routes/router').Router

class exports.ListingRoutes extends Router
  constructor: (app) ->
    super(app)

  registerHandler: =>
    @app.get '/listing', (request, response) =>
      routes = {}
      for elt in @app._router.stack
        continue if !elt.route
        routes[elt.route.path] = elt.route
      response.send (JSON.stringify routes)
