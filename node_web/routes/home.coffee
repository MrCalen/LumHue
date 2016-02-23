Router = require(global.appRoot + '/routes/router').Router

class exports.HomeRoute extends Router
  constructor: (app) ->
    super(app)

  controller: =>
    HomeController = require(global.appRoot + '/controller/home_controller')
    controller = new HomeController()
    return controller

  registerHandlers: =>
    @app.get '/', (request, response) =>
      @controller().homeRequest(request, response)
