<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

if(!isset($_SESSION['carrito'])){

    header("Location: dashboard.php");
    exit();
}



$tipo_orden = $_POST['tipo_orden'];

$metodo_pago = $_POST['metodo_pago'];

$mesa_id = null;

$cliente_nombre = null;
$telefono = null;
$direccion = null;

$dinero_recibido = null;
$cambio = null;



if($tipo_orden == 'domicilio'){

    $cliente_nombre =
    $_POST['cliente_nombre'];

    $telefono =
    $_POST['telefono'];

    $direccion =
    $_POST['direccion'];
}



$total = 0;

foreach($_SESSION['carrito'] as $item){

    $total += $item['subtotal'];
}



if($metodo_pago == 'efectivo'){

    $dinero_recibido =
    $_POST['dinero_recibido'];

    $cambio =
    $dinero_recibido - $total;
}



$folio = "ORD-" . time();



$sql = "INSERT INTO ordenes
(
folio,
usuario_id,
mesa_id,
tipo_orden,
metodo_pago,
dinero_recibido,
cambio,
total,
cliente_nombre,
telefono,
direccion,
estado_cocina
)

VALUES
(
:folio,
:usuario_id,
:mesa_id,
:tipo_orden,
:metodo_pago,
:dinero_recibido,
:cambio,
:total,
:cliente_nombre,
:telefono,
:direccion,
:estado_cocina
)";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':folio' => $folio,
    ':usuario_id' => $_SESSION['id'],
    ':mesa_id' => $mesa_id,
    ':tipo_orden' => $tipo_orden,
    ':metodo_pago' => $metodo_pago,
    ':dinero_recibido' => $dinero_recibido,
    ':cambio' => $cambio,
    ':total' => $total,
    ':cliente_nombre' => $cliente_nombre,
    ':telefono' => $telefono,
    ':direccion' => $direccion,
    ':estado_cocina' => 'pendiente'

]);

$orden_id = $conexion->lastInsertId();


foreach($_SESSION['carrito'] as $item){

    $sqlDetalle = "INSERT INTO detalle_orden
    (
    orden_id,
    producto_id,
    cantidad,
    precio,
    subtotal
    )

    VALUES
    (
    :orden_id,
    :producto_id,
    :cantidad,
    :precio,
    :subtotal
    )";

    $stmtDetalle =
    $conexion->prepare($sqlDetalle);

    $stmtDetalle->execute([

        ':orden_id' => $orden_id,
        ':producto_id' => $item['id'],
        ':cantidad' => $item['cantidad'],
        ':precio' => $item['precio'],
        ':subtotal' => $item['subtotal']

    ]);



    $sqlStock = "UPDATE productos

    SET stock = stock - :cantidad

    WHERE id = :id";

    $stmtStock =
    $conexion->prepare($sqlStock);

    $stmtStock->execute([

        ':cantidad' => $item['cantidad'],
        ':id' => $item['id']

    ]);
}



$_SESSION['carrito'] = [];



header("Location: ticket.php?id=" . $orden_id);
exit();
?>