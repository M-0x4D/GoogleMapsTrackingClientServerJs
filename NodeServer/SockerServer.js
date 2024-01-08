const express = require('express');
const app = express();
const http = require('http');
const https = require('https');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
    cors: {
        origin: "*",
    }
});
const cors = require('cors')
app.use(express.json())
app.use(cors())

const PORT = 9876

io.on('connection', (socket) => {

    console.log('a user connected');


    // join to specific room
    socket.on('join', (room) => {
        console.log(`joined to room ${room}`);
        socket.join(room)
    })

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // socket.in().emit() ====>  sends the event event with data data to all clients in the specified room room, except for the sender.
    // socket.to().emit() ===> sends the event event with data data to all connected clients in the specified room room, including the sender.
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    socket.on('typing', (msg) => socket.in(msg.room).emit('typing', msg.status))
    socket.on('stop typing', (msg) => socket.in(msg.room).emit('stop typing', msg.status))


    // send message 
    socket.on('message', (msg) => {
        console.log('message: ' + msg);
        latLng = {
            lat: 30.05865,
            lng: 31.2357
        };
        
        // io.emit('chatRoom' , msg); ====>   send event to all connected calls
        // socket.emit() ====> send an event to the client associated with the specific Socket object.


        // setTimeout(()=>{
        socket.emit('message', latLng);

        // },3000);
        // socket.emit(msg.data);
        // call api service 
        //   https.get('https://jsonplaceholder.typicode.com/users', (res) => {
        //   console.log('statusCode:', res.statusCode);

        //   res.on('data', (d) => {
        //     process.stdout.write(d);
        //     // console.log(d);
        //   });
        // }).on('error', (e) => {
        //   console.error(e);
        // });
    });


    socket.on('disconnect', () => {
        console.log('User disconnected');
    });

});

server.listen(PORT, () => {
    console.log(`listening on *:${PORT}`);
});