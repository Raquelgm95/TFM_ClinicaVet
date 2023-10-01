const mysql = require('mysql');
const dbConfig = require('../config/database');
const Cita = require('../models/citaModel');

// Conectar a la base de datos
const connection = mysql.createConnection(dbConfig);

// Obtener todas las citas
exports.getAllCitas = (req, res) => {
    Cita.getAllCitas((err, data) => {
        if (err) {
            res.status(500).send({
                message: err.message || 'Se produjo un error al obtener todas las citas.'
            });
        } else {
            res.send(data);
        }
    });
};

// Agregar una nueva cita
exports.createCita = (req, res) => {
    // Valida si los campos requeridos están presentes en la solicitud
    if (!req.body.Fecha || !req.body.Hora || !req.body.Descripcion || !req.body.ClienteID) {
        res.status(400).send({ message: 'Todos los campos son obligatorios.' });
        return;
    }

    // Crea un objeto cita con los datos de la solicitud
    const cita = new Cita({
        Fecha: req.body.Fecha,
        Hora: req.body.Hora,
        Descripcion: req.body.Descripcion,
        ClienteID: req.body.ClienteID
    });

    // Guarda la nueva cita en la base de datos
    Cita.createCita(cita, (err, data) => {
        if (err) {
            res.status(500).send({
                message: err.message || 'Se produjo un error al crear la cita.'
            });
        } else {
            res.send(data); // Devuelve la nueva cita creada
        }
    });
};

// Obtener una cita por su ID
exports.getCitaById = (req, res) => {
    const id = req.params.id;

    // Utiliza el método correspondiente del modelo para obtener la cita por su ID
    Cita.getCitaById(id, (err, data) => {
        if (err) {
            if (err.kind === "not_found") {
                res.status(404).send({
                    message: `Cita no encontrada con el ID ${id}.`
                });
            } else {
                res.status(500).send({
                    message: "Error al recuperar la cita con el ID " + id
                });
            }
        } else {
            res.send(data);
        }
    });
};

// Actualizar una cita por ID
exports.updateCita = (req, res) => {
    const id = req.params.id;

    // Verifica si el cuerpo de la solicitud está vacío
    if (!req.body) {
        res.status(400).send({
            message: "El contenido no puede estar vacío."
        });
        return;
    }

    // Utiliza el método correspondiente del modelo para actualizar la cita por su ID
    Cita.updateCitaById(id, new Cita(req.body), (err, data) => {
        if (err) {
            if (err.kind === "not_found") {
                res.status(404).send({
                    message: `Cita no encontrada con el ID ${id}.`
                });
            } else {
                res.status(500).send({
                    message: "Error al actualizar la cita con el ID " + id
                });
            }
        } else {
            res.send(data);
        }
    });
};

// Eliminar una cita por ID
exports.deleteCita = (req, res) => {
    const id = req.params.id;

    // Utiliza el método correspondiente del modelo para eliminar la cita por su ID
    Cita.deleteCitaById(id, (err, data) => {
        if (err) {
            if (err.kind === "not_found") {
                res.status(404).send({
                    message: `Cita no encontrada con el ID ${id}.`
                });
            } else {
                res.status(500).send({
                    message: "Error al eliminar la cita con el ID " + id
                });
            }
        } else {
            res.send({ message: 'Cita eliminada exitosamente.' });
        }
    });
};
