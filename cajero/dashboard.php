<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

if(!isset($_SESSION['carrito'])){

    $_SESSION['carrito'] = [];
}

date_default_timezone_set('America/Mexico_City');



$diasSemana = [

    'Sunday' => 'domingo',
    'Monday' => 'lunes',
    'Tuesday' => 'martes',
    'Wednesday' => 'miercoles',
    'Thursday' => 'jueves',
    'Friday' => 'viernes',
    'Saturday' => 'sabado'

];

$diaActual = $diasSemana[date('l')];

$sqlPromos = "

SELECT *

FROM promociones

WHERE activo = 1

AND (

dias = 'todos'

OR dias = :dia

)

ORDER BY id DESC

";

$stmtPromo = $conexion->prepare($sqlPromos);

$stmtPromo->execute([

    ':dia' => $diaActual

]);

$promociones = $stmtPromo->fetchAll(PDO::FETCH_ASSOC);



$sql = "SELECT * FROM productos
WHERE disponible = 1";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_cajero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Punto de Venta

</h2>

<div class="row">



<div class="col-md-8">

<div class="row">

<?php if(count($productos) > 0): ?>

<?php foreach($productos as $p): ?>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 h-100">

<div class="card-body text-center">

<h4 class="mb-3">

<?= htmlspecialchars($p['nombre']); ?>

</h4>

<h5 class="text-success mb-3">

$<?= number_format($p['precio'],2); ?>

</h5>

<p>

<?= htmlspecialchars($p['descripcion']); ?>

</p>

<form action="agregar_carrito.php" method="POST">

<input
type="hidden"
name="id"
value="<?= $p['id']; ?>">

<input
type="hidden"
name="nombre"
value="<?= $p['nombre']; ?>">

<input
type="hidden"
name="precio"
value="<?= $p['precio']; ?>">

<button
type="submit"
class="btn btn-primary w-100">

Agregar

</button>

</form>

</div>

</div>

</div>

<?php endforeach; ?>

<?php else: ?>

<div class="alert alert-info">

No hay productos registrados.

</div>