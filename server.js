const express = require("express");
const http = require("http");
const { Server } = require("socket.io");
const cors = require("cors");
const axios = require("axios");

const app = express();
app.use(cors());
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: "*",
  },
});
io.on("connection", (socket) => {
  console.log("User connected:", socket.id);
  socket.on("joinRoom", (roomId) => {
    socket.join(roomId);
    console.log(`User ${socket.id} joined room ${roomId}`);
  });
  // console.log('');
  socket.on("chat", (data) => {
    socket.to(data.roomID).emit("chat", data);
  });
  socket.on("disconnect", () => {
    // console.log("disconnected");
  });
});
server.listen(3000, () => {
  console.log("Server running");
});
