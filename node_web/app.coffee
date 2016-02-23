express = require('express')
app = express()
path = require('path');
global.appRoot = path.resolve(__dirname);

module.Routing = {}

Route = require(global.appRoot + '/routes/routes').Routes
routes = new Route(app)
routes.init()

app.listen 3000
