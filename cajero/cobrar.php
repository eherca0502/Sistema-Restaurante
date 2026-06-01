<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

date_default_timezone_set('America/Mexico_City');

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";

if(!isset($_GET['id'])){

    header("Location: comandas.php");
    exit();
}

$comanda_id = $_GET['id'];



$sqlConfig = "SELECT * FROM configuracion LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);



$sqlComanda = "

SELECT

c.*,
m.numero_mesa

FROM comandas c

INNER JOIN mesas m
ON c.mesa_id = m.id

WHERE c.id = :id

";

$stmtComanda = $conexion->prepare($sqlComanda);

$stmtComanda->execute([
    ':id' => $comanda_id
]);

$comanda = $stmtComanda->fetch(PDO::FETCH_ASSOC);

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

$productos = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);



$total = 0;

foreach($productos as $p){

    $total += (
        $p['cantidad']
        *
        $p['precio']
    );
}



if(isset($_POST['cobrar'])){

    $metodo_pago = $_POST['metodo_pago'];
    $dinero_recibido = $_POST['dinero_recibido'];

    $cambio = 0;

    if($metodo_pago == 'efectivo'){

        $cambio = $dinero_recibido - $total;
    }


    $sqlPago = "INSERT INTO pagos(

    comanda_id,
    metodo_pago,
    total,
    dinero_recibido,
    cambio

    )

    VALUES(

    :comanda_id,
    :metodo_pago,
    :total,
    :dinero_recibido,
    :cambio

    )";

    $stmtPago = $conexion->prepare($sqlPago);

    $stmtPago->execute([

        ':comanda_id' => $comanda_id,
        ':metodo_pago' => $metodo_pago,
        ':total' => $total,
        ':dinero_recibido' => $dinero_recibido,
        ':cambio' => $cambio

    ]);

   

    $sqlCerrar = "UPDATE comandas

    SET estado = 'pagada'

    WHERE id = :id";

    $stmtCerrar = $conexion->prepare($sqlCerrar);

    $stmtCerrar->execute([
        ':id' => $comanda_id
    ]);

  

    $sqlMesa = "UPDATE mesas

    SET estado = 'libre'

    WHERE id = :id";

    $stmtMesa = $conexion->prepare($sqlMesa);

    $stmtMesa->execute([
        ':id' => $comanda['mesa_id']
    ]);

    echo "

<script>

let ventana = window.open(
'ticket_comanda.php?id=".$comanda_id."',
'_blank'
);

if(ventana){

    setTimeout(function(){

        window.location='comandas.php';

    },1000);

}else{

    alert('Permite ventanas emergentes para imprimir el ticket');

}

</script>

";

    exit();
}

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_cajero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Cobrar Mesa <?= $comanda['numero_mesa']; ?>

</h2>

<div class="card shadow border-0">

<div class="card-body">

<table class="table">

<tr>

<th>Producto</th>
<th>Cantidad</th>
<th>Subtotal</th>

</tr>

<?php foreach($productos as $p): ?>

<tr>

<td>

<?= $p['nombre']; ?>

</td>

<td>

<?= $p['cantidad']; ?>

</td>

<td>

$<?= number_format(
$p['cantidad']
*
$p['precio'],
2
); ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<hr>

<h3>

Total:
$<?= number_format($total,2); ?>

</h3>

<form method="POST">

<div class="mb-3">

<label>

Método Pago

</label>

<select
name="metodo_pago"
id="metodo_pago"
class="form-control">

<option value="efectivo">

Efectivo

</option>

<option value="tarjeta">

Tarjeta

</option>

</select>

</div>

<div id="efectivoBox">

<div class="mb-3">

<label>

Dinero recibido

</label>

<input
type="number"
step="0.01"
name="dinero_recibido"
id="dinero_recibido"
class="form-control">

</div>

<div class="mb-3">

<label>

Cambio

</label>

<input
type="text"
id="cambio"
class="form-control"
readonly>

</div>

</div>

<button
name="cobrar"
class="btn btn-success w-100">

Cobrar e Imprimir

</button>

</form>

</div>

</div>

</div>

</div>

</div>

<script>

const metodoPago =
document.getElementById('metodo_pago');

const efectivoBox =
document.getElementById('efectivoBox');

const dineroRecibido =
document.getElementById('dinero_recibido');

const cambio =
document.getElementById('cambio');

const total = <?= $total; ?>;

metodoPago.addEventListener('change', function(){

    efectivoBox.style.display =
    (this.value == 'tarjeta')
    ? 'none'
    : 'block';
});

dineroRecibido.addEventListener('input', function(){

    let recibido =
    parseFloat(this.value) || 0;

    cambio.value =
    '$' + (recibido - total).toFixed(2);
});

</script>

<?php require_once "../includes/footer.php"; ?>