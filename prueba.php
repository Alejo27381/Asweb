<?php
header('Content-Type: application/json');


// Conexión a la base de datos

function conectar(){
$mysqli = new mysqli("localhost:3310", "root", "", "myweb");

if ($mysqli->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Conexión a la base de datos fallida']));
}

}
// Actualizar el stock en la base de datos

