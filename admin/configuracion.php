<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}



$sql = "SELECT * FROM configuracion LIMIT 1";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$config = $stmt->fetch(PDO::FETCH_ASSOC);



if(!$config){

    $config = [

        'id' => '',
        'nombre_restaurante' => '',
        'rfc' => '',
        'telefono' => '',
        'direccion' => '',
        'mensaje_ticket' => '',
        'logo' => ''

    ];
}

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content p-4">

<h2 class="mb-4">

Configuración Restaurante

</h2>

<div class="card shadow border-0">

<div class="card-body">

<form
action="guardar_configuracion.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id"
value="<?php echo $config['id']; ?>">

<div class="mb-3">

<label>

Nombre Restaurante

</label>

<input
type="text"
name="nombre_restaurante"
class="form-control"

value="<?php echo $config['nombre_restaurante']; ?>"

required>

</div>

<div class="mb-3">

<label>

RFC

</label>

<input
type="text"
name="rfc"
class="form-control"

value="<?php echo $config['rfc']; ?>">

</div>

<div class="mb-3">

<label>

Teléfono

</label>

<input
type="text"
name="telefono"
class="form-control"

value="<?php echo $config['telefono']; ?>">

</div>

<div class="mb-3">

<label>

Dirección

</label>

<textarea
name="direccion"
class="form-control"
rows="3"><?php echo $config['direccion']; ?></textarea>

</div>

<div class="mb-3">

<label>

Mensaje Ticket

</label>

<textarea
name="mensaje_ticket"
class="form-control"
rows="3"><?php echo $config['mensaje_ticket']; ?></textarea>

</div>

<div class="mb-3">

<label>

Logo

</label>

<input
type="file"
name="logo"
class="form-control">

</div>

<?php if(!empty($config['logo'])): ?>

<div class="mb-3">

<img
src="../assets/img/<?php echo $config['logo']; ?>"
width="150"
class="img-thumbnail">

</div>

<?php endif; ?>

<button
class="btn btn-success">

Guardar Configuración

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>