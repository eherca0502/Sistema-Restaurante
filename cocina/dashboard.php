<?php

require_once "../includes/auth.php";

if($_SESSION['rol'] != 'cocina'){

    header("Location: ../index.php");
    exit();
}

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_cocina.php"; ?>

</div>

<div class="col-md-10">

<div class="content">

<h1 class="mb-4">

Pantalla Cocina

</h1>

<div id="ordenes">

</div>

</div>

</div>

</div>

</div>



<audio id="sonidoOrden">

<source
src="../assets/audio/nueva orden.mp3"
type="audio/mpeg">

</audio>

<script>

let ultimaCantidad = 0;

function cargarOrdenes(){

    fetch('obtener_ordenes.php')

    .then(response => response.text())

    .then(data => {

        document.getElementById(
            'ordenes'
        ).innerHTML = data;

    

        let totalOrdenes =
        document.querySelectorAll(
            '#ordenes .card'
        ).length;

        

        if(
            ultimaCantidad != 0 &&
            totalOrdenes > ultimaCantidad
        ){

            document.getElementById(
                'sonidoOrden'
            ).play();
        }

        ultimaCantidad = totalOrdenes;
    });
}

cargarOrdenes();

setInterval(cargarOrdenes, 3000);

</script>
<script>


document.addEventListener('keydown', function(e){

    if(e.key === 'Enter'){

        e.preventDefault();
    }
});

</script>
<script>

function cambiarEstado(tipo,id,estado){

    fetch(
    `cambiar_estado.php?tipo=${tipo}&id=${id}&estado=${estado}`
    )

    .then(() => {

        cargarOrdenes();
    });
}

</script>

<?php require_once "../includes/footer.php"; ?>