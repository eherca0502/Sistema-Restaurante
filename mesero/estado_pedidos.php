<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'mesero'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_mesero.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

 Estado de Pedidos

</h2>

<div id="pedidos"></div>

</div>

</div>

</div>

</div>

<script>

function cargarPedidos(){

    fetch('obtener_estados.php')

    .then(response => response.text())

    .then(data => {

        document.getElementById(
            'pedidos'
        ).innerHTML = data;
    });
}

cargarPedidos();

setInterval(cargarPedidos, 3000);

</script>

<?php require_once "../includes/footer.php"; ?>