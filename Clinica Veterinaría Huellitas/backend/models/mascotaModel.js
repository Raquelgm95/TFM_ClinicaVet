const mysql = require('mysql');
const dbConfig = require('../config/database');

// Conectar a la base de datos
const connection = mysql.createConnection(dbConfig);

// Modelo de Mascota
const Mascota = {};

// Obtener todas las mascotas
Mascota.getAllMascotas = (result) => {
    const query = 'SELECT * FROM Mascotas';
    connection.query(query, (error, pets) => {
        if (error) {
        result(error, null);
        return;
        }
        result(null, pets);
    });
    };

// Obtener una mascota por su ID
Mascota.getMascotaById = (id, result) => {
    const query = 'SELECT * FROM Mascotas WHERE MascotaID = ?';
    connection.query(query, [id], (error, pet) => {
        if (error) {
        result(error, null);
        return;
        }
        if (pet.length) {
        result(null, pet[0]);
        return;
        }
        // Mascota no encontrada con el ID
        result({ kind: 'not_found' }, null);
    });
    };

// Crear una nueva mascota
Mascota.createMascota = (newPet, result) => {
    const query = 'INSERT INTO Mascotas SET ?';
    connection.query(query, newPet, (error, pet) => {
        if (error) {
        result(error, null);
        return;
        }
        result(null, { MascotaID: pet.insertId, ...newPet });
    });
    };

// Actualizar una mascota por su ID
Mascota.updateMascotaById = (id, updatedPet, result) => {
    const query = 'UPDATE Mascotas SET ? WHERE MascotaID = ?';
    connection.query(query, [updatedPet, id], (error, pet) => {
        if (error) {
        result(error, null);
        return;
        }
        if (pet.affectedRows === 0) {
        // Mascota no encontrada con el ID
        result({ kind: 'not_found' }, null);
        return;
        }
        result(null, { MascotaID: id, ...updatedPet });
    });
    };

// Eliminar una mascota por su ID
Mascota.deleteMascotaById = (id, result) => {
    const query = 'DELETE FROM Mascotas WHERE MascotaID = ?';
    connection.query(query, [id], (error, pet) => {
        if (error) {
        result(error, null);
        return;
        }
        if (pet.affectedRows === 0) {
        // Mascota no encontrada con el ID
        result({ kind: 'not_found' }, null);
        return;
        }
        result(null, pet);
    });
    };

module.exports = Mascota;