// El archivo database.js, configura la conexión a tu base de datos MySQL //

const mysql = require('mysql');

const connection = mysql.createConnection({
host: 'localhost',
user: 'root', 
password: '', 
database: 'clinica-veterinaria', 
});

connection.connect((err) => {
if (err) {
    console.error('Error al conectar a la base de datos: ' + err.message);
} else {
    console.log('Conexión a la base de datos exitosa');
}
});

module.exports = connection;