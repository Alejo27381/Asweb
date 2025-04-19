const express = require('express');
const path = require('path');
const app = express();
const port = 3000;

// Directorio de archivos estáticos
app.use(express.static(path.join(__dirname, 'public')));

// Ruta para el index.html
app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public/views/index.html'));
});

// Rutas para otras páginas
const pages = [
  'index.html',
  'polos.html',
  'camisas.html',
  'poleras.html',
  'gorras.html',
  'pantalones.html',
  'zapatillas.html',
  'prototipo.html',
  'contactos.html',
  'administrador.html'
];

pages.forEach(page => {
  app.get(`/${page}`, (req, res) => {
    res.sendFile(path.join(__dirname, `public/views/${page}`));
  });
});

// Iniciar servidor
app.listen(port, () => {
  console.log(`Servidor corriendo en http://localhost:${port}`);
});
