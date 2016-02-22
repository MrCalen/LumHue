class HomeController
  constructor: ->

  homeRequest: (request, response) =>
    response.send 'HelloWorld'

module.exports = HomeController
