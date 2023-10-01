const mysql = require('mysql');
const dbConfig = require('../config/database');

// Conectar a la base de datos
const connection = mysql.createConnection(dbConfig);

// Modelo de Cita
const Cita = {};

// Obtener todas las citas
Cita.getAllCitas = (result) => {
    const connection = mysql.createConnection(dbConfig);
    const query = 'SELECT * FROM Citas';
    connection.query(query, (error, appointments) => {
        if (error) {
        result(error, null);
        return;
        }
        result(null, appointments);
    });
};

// Obtener una cita por su ID
Cita.getCitaById = (id, result) => {
    const connection = mysql.createConnection(dbConfig);
    const query = 'SELECT * FROM Citas WHERE CitaID = ?';
    connection.query(query, [id], (error, appointment) => {
        if (error) {
        result(error, null);
        return;
        }

        if (appointment.length) {
        result(null, appointment[0]);
        return;
        }

        // Cita no encontrada con el ID
        result({ kind: 'not_found' }, null);
    });
};

// Crear una nueva cita
Cita.createCita = (newAppointment, result) => {
    const connection = mysql.createConnection(dbConfig);
    const query = 'INSERT INTO Citas SET ?';
    connection.query(query, newAppointment, (error, appointment) => {
        if (error) {
            result(error, null);
            return;
        }
        result(null, { CitaID: appointment.insertId, ...newAppointment });
    });
};

    module.exports = Cita;