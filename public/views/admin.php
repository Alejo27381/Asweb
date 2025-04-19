<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #34495E;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
        }
        .btn {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-crear {
            background-color: #c0392b;
        }
        .btn-editar {
            background-color: #c0392b;
        }
        .btn-eliminar {
            background-color: #f44336;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #c0392b;;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
$servidor="localhost:3310";
$usuario_bd="root";
$contraseña_bd="";
$nombre_bd="myweb";

$conexion= new mysqli($servidor,$usuario_bd,$contraseña_bd,$nombre_bd);

if($conexion->connect_error){
    die ("Error en la conexion: ". $conexion->connect_error);
}

// Procesar formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['crear'])) {
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $categoria_id = $_POST['categoria_id'];
        $codigo = $_POST['codigo'];
        
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, categoria_id, codigo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdiis", $nombre, $descripcion, $precio, $stock, $categoria_id, $codigo);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $categoria_id = $_POST['categoria_id'];
        $codigo = $_POST['codigo'];
        
        $sql = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=?, categoria_id=?, codigo=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdiisi", $nombre, $descripcion, $precio, $stock, $categoria_id, $codigo, $id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        
        $sql = "DELETE FROM productos WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

//consulta sql
$sql="SELECT id,nombre,descripcion,precio,stock,categoria_id,codigo FROM productos";
$resultado=$conexion->query($sql);

echo "<button class='btn btn-crear' onclick='mostrarFormularioCrear()'>Crear Nuevo Producto</button>";

if($resultado->num_rows>0){
    echo "<table>";
    echo "<tr><th>ID</th><th>NOMBRE</th><th>DESCRIPCION</th><th>PRECIO</th><th>STOCK</th><th>CATEGORIA_ID</th><th>CODIGO</th><th>ACCIONES</th></tr>";

    while($fila=$resultado->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $fila["id"] . "</td>";
        echo "<td>" . $fila["nombre"] . "</td>";
        echo "<td>" . $fila["descripcion"] . "</td>";
        echo "<td>" . $fila["precio"] . "</td>";
        echo "<td>" . $fila["stock"] . "</td>";
        echo "<td>" . $fila["categoria_id"] . "</td>";
        echo "<td>" . $fila["codigo"] . "</td>";
        echo "<td>
                <button class='btn btn-editar' onclick='mostrarFormularioEditar(" . json_encode($fila) . ")'>Editar</button>
                <button class='btn btn-eliminar' onclick='confirmarEliminar(" . $fila["id"] . ")'>Eliminar</button>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No se encontraron productos.</p>";
}

$conexion->close();
?>

<!-- Modal para crear/editar producto -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2 id="modalTitle">Crear/Editar Producto</h2>
        <form id="productoForm" method="POST">
            <input type="hidden" id="id" name="id">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br>
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" required><br>
            <label for="precio">Precio:</label>
            <input type="number" id="precio" name="precio" step="0.01" required><br>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required><br>
            <label for="categoria_id">Categoría ID:</label>
            <input type="number" id="categoria_id" name="categoria_id" required><br>
            <label for="codigo">Código:</label>
            <input type="text" id="codigo" name="codigo" required><br>
            <input type="submit" id="submitBtn" class="submit" value="Guardar">
        </form>
    </div>
</div>

<script>
function mostrarFormularioCrear() {
    document.getElementById('modalTitle').innerText = 'Crear Nuevo Producto';
    document.getElementById('productoForm').reset();
    document.getElementById('id').value = '';
    document.getElementById('submitBtn').name = 'crear';
    document.getElementById('modal').style.display = 'block';
}

function mostrarFormularioEditar(producto) {
    document.getElementById('modalTitle').innerText = 'Editar Producto';
    document.getElementById('id').value = producto.id;
    document.getElementById('nombre').value = producto.nombre;
    document.getElementById('descripcion').value = producto.descripcion;
    document.getElementById('precio').value = producto.precio;
    document.getElementById('stock').value = producto.stock;
    document.getElementById('categoria_id').value = producto.categoria_id;
    document.getElementById('codigo').value = producto.codigo;
    document.getElementById('submitBtn').name = 'editar';
    document.getElementById('modal').style.display = 'block';
}

function cerrarModal() {
    document.getElementById('modal').style.display = 'none';
}

function confirmarEliminar(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = '<input type="hidden" name="eliminar" value="1"><input type="hidden" name="id" value="' + id + '">';
        document.body.appendChild(form);
        form.submit();
    }
}

window.onclick = function(event) {
    if (event.target == document.getElementById('modal')) {
        cerrarModal();
    }
}
</script>

</body>
</html>