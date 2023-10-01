const mysql = require('mysql');
const dbConfig = require('../config/database');
const mascota = require('../models/mascotaModel'); 

// Conectar a la base de datos
const connection = mysql.createConnection(dbConfig);

// Obtener todas las mascotas
exports.getAllMascotas = (req, res) => {
  connection.query('SELECT * FROM Mascotas', (error, results) => {
    if (error) throw error;
    res.json(results);
});
};

// Agregar una nueva mascota
exports.createMascota = (req, res) => {
  // Valida si los campos requeridos están presentes en la solicitud
  if (!req.body.Nombre || !req.body.Edad || !req.body.Especie) {
      res.status(400).send({ message: 'Todos los campos son obligatorios.' });
      return;
  }

  // Crea un objeto mascota con los datos de la solicitud
  const nuevaMascota = new Mascota({
      Nombre: req.body.Nombre,
      Edad: req.body.Edad,
      Especie: req.body.Especie,
      Raza: req.body.Raza || null, // Puedes manejar valores nulos si no se proporcionan
      FechaNacimiento: req.body.FechaNacimiento || null,
      NumeroChip: req.body.NumeroChip || null,
      Sexo: req.body.Sexo || null,
      Peso: req.body.Peso || null,
      Observaciones: req.body.Observaciones || null,
  });

  // Guarda la nueva mascota en la base de datos
  Mascota.createMascota(nuevaMascota, (err, data) => {
      if (err) {
          res.status(500).send({
              message: err.message || 'Se produjo un error al crear la mascota.'
          });
      } else {
          res.send(data); // Devuelve la nueva mascota creada
      }
  });
};

// Obtener una mascota por ID
exports.getMascotaById = (req, res) => {
  const id = req.params.id;

  // Utiliza el método correspondiente del modelo para obtener la mascota por su ID
  Mascota.getMascotaById(id, (err, data) => {
    if (err) {
      if (err.kind === "not_found") {
        res.status(404).send({
          message: `Mascota no encontrada con el ID ${id}.`
        });
        } else {
          res.status(500).send({
            message: "Error al recuperar la mascota con el ID " + id
          });
        }
    } else {
      res.send(data);
    }
  });
};

// Actualizar una mascota por ID
exports.updateMascota = (req, res) => {
  const id = req.params.id;

  // Verifica si el cuerpo de la solicitud está vacío
  if (!req.body) {
      res.status(400).send({
          message: "El contenido no puede estar vacío."
      });
      return;
  }

  // Crea un objeto mascota con los datos de la solicitud
  const mascotaActualizada = new Mascota({
      Nombre: req.body.Nombre,
      Edad: req.body.Edad,
      Especie: req.body.Especie,
      Raza: req.body.Raza || null,
      FechaNacimiento: req.body.FechaNacimiento || null,
      NumeroChip: req.body.NumeroChip || null,
      Sexo: req.body.Sexo || null,
      Peso: req.body.Peso || null,
      Observaciones: req.body.Observaciones || null,
  });

  // Utiliza el método correspondiente del modelo para actualizar la mascota por su ID
  Mascota.updateMascotaById(id, mascotaActualizada, (err, data) => {
      if (err) {
        if (err.kind === "not_found") {
          res.status(404).send({
            message: `Mascota no encontrada con el ID ${id}.`
          });
          } else {
            res.status(500).send({
              message: "Error al actualizar la mascota con el ID " + id
            });
          }
      } else {
        res.send(data);
      }
  });
};

// Eliminar una mascota por ID
exports.deleteMascota = (req, res) => {
  const id = req.params.id;

  // Utiliza el método correspondiente del modelo para eliminar la mascota por su ID
  Mascota.deleteMascotaById(id, (err, data) => {
      if (err) {
        if (err.kind === "not_found") {
          res.status(404).send({
            message: `Mascota no encontrada con el ID ${id}.`
          });
          } else {
            res.status(500).send({
              message: "Error al eliminar la mascota con el ID " + id
            });
          }
      } else {
        res.send({ message: 'Mascota eliminada exitosamente.' });
      }
  });
};