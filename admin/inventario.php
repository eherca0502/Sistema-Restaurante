<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";



$sql = "SELECT productos.*,
categorias.nombre AS categoria

FROM productos

LEFT JOIN categorias
ON productos.categoria_id = categorias.id

ORDER BY stock ASC";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Inventario

</h2>

<div class="card shadow border-0">

<div class="card-body">

<table class="table table-hover">

<thead>

<tr>


<th>Producto</th>
<th>Categoría</th>
<th>Precio</th>
<th>Stock</th>
<th>Estado</th>
<th>Disponible</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php foreach($productos as $p): ?>

<tr>



<td>

<?= htmlspecialchars($p['nombre']); ?>

</td>

<td>

<?= htmlspecialchars($p['categoria']); ?>

</td>

<td>

$<?= number_format($p['precio'],2); ?>

</td>

<td>

<?php if($p['stock'] <= 0): ?>

<span class="badge bg-danger">

Agotado

</span>

<?php elseif($p['stock'] <= 5): ?>

<span class="badge bg-warning text-dark">

<?= $p['stock']; ?>

</span>

<?php else: ?>

<span class="badge bg-success">

<?= $p['stock']; ?>

</span>

<?php endif; ?>

</td>

<td>

<?php if($p['stock'] <= 0): ?>

<span class="badge bg-danger">

Sin stock

</span>

<?php else: ?>

<span class="badge bg-success">

Disponible

</span>

<?php endif; ?>

</td>

<td>

<?php if($p['disponible'] == 1): ?>

<span class="badge bg-success">

Sí

</span>

<?php else: ?>

<span class="badge bg-danger">

No

</span>

<?php endif; ?>

</td>

<td>

<a
href="editar_stock.php?id=<?= $p['id']; ?>"
class="btn btn-primary btn-sm">

<i class="fa fa-pen"></i>

Editar

</a>

</td>

</tr>