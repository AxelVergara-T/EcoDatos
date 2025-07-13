<?php
function conectarDB() {
    $host = 'db'; // nombre del contenedor en docker-compose
    $dbname = 'ecodatos_bd';
    $user = 'ecodatosadmin';
    $password = 'ecodatos123';

    try {
        $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Error de conexión a PostgreSQL: " . $e->getMessage());
    }
}
