<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}



if(isset($_POST['guardar'])){

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = $_POST['precio'];

   
    $dias = strtolower($_POST['dias']);

    $sql = "INSERT INTO promociones
    (

    nombre,
    descripcion,
    precio,
    dias

    )

    VALUES
    (

    :nombre,
    :descripcion,
    :precio,
    :dias

    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => $precio,
        ':dias' => $dias

    ]);

    header("Location: promociones.php");
    exit();
}



if(isset($_GET['eliminar'])){

    $id = $_GET['eliminar'];

    $sqlDelete = "DELETE FROM promociones
    WHERE id = :id";

    $stmtDelete =
    $conexion->prepare($sqlDelete);

    $stmtDelete->execute([

        ':id' => $id

    ]);

    header("Location: promociones.php");
    exit();
}



if(isset($_GET['estado'])){

    $id = $_GET['id'];
    $estado = $_GET['estado'];

    $sqlEstado = "UPDATE promociones

    SET activo = :activo

    WHERE id = :id";

    $stmtEstado =
    $conexion->prepare($sqlEstado);

    $stmtEstado->execute([

        ':activo' => $estado,
        ':id' => $id

    ]);

    header("Location: promociones.php");
    exit();
}


$sql = "SELECT * FROM promociones
ORDER BY id DESC";

$stmt = $conexion->prepare($sql);

$stmt->execute();

$promos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

 Promociones

</h2>



<div class="card shadow border-0 mb-4">

<div class="card-body">

<form method="POST">

<div class="row">

<div class="col-md-3 mb-3">

<label>

Nombre

</label>

<input
type="text"
name="nombre"
class="form-control"
placeholder="Ej: Martes 2x1"
required>

</div>

<div class="col-md-3 mb-3">

<label>

Descripción

</label>

<input
type="text"
name="descripcion"
class="form-control"
placeholder="Descripción promo">

</div>

<div class="col-md-2 mb-3">

<label>

Precio

</label>

<input
type="number"
step="0.01"
name="precio"
class="form-control"
placeholder="299"
required>

</div>

<div class="col-md-2 mb-3">

<label>

Día

</label>

<select
name="dias"
class="form-control">

<option value="todos">

Todos

</option>

<option value="lunes">

Lunes

</option>

<option value="martes">

Martes

</option>

<option value="miercoles">

Miércoles

</option>

<option value="jueves">

Jueves

</option>

<option value="viernes">

Viernes

</option>

<option value="sabado">

Sábado

</option>

<option value="domingo">

Domingo

</option>

</select>

</div>

<div class="col-md-2 mb-3 d-flex align-items-end">

<button
name="guardar"
class="btn btn-success w-100">

Guardar

</button>

</div>

</div>

</form>

</div>

</div>



<div class="card shadow border-0">

<div class="card-body">

<table class="table table-hover align-middle">

<thead>

<tr>

<th>Nombre</th>
<th>Precio</th>
<th>Día</th>
<th>Estado</th>
<th>Acciones</th>

</tr>

</thead>

<tbody>

<?php if(count($promos) > 0): ?>

<?php foreach($promos as $p): ?>

<tr>

<td>

<?= htmlspecialchars($p['nombre']); ?>

</td>

<td>

$<?= number_format(
$p['precio'],
2
); ?>

</td>

<td>

<?= ucfirst($p['dias']); ?>

</td>

<td>

<?php if($p['activo']): ?>

<span class="badge bg-success">

Activa

</span>

<?php else: ?>

<span class="badge bg-danger">

Inactiva

</span>

<?php endif; ?>

</td>

<td>

<?php if($p['activo']): ?>

<a
href="?estado=0&id=<?= $p['id']; ?>"
class="btn btn-warning btn-sm">

Desactivar

</a>

<?php else: ?>

<a
href="?estado=1&id=<?= $p['id']; ?>"
class="btn btn-success btn-sm">

Activar

</a>

<?php endif; ?>

<a
href="?eliminar=<?= $p['id']; ?>"
class="btn btn-danger btn-sm"

onclick="return confirm(
'¿Eliminar promoción?'
)">

Eliminar

</a>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5">

<div class="alert alert-info mb-0">

No hay promociones registradas.

</div>

</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>