<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$id = $_POST['id'];

$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$rol = $_POST['rol'];

$sql = "UPDATE usuarios SET

nombre = :nombre,
usuario = :usuario,
rol = :rol

WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':nombre' => $nombre,
    ':usuario' => $usuario,
    ':rol' => $rol,
    ':id' => $id

]);

header("Location: usuarios.php");
?>