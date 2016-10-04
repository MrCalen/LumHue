express = require('express');
app = express();
server = require('http').createServer(app);
server.listen(9095);
io = require("socket.io").listen(server);
curl = require 'curlrequest'
console.log('listening on port 9095');

redis = require('redis');
subscribe = redis.createClient();

connections = {}

io.of '/socket.io', (socket) ->
  socket.on 'auth', (msg) ->
    json = JSON.parse msg
    token = json.token
    curl.request {
        url: 'https://calen.mr-calen.eu/api/user?access_token=' + token
      }
    , (err, data) ->
      usr = JSON.parse data
      if usr.id?
        connections[token] = usr

subscribe.subscribe('lights');
subscribe.on "message", (channel, message) ->
  console.log message