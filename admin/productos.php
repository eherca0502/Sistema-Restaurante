<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}


$sql = "SELECT productos.*, categorias.nombre AS categoria
FROM productos
INNER JOIN categorias
ON productos.categoria_id = categorias.id";

$stmt = $conexion->prepare($sql);
$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
?>

<div class="container-fluid">

<div class="row">


<div class="col-md-2 p-0">
    <?php require_once "../includes/sidebar_admin.php"; ?>
</div>


<div class="col-md-10">

<div class="content">

    <div class="d-flex justify-content-between align-items-center">

        <h2>Productos</h2>

        <a href="crear_producto.php" class="btn btn-primary">
            Nuevo Producto
        </a>

    </div>

    <div class="table-responsive mt-4">

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($productos as $p): ?>

            <tr>

                <td>
                    <?= $p['nombre']; ?>
                </td>

                <td>
                    <?= $p['categoria']; ?>
                </td>

                <td>
                    $<?= number_format($p['precio'],2); ?>
                </td>

                <td>
                    <?= $p['stock']; ?>
                </td>

                <td>

                    <?php if($p['disponible'] == 1): ?>
                        <span class="badge bg-success">Disponible</span>
                    <?php else: ?>
                        <span class="badge bg-danger">No disponible</span>
                    <?php endif; ?>

                </td>

                <td>

                    <a href="editar_producto.php?id=<?= $p['id']; ?>"
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <a href="eliminar_producto.php?id=<?= $p['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar este producto?')">
                        Eliminar
                    </a>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

    </div>

</div>

</div>

</div>

</div>

<?php require_once "../includes/footer.php"; ?>