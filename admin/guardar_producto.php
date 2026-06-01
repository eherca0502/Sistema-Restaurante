<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$nombre = $_POST['nombre'];
$categoria_id = $_POST['categoria_id'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$sql = "INSERT INTO productos
(categoria_id, nombre, descripcion, precio, stock)
VALUES
(:categoria_id, :nombre, :descripcion, :precio, :stock)";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':categoria_id' => $categoria_id,
    ':nombre' => $nombre,
    ':descripcion' => $descripcion,
    ':precio' => $precio,
    ':stock' => $stock

]);

header("Location: productos.php");
?>