const express = require('express');
const router = express.Router();
const mascotaController = require('../controllers/mascotasController');


// Ruta para obtener todas las mascotas
router.get('/api/mascotas', mascotasController.getAllMascotas);

// Ruta para agregar una nueva mascota
router.post('/api/mascotas', mascotasController.createMascota);

// Ruta para obtener una mascota por ID
router.get('/api/mascotas/:id', mascotasController.getMascotaById);

// Ruta para actualizar una mascota por ID
router.put('/api/mascotas/:id', mascotasController.updateMascota);

// Ruta para eliminar una mascota por ID
router.delete('/api/mascotas/:id', mascotasController.deleteMascota);

module.exports = router;
