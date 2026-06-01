<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

if(!isset($_GET['id'])){

    header("Location: inventario.php");
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

if(!$producto){

    header("Location: inventario.php");
    exit();
}



if(isset($_POST['guardar'])){

    $stock = $_POST['stock'];

    $disponible = $_POST['disponible'];

    $sqlUpdate = "UPDATE productos

    SET stock = :stock,
    disponible = :disponible

    WHERE id = :id";

    $stmtUpdate =
    $conexion->prepare($sqlUpdate);

    $stmtUpdate->execute([

        ':stock' => $stock,
        ':disponible' => $disponible,
        ':id' => $id

    ]);

    header("Location: inventario.php");
    exit();
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

📦 Editar Stock

</h2>

<div class="card shadow border-0">

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>

Producto

</label>

<input
type="text"
class="form-control"
value="<?= htmlspecialchars($producto['nombre']); ?>"
readonly>

</div>

<div class="mb-3">

<label>

Stock

</label>

<input
type="number"
name="stock"
class="form-control"
value="<?= $producto['stock']; ?>"
required>

</div>

<div class="mb-3">

<label>

Disponible

</label>

<select
name="disponible"
class="form-control">

<option
value="1"
<?= $producto['disponible'] == 1 ? 'selected' : ''; ?>>

Sí

</option>

<option
value="0"
<?= $producto['disponible'] == 0 ? 'selected' : ''; ?>>

No

</option>

</select>

</div>

<button
name="guardar"
class="btn btn-success">

<i class="fa fa-save"></i>

Guardar Cambios

</button>

<a
href="inventario.php"
class="btn btn-secondary">

Volver

</a>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>