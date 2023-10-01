const mysql = require('mysql');
const dbConfig = require('../config/database');
const Cliente = require('../models/clienteModel');

const connection = mysql.createConnection(dbConfig);

connection.connect((err) => {
  if (err) {
    console.error('Error al conectar a la base de datos:', err);
  } else {
    console.log('Conexión exitosa a la base de datos');
  }
});

// Obtener todos los clientes
exports.getAllClientes = (req, res) => {
  connection.query('SELECT * FROM Clientes', (error, results) => {
    if (error) {
      console.error('Error al obtener todos los clientes:', error);
      res.status(500).json({ error: 'Error interno del servidor' });
    } else {
      res.json(results);
    }
  });
};

// Agregar un nuevo cliente
exports.createCliente = (req, res) => {
  const { Nombre, Apellidos, Telefono, Correo, Direccion } = req.body;
  connection.query(
    'INSERT INTO Clientes (Nombre, Apellidos, Telefono, Correo, Direccion) VALUES (?, ?, ?, ?, ?)',
    [Nombre, Apellidos, Telefono, Correo, Direccion],
    (error, result) => {
      if (error) {
        console.error('Error al agregar un nuevo cliente:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
      } else {
        res.json({ message: 'Cliente agregado', id: result.insertId });
      }
    }
  );
};

// Obtener un cliente por ID
exports.getClienteById = (req, res) => {
  const { id } = req.params;
  connection.query('SELECT * FROM Clientes WHERE ClienteID = ?', [id], (error, result) => {
    if (error) {
      console.error('Error al obtener un cliente por ID:', error);
      res.status(500).json({ error: 'Error interno del servidor' });
    } else if (result.length === 0) {
      res.status(404).json({ message: 'Cliente no encontrado' });
    } else {
      res.json(result[0]);
    }
  });
};

// Actualizar un cliente por ID
exports.updateCliente = (req, res) => {
  const { id } = req.params;
  const { Nombre, Apellidos, Telefono, Correo, Direccion } = req.body;
  connection.query(
    'UPDATE Clientes SET Nombre = ?, Apellidos = ?, Telefono = ?, Correo = ?, Direccion = ? WHERE ClienteID = ?',
    [Nombre, Apellidos, Telefono, Correo, Direccion, id],
    (error) => {
      if (error) {
        console.error('Error al actualizar un cliente:', error);
        res.status(500).json({ error: 'Error interno del servidor' });
      } else {
        res.json({ message: 'Cliente actualizado', id });
      }
    }
  );
};

// Eliminar un cliente por ID
exports.deleteCliente = (req, res) => {
  const { id } = req.params;
  connection.query('DELETE FROM Clientes WHERE ClienteID = ?', [id], (error, result) => {
    if (error) {
      console.error('Error al eliminar un cliente:', error);
      res.status(500).json({ error: 'Error interno del servidor' });
    } else if (result.affectedRows === 0) {
      res.status(404).json({ message: 'Cliente no encontrado' });
    } else {
      res.json({ message: 'Cliente eliminado', id });
    }
  });
};