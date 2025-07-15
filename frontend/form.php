<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}

require_once 'conexion.php';
$pdo = conectarDB();

// Funciones de cifrado AES para el RUT
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

// Clave secreta para cifrado
$clave_secreta = 'clave_segura_123';

// Modo edición
$modo_edicion = false;
$datos_edicion = [];

if (isset($_GET['editar'])) {
    $modo_edicion = true;
    $id_editar = intval($_GET['editar']);
    $stmt = $pdo->prepare("SELECT * FROM datos WHERE id = :id");
    $stmt->execute(['id' => $id_editar]);
    $datos_edicion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($datos_edicion) {
        $datos_edicion['rut'] = desencriptar_rut($datos_edicion['rut'], $clave_secreta);
    }
}

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
    header('Location: form.php');
    exit;
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $id = intval($_POST['id']);
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $religion_input = $_POST['religion'];

    $rut_ofuscado = encriptar_rut($rut, $clave_secreta);
    $religion_hash = hash('sha256', $religion_input);

    $stmt = $pdo->prepare("UPDATE datos SET rut = :rut, nombre = :nombre, apellido = :apellido, religion = :religion WHERE id = :id");
    $stmt->execute([
        'rut' => $rut_ofuscado,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'religion' => $religion_hash,
        'id' => $id
    ]);
    header('Location: form.php');
    exit;
}

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $stmt = $pdo->prepare("DELETE FROM datos WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: form.php');
    exit;
}

// Obtener los datos
$stmt = $pdo->query("SELECT * FROM datos");
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Datos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2><?= $modo_edicion ? 'Editar' : 'Agregar'; ?> Datos</h2>
<form method="post" action="">
    <?php if ($modo_edicion): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($datos_edicion['id']) ?>">
    <?php endif; ?>
    Rut: <input type="text" name="rut" value="<?= $modo_edicion ? htmlspecialchars($datos_edicion['rut']) : '' ?>" required><br>
    Nombre: <input type="text" name="nombre" value="<?= $modo_edicion ? htmlspecialchars($datos_edicion['nombre']) : '' ?>" required><br>
    Apellido: <input type="text" name="apellido" value="<?= $modo_edicion ? htmlspecialchars($datos_edicion['apellido']) : '' ?>" required><br>
    Religión: <input type="text" name="religion" required><br>
    <button type="submit" name="<?= $modo_edicion ? 'actualizar' : 'insertar' ?>">
        <?= $modo_edicion ? 'Actualizar' : 'Insertar' ?>
    </button>
</form>

<h2>Datos existentes</h2>
<table border="1" cellpadding="5" cellspacing="0">
<tr>
    <th>ID</th>
    <th>RUT</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Religión</th>
    <th>Acciones</th>
</tr>
<?php foreach($datos as $fila): ?>
<tr>
    <td><?= htmlspecialchars($fila['id']) ?></td>
    <td><?= htmlspecialchars(desencriptar_rut($fila['rut'], $clave_secreta)) ?></td>
    <td><?= htmlspecialchars($fila['nombre']) ?></td>
    <td><?= htmlspecialchars($fila['apellido']) ?></td>
    <td><?= htmlspecialchars($fila['religion']) ?></td>
    <td>
        <a href="?editar=<?= $fila['id'] ?>">Editar</a> |
        <a href="?eliminar=<?= $fila['id'] ?>" onclick="return confirm('¿Eliminar este registro?');">Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
