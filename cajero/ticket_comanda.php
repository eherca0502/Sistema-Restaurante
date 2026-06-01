
<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

if(!isset($_GET['id'])){

    header("Location: comandas.php");
    exit();
}

$comanda_id = $_GET['id'];




$sqlConfig = "SELECT * FROM configuracion LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);




$sql = "

SELECT

c.*,
m.numero_mesa,
u.nombre AS mesero

FROM comandas c

INNER JOIN mesas m
ON c.mesa_id = m.id

INNER JOIN usuarios u
ON c.mesero_id = u.id

WHERE c.id = :id

";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $comanda_id
]);

$comanda = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$comanda){

    header("Location: comandas.php");
    exit();
}




$sqlDetalle = "

SELECT

cd.*,
p.nombre

FROM comanda_detalle cd

INNER JOIN productos p
ON cd.producto_id = p.id

WHERE cd.comanda_id = :id

";

$stmtDetalle = $conexion->prepare($sqlDetalle);

$stmtDetalle->execute([
    ':id' => $comanda_id
]);

$detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);




$sqlPago = "

SELECT *

FROM pagos

WHERE comanda_id = :id

ORDER BY id DESC

LIMIT 1

";

$stmtPago = $conexion->prepare($sqlPago);

$stmtPago->execute([
    ':id' => $comanda_id
]);

$pago = $stmtPago->fetch(PDO::FETCH_ASSOC);




$total = 0;

foreach($detalles as $d){

    $total += (
        $d['cantidad']
        *
        $d['precio']
    );
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>

Ticket Comanda

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

Comanda:
#<?php echo $comanda['id']; ?>

</p>

<p>

Fecha:
<?php echo $comanda['fecha']; ?>

</p>

<p>

Mesa:
<?php echo $comanda['numero_mesa']; ?>

</p>

<p>

Mesero:
<?php echo $comanda['mesero']; ?>

</p>

<hr>

<table>

<?php foreach($detalles as $d): ?>

<tr>

<td>

<?php echo $d['cantidad']; ?> x
<?php echo $d['nombre']; ?>

</td>

<td align="right">

$<?php echo number_format(

$d['cantidad']
*
$d['precio']

,2); ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<hr>

<div class="total">

TOTAL:
$<?php echo number_format($total,2); ?>

</div>

<?php if($pago): ?>

<hr>

<p>

Método Pago:
<?php echo ucfirst($pago['metodo_pago']); ?>

</p>

<?php if($pago['metodo_pago'] == 'efectivo'): ?>

<p>

Recibido:
$<?php echo number_format(
$pago['dinero_recibido'],
2
); ?>

</p>

<p>

Cambio:
$<?php echo number_format(
$pago['cambio'],
2
); ?>

</p>

<?php endif; ?>

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

🖨 Imprimir Ticket

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

