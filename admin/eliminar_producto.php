<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM productos
WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

header("Location: productos.php");
?>