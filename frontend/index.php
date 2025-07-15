<?php
session_start();
require_once 'conexion.php';

$pdo = conectarDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $clave = $_POST['clave'];

    $stmt = $pdo->prepare("SELECT id, clave FROM usuarios WHERE correo = :correo");
    $stmt->execute(['correo' => $correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    //echo '<pre>';
    //var_dump($usuario);
    //var_dump($clave);

    //echo '</pre>';
   
    if ($usuario && password_verify($clave, $usuario['clave'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        header('Location: form.php');
        exit;
    } else {
        $error = 'Correo o clave incorrectos.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - EcoDatos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Iniciar Sesión</h2>
<?php if (isset($error)) echo "<p style='color:red;'>" . htmlspecialchars($error) . "</p>"; ?>
<form method="post" action="">
    Correo: <input type="email" name="correo" required><br>
    Clave: <input type="password" name="clave" required><br>
    <button type="submit">Ingresar</button>
</form>
</body>
</html>
