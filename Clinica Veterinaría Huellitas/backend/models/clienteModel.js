const mysql = require('mysql');
const dbConfig = require('../config/database');

// Conectar a la base de datos
const connection = mysql.createConnection(dbConfig);

// Modelo de Cliente
const Cliente = {};

// Obtener todos los clientes
Cliente.getAllClientes = (result) => {
    const query = 'SELECT * FROM Clientes';
    connection.query(query, (error, clients) => {
    connection.end(); // Cerrar la conexión
    if (error) {
        result(error, null);
        return;
    }
    result(null, clients);
    });
};

// Obtener un cliente por su ID
Cliente.getClienteById = (id, result) => {
    const query = 'SELECT * FROM Clientes WHERE ClienteID = ?';
    connection.query(query, [id], (error, client) => {
    connection.end(); // Cerrar la conexión
    if (error) {
        result(error, null);
        return;
    }
    if (client.length) {
        result(null, client[0]);
        return;
    }
    // Cliente no encontrado con el ID
    result({ kind: 'not_found' }, null);
    });
};

// Crear un nuevo cliente
Cliente.createCliente = (newClient, result) => {
    const query = 'INSERT INTO Clientes SET ?';
    connection.query(query, newClient, (error, client) => {
    connection.end(); // Cerrar la conexión
    if (error) {
        result(error, null);
        return;
    }
    result(null, { ClienteID: client.insertId, ...newClient });
    });
};

// Actualizar un cliente por su ID
Cliente.updateClienteById = (id, updatedClient, result) => {
    const query = 'UPDATE Clientes SET ? WHERE ClienteID = ?';
    connection.query(query, [updatedClient, id], (error, client) => {
    connection.end(); // Cerrar la conexión
    if (error) {
        result(error, null);
        return;
    }
    if (client.affectedRows === 0) {
      // Cliente no encontrado con el ID
        result({ kind: 'not_found' }, null);
        return;
    }
    result(null, { ClienteID: id, ...updatedClient });
    });
};

// Eliminar un cliente por su ID
Cliente.deleteClienteById = (id, result) => {
    const query = 'DELETE FROM Clientes WHERE ClienteID = ?';
    connection.query(query, [id], (error, client) => {
    connection.end(); // Cerrar la conexión
    if (error) {
        result(error, null);
        return;
    }
    if (client.affectedRows === 0) {
      // Cliente no encontrado con el ID
        result({ kind: 'not_found' }, null);
        return;
    }
    result(null, client);
    });
};

module.exports = Cliente;