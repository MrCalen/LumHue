Router = require(global.appRoot + '/routes/router').Router

class exports.DefaultRoutes extends Router
  constructor: (app) ->
    super(app)

  registerHandler: =>
    @app.get '*', (request, response) =>
      response.send(JSON.parse '{ "name": "not found", "error": "404" }')
