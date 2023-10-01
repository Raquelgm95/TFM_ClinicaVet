// routes/index.js
const express = require('express');
const router = express.Router();

// Importa las rutas de cada recurso
const clientesRoutes = require('./clientes');
const mascotasRoutes = require('./mascotas');
const citasRoutes = require('./citas');

// Utiliza las rutas para cada recurso
router.use('/clientes', clientesRoutes);
router.use('/mascotas', mascotasRoutes);
router.use('/citas', citasRoutes);

module.exports = router;                             