<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$id = $_GET['id'];

$sql = "DELETE FROM usuarios
WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

header("Location: usuarios.php");
?>