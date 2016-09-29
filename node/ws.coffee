express = require('express');
app = express();
server = require('http').createServer(app);
io = require("socket.io").listen(server);

server.listen(9095);
console.log('listening on port 9095');

io.sockets.on 'connection', (socket) ->
  console.log('user connected');

  msg = "user connected!!!";
  socket.emit('message', msg);

  socket.on 'disconnect', ->
    console.log('user disconnected')

  socket.on 'message', (msg) ->
    socket.emit('message', msg);
    console.log('message: ' + msg)

