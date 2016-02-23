class HomeRoute
  constructor: (@app) ->

  controller: =>
    HomeController = require(global.appRoot + '/controller/home_controller')
    controller = new HomeController()
    return controller

  registerHandlers: =>
    @app.get '/', (request, response) =>
      @controller().homeRequest(request, response)

module.exports = HomeRoute
