express = require 'express'
http = require 'http'
hue = require 'node-hue-api'
HueApi = require("node-hue-api").HueApi
app = express()

bodyParser = require('body-parser');
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

app.get '/getBridgeIP', (req, res) ->
  hue.nupnpSearch().then((result) ->
    bridge_ip = result[0].ipaddress
    res.send bridge_ip
  ).done();


app.get '/getBridge', (req, res) ->
  api = new HueApi("192.168.1.25", '328f4f1a291e01cd550e24950d02d4e')
  api.lights()
  .then (result) ->
    res.send result
  .done()

app.post '/setLight', (req, res) ->
  api = new HueApi("192.168.1.25", '328f4f1a291e01cd550e24950d02d4e')
  req.body.on = JSON.parse req.body.on

  api.setLightState(1, req.body)
  .then (result) ->
    res.send result
  .done()


app.listen 3000, ->
  console.log 'Example app listening on port 3000!'
