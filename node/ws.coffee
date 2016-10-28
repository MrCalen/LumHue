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
subscribe.subscribe('lights');

listen = (socket) ->
  console.log "new connection"
  socket.on 'auth', (msg) ->
    try
      json = JSON.parse msg
    catch error
      json = msg

    token = json.token

    curl.request {
        url: 'https://calen.mr-calen.eu/api/user?access_token=' + token
      }
    , (err, data) ->
      usr = JSON.parse data
      if usr.id?
        connections[token] = usr
    socket.emit 'auth', {}
    subscribe.on "message", (channel, message) ->
      socket.emit('message', JSON.parse(message))

io.on 'connection', (socket) ->
  listen(socket)

io.of '/socket.io', (socket) ->
  listen(socket)