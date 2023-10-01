// es un archivo que contiene la lógica central de tu servidor backend. Aquí, configuras Express.js, defines las rutas 
//principales de tu API, estableces middleware (como manejo de solicitudes JSON y CORS), y lanzas tu servidor para que 
//escuche las solicitudes entrantes. Esta parte es completamente backend y se encarga de manejar las solicitudes y 
//respuestas, interactuar con la base de datos y proporcionar datos al frontend.

const express = require('express');
const bodyParser = require('body-parser');
const { body } = require('express-validator'); // Importa solo el body de express-validator

// Rutas de los diferentes recursos
const clientesRoutes = require('./routes/clientes');
const citasRoutes = require('./routes/citas');
const mascotasRoutes = require('./routes/mascotas');

const app = express();

// Configuración del middleware
app.use(bodyParser.json());

// Middleware para validar campos requeridos
const validateClienteFields = (req, res, next) => {
    const { Nombre, Apellidos, Telefono, Correo, Direccion } = req.body;

// Verificar si los campos requeridos están presentes en la solicitud
    if (!Nombre || !Apellidos || !Telefono || !Correo || !Direccion) {
        return res.status(400).json({ message: 'Todos los campos son obligatorios.' });
}
// Si todos los campos requeridos están presentes, continúa con la siguiente middleware o ruta
    next();
};

// Configuración de las rutas para cada recurso
app.use('/api/clientes', clientesRoutes); 
app.use('/api/citas', citasRoutes);
app.use('/api/mascotas', mascotasRoutes); 

// Configuración del puerto y arranque del servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor en funcionamiento en el puerto ${PORT}`);
});