<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'mesero'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";

if(!isset($_GET['mesa'])){

    header("Location: dashboard.php");
    exit();
}

$mesa_id = $_GET['mesa'];



$sqlMesa = "SELECT * FROM mesas
WHERE id = :id";

$stmtMesa = $conexion->prepare($sqlMesa);

$stmtMesa->execute([
    ':id' => $mesa_id
]);

$mesa = $stmtMesa->fetch(PDO::FETCH_ASSOC);

if(!$mesa){

    header("Location: dashboard.php");
    exit();
}



$sqlComanda = "SELECT * FROM comandas

WHERE mesa_id = :mesa_id

AND estado != 'pagada'

LIMIT 1";

$stmtComanda = $conexion->prepare($sqlComanda);

$stmtComanda->execute([
    ':mesa_id' => $mesa_id
]);

$comanda = $stmtComanda->fetch(PDO::FETCH_ASSOC);



if(!$comanda){

    $sqlNueva = "INSERT INTO comandas(

    tipo_orden,
    mesa_id,
    mesero_id

    )

    VALUES(

    'mesa',
    :mesa_id,
    :mesero_id

    )";

    $stmtNueva = $conexion->prepare($sqlNueva);

    $stmtNueva->execute([

        ':mesa_id' => $mesa_id,
        ':mesero_id' => $_SESSION['id']

    ]);

    $comanda_id = $conexion->lastInsertId();

    

    $sqlMesaEstado = "UPDATE mesas

    SET estado = 'ocupada'

    WHERE id = :id";

    $stmtMesaEstado = $conexion->prepare($sqlMesaEstado);

    $stmtMesaEstado->execute([
        ':id' => $mesa_id
    ]);

}else{

    $comanda_id = $comanda['id'];
}



if(isset($_POST['producto_id'])){

    $producto_id = $_POST['producto_id'];

    $sqlProducto = "SELECT * FROM productos
    WHERE id = :id";

    $stmtProducto = $conexion->prepare($sqlProducto);

    $stmtProducto->execute([
        ':id' => $producto_id
    ]);

    $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

    if($producto){

        $sqlDetalle = "INSERT INTO comanda_detalle(

        comanda_id,
        producto_id,
        cantidad,
        precio

        )

        VALUES(

        :comanda_id,
        :producto_id,
        1,
        :precio

        )";

        $stmtDetalle = $conexion->prepare($sqlDetalle);

        $stmtDetalle->execute([

            ':comanda_id' => $comanda_id,
            ':producto_id' => $producto_id,
            ':precio' => $producto['precio']

        ]);

        header("Location: comanda.php?mesa=".$mesa_id);
        exit();
    }
}



if(isset($_POST['promo_id'])){

    $promo_id = $_POST['promo_id'];

    $sqlPromo = "SELECT * FROM promociones
    WHERE id = :id";

    $stmtPromo = $conexion->prepare($sqlPromo);

    $stmtPromo->execute([

        ':id' => $promo_id

    ]);

    $promo = $stmtPromo->fetch(PDO::FETCH_ASSOC);

    if($promo){

        $sqlDetalle = "INSERT INTO comanda_detalle(

        comanda_id,
        producto_id,
        cantidad,
        precio

        )

        VALUES(

        :comanda_id,
        0,
        1,
        :precio

        )";

        $stmtDetalle = $conexion->prepare($sqlDetalle);

        $stmtDetalle->execute([

            ':comanda_id' => $comanda_id,
            ':precio' => $promo['precio']

        ]);

        header("Location: comanda.php?mesa=".$mesa_id);
        exit();
    }
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



$sqlProductos = "SELECT * FROM productos
WHERE disponible = 1

ORDER BY nombre ASC";

$stmtProductos = $conexion->prepare($sqlProductos);

$stmtProductos->execute();

$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);


$sqlDetalle = "

SELECT

cd.*,

CASE
WHEN cd.producto_id = 0
THEN 'Promoción'
ELSE p.nombre
END AS nombre

FROM comanda_detalle cd

LEFT JOIN productos p
ON cd.producto_id = p.id

WHERE cd.comanda_id = :comanda_id

ORDER BY cd.id DESC

";

$stmtDetalle = $conexion->prepare($sqlDetalle);

$stmtDetalle->execute([
    ':comanda_id' => $comanda_id
]);

$detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_mesero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Mesa <?= $mesa['numero_mesa']; ?>

</h2>

<div class="row">



<div class="col-md-8">

<div class="row">

<?php foreach($productos as $p): ?>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 h-100">

<div class="card-body text-center">

<h4>

<?= $p['nombre']; ?>

</h4>

<h5 class="text-success mb-3">

$<?= number_format($p['precio'],2); ?>

</h5>

<form method="POST">

<input
type="hidden"
name="producto_id"
value="<?= $p['id']; ?>">

<button class="btn btn-primary w-100">

Agregar

</button>

</form>

</div>

</div>

</div>

<?php endforeach; ?>



<?php if(count($promociones) > 0): ?>

<div class="col-12">

<h3 class="mt-4 mb-4">

 Promociones del Día

</h3>

</div>

<?php foreach($promociones as $promo): ?>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 h-100 bg-warning">

<div class="card-body text-center">

<h4>

<?= $promo['nombre']; ?>

</h4>

<h5 class="mb-3">

$<?= number_format($promo['precio'],2); ?>

</h5>

<p>

<?= $promo['descripcion']; ?>

</p>

<form method="POST">

<input
type="hidden"
name="promo_id"
value="<?= $promo['id']; ?>">

<button class="btn btn-dark w-100">

Agregar Promo

</button>

</form>

</div>

</div>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</div>



<div class="col-md-4">

<div class="card shadow border-0">

<div class="card-body">

<h4 class="mb-4">

 Comanda

</h4>

<?php if(count($detalle) > 0): ?>

<table class="table">

<tr>

<th>Producto</th>
<th>Precio</th>

</tr>

<?php

$total = 0;

foreach($detalle as $d):

$total += $d['precio'];

?>

<tr>

<td>

<?= $d['nombre']; ?>

</td>

<td>

$<?= number_format($d['precio'],2); ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<hr>

<h4>

Total:
$<?= number_format($total,2); ?>

</h4>

<?php else: ?>

<div class="alert alert-info">

Todavía no hay productos en esta comanda.

</div>

<?php endif; ?>

<a
href="dashboard.php"
class="btn btn-secondary w-100 mt-3">

Volver

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>