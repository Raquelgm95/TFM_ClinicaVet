const express = require('express');
const { body, validationResult } = require('express-validator');
const router = express.Router();
const clientesController = require('../controllers/clientesController');

// Definir reglas de validación para la creación de clientes
const createClienteValidators = [
    body('Nombre').notEmpty().trim().escape(),
    body('Apellidos').notEmpty().trim().escape(),
    body('Telefono').notEmpty().trim().escape(),
    body('Correo').isEmail().normalizeEmail(),
    body('Direccion').notEmpty().trim().escape(),
];

// Middleware para manejar errores de validación
const handleValidationErrors = (req, res, next) => {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
        return res.status(400).json({ errors: errors.array() });
    }
    next();
};

// Ruta para obtener todos los clientes
router.get('/clientes', clientesController.getAllClientes);

// Ruta para agregar un nuevo cliente
router.post('/clientes', createClienteValidators, handleValidationErrors, clientesController.createCliente);

// Ruta para obtener un cliente por ID
router.get('/clientes/:id', clientesController.getClienteById);

// Ruta para actualizar un cliente por ID
router.put('/clientes/:id', clientesController.updateCliente);

// Ruta para eliminar un cliente por ID
router.delete('/clientes/:id', clientesController.deleteCliente);

module.exports = router;