<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}



$stmt = $conexion->prepare("

SELECT IFNULL(SUM(total),0)

FROM ordenes

WHERE DATE(fecha) = CURDATE()

");

$stmt->execute();

$ventasHoy = $stmt->fetchColumn();



$stmt = $conexion->prepare("

SELECT COUNT(*)

FROM ordenes

WHERE DATE(fecha) = CURDATE()

");

$stmt->execute();

$ordenesHoy = $stmt->fetchColumn();



$stmt = $conexion->prepare("

SELECT COUNT(*)

FROM productos

");

$stmt->execute();

$productos = $stmt->fetchColumn();



$stmt = $conexion->prepare("

SELECT COUNT(*)

FROM mesas

");

$stmt->execute();

$mesas = $stmt->fetchColumn();



$stmt = $conexion->prepare("

SELECT COUNT(*)

FROM productos

WHERE stock <= 5

");

$stmt->execute();

$stockBajo = $stmt->fetchColumn();

require_once __DIR__ . "/../includes/header.php";

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once __DIR__ . "/../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h1 class="mb-4">

 Bienvenido
<?= $_SESSION['nombre']; ?>

</h1>

<div class="row g-4">



<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 bg-success text-white">

<div class="card-body">

<h5 class="mb-3">

 Ventas Hoy

</h5>

<h1>

$<?= number_format($ventasHoy,2); ?>

</h1>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 bg-primary text-white">

<div class="card-body">

<h5 class="mb-3">

 Órdenes Hoy

</h5>

<h1>

<?= $ordenesHoy; ?>

</h1>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 bg-dark text-white">

<div class="card-body">

<h5 class="mb-3">

 Productos

</h5>

<h1>

<?= $productos; ?>

</h1>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 bg-warning text-dark">

<div class="card-body">

<h5 class="mb-3">

 Mesas

</h5>

<h1>

<?= $mesas; ?>

</h1>

</div>

</div>

</div>



<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 bg-danger text-white">

<div class="card-body">

<h5 class="mb-3">

 Stock Bajo

</h5>

<h1>

<?= $stockBajo; ?>

</h1>

</div>

</div>

</div>

</div>

<!-- MENSAJE -->

<div class="card border-0 shadow mt-5 rounded-4">

<div class="card-body">

<h4 class="mb-3">

📊 Resumen del Sistema

</h4>

<p class="mb-0">

El sistema está funcionando correctamente.
Aquí puedes monitorear ventas, órdenes,
inventario y operación general del restaurante.

</p>

</div>

</div>

</div>

</div>

</div>

</div>

<script>

document.addEventListener('keydown', function(e){

    if(e.key === 'Enter'){

        e.preventDefault();
    }
});

</script>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>