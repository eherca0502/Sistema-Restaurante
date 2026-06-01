<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$id = $_POST['id'];

$nombre = $_POST['nombre'];
$categoria_id = $_POST['categoria_id'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$sql = "UPDATE productos SET

categoria_id = :categoria_id,
nombre = :nombre,
descripcion = :descripcion,
precio = :precio,
stock = :stock

WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':categoria_id' => $categoria_id,
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':stock' => $stock,
    ':id' => $id

]);

header("Location: productos.php");
?>