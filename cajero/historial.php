<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";




$fecha = isset($_GET['fecha'])
? $_GET['fecha']
: date('Y-m-d');




$sqlOrdenes = "

SELECT *

FROM ordenes

WHERE DATE(fecha)=:fecha

ORDER BY id DESC

";

$stmtOrdenes = $conexion->prepare($sqlOrdenes);

$stmtOrdenes->execute([

    ':fecha'=>$fecha

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

WHERE DATE(pagos.fecha)=:fecha

ORDER BY pagos.id DESC

";

$stmtPagos = $conexion->prepare($sqlPagos);

$stmtPagos->execute([

    ':fecha'=>$fecha

]);

$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_cajero.php"; ?>