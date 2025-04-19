const mysql = require('mysql');

const connection = mysql.createConnection({
    host: 'localhost:3310',
    user: 'root',        
    password: '', 
    database: 'myweb'
});

connection.connect((err) => {
    if (err) {
        console.error('Error conectando a la base de datos: ', err);
        return;
    }
    console.log('Conectado a la base de datos MySQL');
});

module.exports = connection;

