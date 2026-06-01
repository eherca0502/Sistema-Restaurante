<?php

require_once "../config/database.php";



$sql = "

SELECT

c.*,
m.numero_mesa

FROM comandas c

INNER JOIN mesas m
ON c.mesa_id = m.id

WHERE c.estado != 'pagada'

ORDER BY c.fecha ASC

";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$comandas = $stmt->fetchAll(PDO::FETCH_ASSOC);



if(count($comandas) == 0){

    echo "

    <div class='alert alert-info'>

    No hay pedidos activos.

    </div>

    ";

    exit();
}



foreach($comandas as $c):

?>

<div class="card shadow border-0 mb-4">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center">

<div>

<h4>

 Mesa <?= $c['numero_mesa']; ?>

</h4>

<p class="text-muted mb-0">

<?= date(
'd/m/Y h:i A',
strtotime($c['fecha'])
); ?>

</p>

</div>

<div>

<?php if($c['estado'] == 'abierta'): ?>

<span class="badge bg-warning p-2">

 Pendiente

</span>

<?php endif; ?>

<?php if($c['estado'] == 'preparando'): ?>

<span class="badge bg-primary p-2">

 Preparando

</span>

<?php endif; ?>

<?php if($c['estado'] == 'lista'): ?>

<span class="badge bg-success p-2">

 Lista

</span>

<?php endif; ?>

</div>

</div>

</div>

</div>

<?php endforeach; ?>