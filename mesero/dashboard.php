<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'mesero'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";



$sql = "SELECT * FROM mesas
ORDER BY numero_mesa ASC";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_mesero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Mesas

</h2>

<div class="row">

<?php foreach($mesas as $mesa): ?>

<div class="col-md-3 mb-4">

<a
href="comanda.php?mesa=<?= $mesa['id']; ?>"
style="text-decoration:none;">

<div class="card shadow border-0 mesa-card">

<div class="card-body text-center">

<h2 class="mb-3">

Mesa <?= $mesa['numero_mesa']; ?>

</h2>

<?php if($mesa['estado'] == 'libre'): ?>

<div class="badge bg-success p-2">

🟢 Libre

</div>

<?php endif; ?>

<?php if($mesa['estado'] == 'ocupada'): ?>

<div class="badge bg-danger p-2">

🔴 Ocupada

</div>

<?php endif; ?>

<?php if($mesa['estado'] == 'pagando'): ?>

<div class="badge bg-warning p-2">

🟡 Pagando

</div>

<?php endif; ?>

</div>

</div>

</a>

</div>

<?php endforeach; ?>

</div>

</div>

</div>

</div>

</div>

<style>

.mesa-card{

transition: 0.3s;
border-radius: 20px;

}

.mesa-card:hover{

transform: scale(1.03);

}

</style>

<?php require_once "../includes/footer.php"; ?>