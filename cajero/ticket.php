
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$id = $_GET['id'];




$sqlConfig = "SELECT * FROM configuracion
LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);




$sql = "SELECT

ordenes.*,
usuarios.nombre AS cajero

FROM ordenes

LEFT JOIN usuarios
ON ordenes.usuario_id = usuarios.id

WHERE ordenes.id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':id' => $id

]);

$orden = $stmt->fetch(PDO::FETCH_ASSOC);



$sqlDetalle = "SELECT

detalle_orden.*,
productos.nombre

FROM detalle_orden

LEFT JOIN productos
ON detalle_orden.producto_id = productos.id

WHERE orden_id = :orden_id";

$stmtDetalle = $conexion->prepare($sqlDetalle);

$stmtDetalle->execute([

    ':orden_id' => $id

]);

$detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>

Ticket

</title>

<style>

body{

    font-family: monospace;
    width: 300px;
    margin: auto;
    padding: 10px;
}

.center{

    text-align:center;
}

hr{

    border: none;
    border-top: 1px dashed black;
    margin: 10px 0;
}

table{

    width:100%;
    border-collapse: collapse;
}

td{

    padding: 3px 0;
    font-size: 14px;
}

.logo{

    max-width: 100px;
    margin-bottom: 10px;
}

.total{

    font-size: 20px;
    font-weight: bold;
}

.btn-print{

    width:100%;
    padding:12px;
    background:black;
    color:white;
    border:none;
    cursor:pointer;
    font-size:16px;
    margin-top:20px;
}

@media print{

    .btn-print{

        display:none;
    }
}

</style>

</head>

<body>

<div class="center">

<?php if(!empty($config['logo'])): ?>

<img
src="../assets/img/<?php echo $config['logo']; ?>"
class="logo">

<?php endif; ?>

<h2>

<?php echo $config['nombre_restaurante']; ?>

</h2>

<p>

RFC:
<?php echo $config['rfc']; ?>

</p>

<p>

<?php echo $config['telefono']; ?>

</p>

<p>

<?php echo $config['direccion']; ?>

</p>

</div>

<hr>

<p>

Folio:
<?php echo $orden['folio']; ?>

</p>

<p>

Fecha:
<?php echo $orden['fecha']; ?>

</p>

<p>

Cajero:
<?php echo $orden['cajero']; ?>

</p>

<p>

Tipo:
<?php echo ucfirst($orden['tipo_orden']); ?>

</p>

<?php if($orden['tipo_orden'] == 'domicilio'): ?>

<hr>

<p>

Cliente:
<?php echo $orden['cliente_nombre']; ?>

</p>

<p>

Teléfono:
<?php echo $orden['telefono']; ?>

</p>

<p>

Dirección:
<?php echo $orden['direccion']; ?>

</p>

<?php endif; ?>

<hr>

<table>

<?php foreach($detalles as $d): ?>

<tr>

<td>

<?php echo $d['cantidad']; ?> x

<?php

if(!empty($d['nombre'])){

    echo $d['nombre'];

}else{

    echo "Promoción";
}

?>

</td>

<td align="right">

$<?php echo number_format(
$d['subtotal'],
2
); ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<hr>

<div class="total">

TOTAL:
$<?php echo number_format(
$orden['total'],
2
); ?>

</div>

<hr>

<p>

Método Pago:
<?php echo ucfirst(
$orden['metodo_pago']
); ?>

</p>

<?php if(
$orden['metodo_pago'] == 'efectivo'
): ?>

<p>

Recibido:
$<?php echo number_format(
$orden['dinero_recibido'],
2
); ?>

</p>

<p>

Cambio:
$<?php echo number_format(
$orden['cambio'],
2
); ?>

</p>

<?php endif; ?>

<hr>

<div class="center">

<p>

<?php echo $config['mensaje_ticket']; ?>

</p>

</div>

<button
onclick="window.print()"
class="btn-print">

 Imprimir Ticket

</button>


<script>

window.onload = function(){

    setTimeout(() => {

        window.print();

    }, 500);
}

window.onafterprint = function(){

    window.close();
}

</script>

</body>

</html>
