<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'caja'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";



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

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_cajero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Cobrar Comandas

</h2>

<div class="row">

<?php foreach($comandas as $c): ?>

<?php



$sqlTotal = "

SELECT SUM(
cantidad * precio
) total

FROM comanda_detalle

WHERE comanda_id = :id

";

$stmtTotal = $conexion->prepare($sqlTotal);

$stmtTotal->execute([
    ':id' => $c['id']
]);

$total = $stmtTotal->fetch(PDO::FETCH_ASSOC);

?>

<div class="col-md-4 mb-4">

<div class="card shadow border-0 h-100">

<div class="card-body">

<h3>

 Mesa <?= $c['numero_mesa']; ?>

</h3>

<p>

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

<h4>

Total:
$<?= number_format(
$total['total'],
2
); ?>

</h4>

<a
href="cobrar.php?id=<?= $c['id']; ?>"
class="btn btn-success w-100 mt-3">

Cobrar

</a>

</div>

</div>

</div>

<?php endforeach; ?>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>