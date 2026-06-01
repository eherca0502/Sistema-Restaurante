<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$id = $_GET['id'];

$sql = "SELECT * FROM usuarios
WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content">

<h2>Editar Usuario</h2>

<form action="actualizar_usuario.php"
method="POST">

<input type="hidden"
name="id"
value="<?php echo $usuario['id']; ?>">

<div class="mb-3">

<label>Nombre</label>

<input type="text"
name="nombre"
class="form-control"
value="<?php echo $usuario['nombre']; ?>">

</div>

<div class="mb-3">

<label>Usuario</label>

<input type="text"
name="usuario"
class="form-control"
value="<?php echo $usuario['usuario']; ?>">

</div>

<div class="mb-3">

<label>Rol</label>

<select name="rol"
class="form-control">

<option value="admin"
<?php if($usuario['rol'] == 'admin') echo "selected"; ?>>
Administrador
</option>

<option value="cajero"
<?php if($usuario['rol'] == 'cajero') echo "selected"; ?>>
Cajero
</option>

<option value="cocina"
<?php if($usuario['rol'] == 'cocina') echo "selected"; ?>>
Cocina
</option>

<option value="mesero"
<?php if($usuario['rol'] == 'mesero') echo "selected"; ?>>
Mesero
</option>

</select>

</div>

<button class="btn btn-success">

Actualizar Usuario

</button>

</form>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>