require('dotenv').config();
const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const cors = require('cors');

const app = express();
const server = http.createServer(app);
const io = new Server(server, {
  cors: {
    origin: process.env.ALLOWED_ORIGINS || '*',  // Permite todas as origens (ou as definidas no .env)
    methods: ["GET", "POST"],  // Métodos HTTP permitidos
    credentials: true  // Se precisar de cookies ou credenciais
  }
});

// Configurar CORS para o servidor HTTP também
app.use(cors({
  origin: (origin, callback) => {
    const allowedOrigins = process.env.ALLOWED_ORIGINS || "*";
    if (allowedOrigins === '*' || allowedOrigins.split(',').includes(origin)) {
      callback(null, true);
    } else {
      callback(new Error('Not allowed by CORS'));
    }
  }
}));

// Quando um cliente se conecta
io.on('connection', (socket) => {
  console.log('Novo cliente conectado:', socket.id);

  // O cliente deve se juntar a uma "room" baseada no ID da clínica
  socket.on('joinRoom', (clinicaId) => {
    socket.join(clinicaId);
    console.log(`Cliente ${socket.id} entrou na sala da clínica ${clinicaId}`);
  });

  socket.on('message', ({ clinicaId, mensagem }) => {
    console.log(`Mensagem recebida da clínica ${clinicaId}:`, mensagem);

    // Envia a mensagem apenas para os clientes na "room" correspondente
    io.to(clinicaId).emit('response', mensagem);
});

  // Quando o cliente se desconecta
  socket.on('disconnect', () => {
    console.log('Cliente desconectado:', socket.id);
  });
});

// Inicia o servidor na porta desejada
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Servidor WebSocket rodando na porta ${PORT}`);
});
