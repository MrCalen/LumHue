express = require('express')
app = express()
path = require('path');
global.appRoot = path.resolve(__dirname);

Route = require(global.appRoot + '/routes/routes')
routes = new Route(app)
routes.init()

app.listen 3000
