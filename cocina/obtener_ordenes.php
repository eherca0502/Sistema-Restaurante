<?php

require_once "../config/database.php";



$sqlComandas = "

SELECT *

FROM comandas

WHERE estado != 'pagada'

AND estado != 'entregada'

ORDER BY fecha ASC

";

$stmtComandas = $conexion->prepare($sqlComandas);

$stmtComandas->execute();

$comandas = $stmtComandas->fetchAll(PDO::FETCH_ASSOC);


$sqlOrdenes = "

SELECT *

FROM ordenes

WHERE estado_cocina != 'entregada'

ORDER BY fecha ASC

";

$stmtOrdenes = $conexion->prepare($sqlOrdenes);

$stmtOrdenes->execute();

$ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);




if(
count($comandas) == 0
&&
count($ordenes) == 0
){

    echo "

    <div class='alert alert-info'>

    No hay órdenes pendientes.

    </div>

    ";

    exit();
}

?>





<?php foreach($comandas as $c): ?>

<div class="card shadow border-0 mb-4">

<div class="card-body">

<h3 class="mb-3">

🍽️ Mesa

<?php

$sqlMesa = "SELECT * FROM mesas
WHERE id = :id";

$stmtMesa = $conexion->prepare($sqlMesa);

$stmtMesa->execute([

    ':id' => $c['mesa_id']

]);

$mesa = $stmtMesa->fetch(PDO::FETCH_ASSOC);

echo $mesa['numero_mesa'];

?>

</h3>

<p>

Estado:

<?php if($c['estado'] == 'abierta'): ?>

<span class="badge bg-warning">

Pendiente

</span>

<?php endif; ?>

<?php if($c['estado'] == 'preparando'): ?>

<span class="badge bg-primary">

Preparando

</span>

<?php endif; ?>

<?php if($c['estado'] == 'lista'): ?>

<span class="badge bg-success">

Lista

</span>

<?php endif; ?>

</p>

<hr>

<?php

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

    ':id' => $c['id']

]);

$productos = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

?>

<ul class="list-group">

<?php foreach($productos as $p): ?>

<li class="list-group-item d-flex justify-content-between">

<div>

<?= $p['cantidad']; ?> x
<?= $p['nombre']; ?>

</div>

</li>

<?php endforeach; ?>

</ul>

<div class="mt-4 d-flex gap-2">

<?php if($c['estado'] == 'abierta'): ?>

<button
onclick="cambiarEstado(
'comanda',
<?= $c['id']; ?>,
'preparando'
)"
class="btn btn-primary">

Preparando

</button>

<?php endif; ?>

<?php if($c['estado'] == 'preparando'): ?>

<button
onclick="cambiarEstado(
'comanda',
<?= $c['id']; ?>,
'lista'
)"
class="btn btn-success">

Lista

</button>

<?php endif; ?>

<?php if($c['estado'] == 'lista'): ?>

<button
onclick="cambiarEstado(
'comanda',
<?= $c['id']; ?>,
'entregada'
)"
class="btn btn-dark">

Entregada

</button>

<?php endif; ?>

</div>

</div>

</div>

<?php endforeach; ?>





<?php foreach($ordenes as $o): ?>

<div class="card shadow border-0 mb-4 border-danger">

<div class="card-body">

<h3 class="mb-3">

<?php if($o['tipo_orden'] == 'llevar'): ?>

 Para Llevar

<?php endif; ?>

<?php if($o['tipo_orden'] == 'domicilio'): ?>

 Domicilio

<?php endif; ?>

</h3>

<p>

Estado:

<?php if($o['estado_cocina'] == 'pendiente'): ?>

<span class="badge bg-warning">

Pendiente

</span>

<?php endif; ?>

<?php if($o['estado_cocina'] == 'preparando'): ?>

<span class="badge bg-primary">

Preparando

</span>

<?php endif; ?>

<?php if($o['estado_cocina'] == 'lista'): ?>

<span class="badge bg-success">

Lista

</span>

<?php endif; ?>

</p>

<?php if($o['tipo_orden'] == 'domicilio'): ?>

<hr>

<p>

Cliente:
<?= $o['cliente_nombre']; ?>

</p>

<p>

Teléfono:
<?= $o['telefono']; ?>

</p>

<p>

Dirección:
<?= $o['direccion']; ?>

</p>

<?php endif; ?>

<hr>

<?php

$sqlDetalleOrden = "

SELECT

d.*,
p.nombre

FROM detalle_orden d

LEFT JOIN productos p
ON d.producto_id = p.id

WHERE d.orden_id = :id

";

$stmtDetalleOrden = $conexion->prepare($sqlDetalleOrden);

$stmtDetalleOrden->execute([

    ':id' => $o['id']

]);

$productosOrden = $stmtDetalleOrden->fetchAll(PDO::FETCH_ASSOC);

?>

<ul class="list-group">

<?php foreach($productosOrden as $p): ?>

<li class="list-group-item d-flex justify-content-between">

<div>

<?= $p['cantidad']; ?> x
<?= $p['nombre']; ?>

</div>

</li>

<?php endforeach; ?>

</ul>

<div class="mt-4 d-flex gap-2">

<?php if($o['estado_cocina'] == 'pendiente'): ?>

<button
onclick="cambiarEstado(
'orden',
<?= $o['id']; ?>,
'preparando'
)"
class="btn btn-primary">

Preparando

</button>

<?php endif; ?>

<?php if($o['estado_cocina'] == 'preparando'): ?>

<button
onclick="cambiarEstado(
'orden',
<?= $o['id']; ?>,
'lista'
)"
class="btn btn-success">

Lista

</button>

<?php endif; ?>

<?php if($o['estado_cocina'] == 'lista'): ?>

<button
onclick="cambiarEstado(
'orden',
<?= $o['id']; ?>,
'entregada'
)"
class="btn btn-dark">

Entregada

</button>

<?php endif; ?>

</div>

</div>

</div>

<?php endforeach; ?>



