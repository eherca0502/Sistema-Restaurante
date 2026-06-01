<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";

date_default_timezone_set('America/Mexico_City');

$fecha = isset($_GET['fecha'])
? $_GET['fecha']
: date('Y-m-d');



$sqlOrdenes = "

SELECT *

FROM ordenes

WHERE DATE(fecha) = :fecha

ORDER BY id DESC

";

$stmtOrdenes = $conexion->prepare($sqlOrdenes);

$stmtOrdenes->execute([

    ':fecha' => $fecha

]);

$ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);



$sqlPagos = "

SELECT
pagos.*,
mesas.numero_mesa

FROM pagos

LEFT JOIN comandas
ON pagos.comanda_id = comandas.id

LEFT JOIN mesas
ON comandas.mesa_id = mesas.id

WHERE DATE(pagos.fecha) = :fecha

ORDER BY pagos.id DESC

";

$stmtPagos = $conexion->prepare($sqlPagos);

$stmtPagos->execute([

    ':fecha' => $fecha

]);

$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);



$totalVentas = 0;
$totalEfectivo = 0;
$totalTarjeta = 0;

$totalOrdenes = count($ordenes);
$totalComandas = count($pagos);



$gananciaEstimada = 0;

foreach($ordenes as $o){

    $totalVentas += $o['total'];

    $gananciaEstimada += (
        $o['total'] * 0.35
    );

    if($o['metodo_pago'] == 'efectivo'){

        $totalEfectivo += $o['total'];
    }

    if($o['metodo_pago'] == 'tarjeta'){

        $totalTarjeta += $o['total'];
    }
}

foreach($pagos as $p){

    $totalVentas += $p['total'];

    $gananciaEstimada += (
        $p['total'] * 0.35
    );

    if($p['metodo_pago'] == 'efectivo'){

        $totalEfectivo += $p['total'];
    }

    if($p['metodo_pago'] == 'tarjeta'){

        $totalTarjeta += $p['total'];
    }
}

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Corte de Caja

</h2>

<form method="GET" class="mb-4">

<div class="row">

<div class="col-md-4">

<input
type="date"
name="fecha"
value="<?= $fecha; ?>"
class="form-control">

</div>

<div class="col-md-2">

<button class="btn btn-primary w-100">

Filtrar

</button>

</div>

<div class="col-md-2">

<a
href="corte_caja_pdf.php?fecha=<?= $fecha; ?>"
class="btn btn-danger w-100">

 Generar PDF

</a>

</div>

</div>

</form>



<div class="row mb-4">



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

 Ventas Totales

</h5>

<h2 class="text-success">

$<?= number_format($totalVentas, 2); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

Efectivo

</h5>

<h2 class="text-primary">

$<?= number_format($totalEfectivo, 2); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

 Tarjeta

</h5>

<h2 class="text-danger">

$<?= number_format($totalTarjeta, 2); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

 Ganancia Estimada

</h5>

<h2 class="text-warning">

$<?= number_format($gananciaEstimada, 2); ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

 Órdenes

</h5>

<h2>

<?= $totalOrdenes; ?>

</h2>

</div>

</div>

</div>



<div class="col-md-3 mb-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h5>

Comandas

</h5>

<h2>

<?= $totalComandas; ?>

</h2>

</div>

</div>

</div>

</div>



<div class="card shadow border-0 mb-4">

<div class="card-body">

<h4 class="mb-4">

 Llevar / Domicilio

</h4>

<table class="table table-hover">

<tr>

<th>Folio</th>
<th>Tipo</th>
<th>Total</th>
<th>Pago</th>
<th>Fecha</th>

</tr>

<?php foreach($ordenes as $o): ?>

<tr>

<td>

<?= $o['folio']; ?>

</td>

<td>

<?= ucfirst($o['tipo_orden']); ?>

</td>

<td>

$<?= number_format($o['total'], 2); ?>

</td>

<td>

<?= ucfirst($o['metodo_pago']); ?>

</td>

<td>

<?= $o['fecha']; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>


<div class="card shadow border-0">

<div class="card-body">

<h4 class="mb-4">

Comandas Cobradas

</h4>

<table class="table table-hover">

<tr>

<th>Mesa</th>
<th>Total</th>
<th>Pago</th>
<th>Fecha</th>

</tr>

<?php foreach($pagos as $p): ?>

<tr>

<td>

Mesa <?= $p['numero_mesa']; ?>

</td>

<td>

$<?= number_format($p['total'], 2); ?>

</td>

<td>

<?= ucfirst($p['metodo_pago']); ?>

</td>

<td>

<?= $p['fecha']; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>

</div>

</div>
