<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM productos
WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$producto = $stmt->fetch(PDO::FETCH_ASSOC);

$sqlCategorias = "SELECT * FROM categorias";

$stmtCategorias = $conexion->prepare($sqlCategorias);
$stmtCategorias->execute();

$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">

<?php require_once "../includes/sidebar_admin.php"; ?>

</div>

<div class="col-md-10">

<div class="content">

<h2>Editar Producto</h2>

<form action="actualizar_producto.php"
method="POST">

<input type="hidden"
name="id"
value="<?php echo $producto['id']; ?>">

<div class="mb-3">

<label>Nombre</label>

<input type="text"
name="nombre"
class="form-control"
value="<?php echo $producto['nombre']; ?>">

</div>

<div class="mb-3">

<label>Categoría</label>

<select name="categoria_id"
class="form-control">

<?php foreach($categorias as $c): ?>

<option
value="<?php echo $c['id']; ?>"

<?php
if($producto['categoria_id'] == $c['id']){
    echo "selected";
}
?>

>

<?php echo $c['nombre']; ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="mb-3">

<label>Descripción</label>

<textarea
name="descripcion"
class="form-control"><?php echo $producto['descripcion']; ?></textarea>

</div>

<div class="mb-3">

<label>Precio</label>

<input type="number"
step="0.01"
name="precio"
class="form-control"
value="<?php echo $producto['precio']; ?>">

</div>

<div class="mb-3">

<label>Stock</label>

<input type="number"
name="stock"
class="form-control"
value="<?php echo $producto['stock']; ?>">

</div>

<button class="btn btn-success">

Actualizar Producto

</button>

</form>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>