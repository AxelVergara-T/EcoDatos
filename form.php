<?php
/*session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'conexion.php';
$pdo = conectarDB();

// Funciones de cifrado AES (RUT reversible)
function encriptar_rut($rut, $clave) {
    $iv = openssl_random_pseudo_bytes(16);
    $cifrado = openssl_encrypt($rut, 'aes-128-cbc', $clave, 0, $iv);
    return base64_encode($iv . $cifrado);
}

function desencriptar_rut($rut_cifrado, $clave) {
    $datos = base64_decode($rut_cifrado);
    $iv = substr($datos, 0, 16);
    $cifrado = substr($datos, 16);
    return openssl_decrypt($cifrado, 'aes-128-cbc', $clave, 0, $iv);
}

// Clave para encriptar el RUT (debe ir en variable de entorno en producción)
$clave_secreta = 'clave_segura_123';

// Procesar inserción
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insertar'])) {
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $religion_input = $_POST['religion'];

    $rut_ofuscado = encriptar_rut($rut, $clave_secreta);
    $religion_hash = hash('sha256', $religion_input);

    $stmt = $pdo->prepare("INSERT INTO datos (rut, nombre, apellido, religion) VALUES (:rut, :nombre, :apellido, :religion)");
    $stmt->execute([
        'rut' => $rut_ofuscado,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'religion' => $religion_hash
    ]);
}

// Obtener los datos
$stmt = $pdo->query("SELECT * FROM datos");
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head><title>Gestión de Datos</title></head>
<body>
<h2>Agregar Datos</h2>
<form method="post" action="">
    Rut: <input type="text" name="rut" required><br>
    Nombre: <input type="text" name="nombre" required><br>
    Apellido: <input type="text" name="apellido" required><br>
    Religión: <input type="text" name="religion" required><br>
    <button type="submit" name="insertar">Insertar</button>
</form>

<h2>Datos existentes</h2>
<table border="1" cellpadding="5" cellspacing="0">
<tr><th>ID</th><th>RUT</th><th>Nombre</th><th>Apellido</th><th>Religión (hash)</th></tr>
<?php foreach($datos as $fila): ?>
<tr>
    <td><?= htmlspecialchars($fila['id']) ?></td>
    <td><?= htmlspecialchars(desencriptar_rut($fila['rut'], $clave_secreta)) ?></td>
    <td><?= htmlspecialchars($fila['nombre']) ?></td>
    <td><?= htmlspecialchars($fila['apellido']) ?></td>
    <td><?= htmlspecialchars($fila['religion']) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
