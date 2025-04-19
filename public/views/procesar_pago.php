<?php

$servername = "localhost:3310";
$username = "root";
$password = "";
$dbname = "myweb";

// Crear conexión
$conexion = new mysqli($servername, $username, $password, $dbname);

if($conexion->connect_error){
    die ("Error en la conexion: ". $conexion->connect_error);
}

   // Procesar los datos del formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    // Query SQL para insertar los datos en la tabla
    $sql = "INSERT INTO nombre_tabla (direccion, ciudad, estado, codigo_postal, pais, telefono)
            VALUES ('$address', '$city', '$state', '$zipcode', '$country', '$phone')";

    if ($conn->query($sql) === TRUE) {
        echo "Datos guardados correctamente.";
    } else {
        echo "Error al guardar los datos: " . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>

?>