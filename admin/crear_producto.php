<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT * FROM categorias";
$stmt = $conexion->prepare($sql);
$stmt->execute();

$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once "../includes/header.php";
?>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 p-0">

            <?php require_once "../includes/sidebar_admin.php"; ?>

        </div>

        <div class="col-md-10">

            <div class="content">

                <h2>Nuevo Producto</h2>

                <form action="guardar_producto.php"
                method="POST">

                    <div class="mb-3">

                        <label>Nombre</label>

                        <input type="text"
                        name="nombre"
                        class="form-control"
                        required>

                    </div>

                    <div class="mb-3">

                        <label>Categoría</label>

                        <select name="categoria_id"
                        class="form-control">

                            <?php foreach($categorias as $c): ?>

                            <option value="<?php echo $c['id']; ?>">

                                <?php echo $c['nombre']; ?>

                            </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>Descripción</label>

                        <textarea name="descripcion"
                        class="form-control"></textarea>

                    </div>

                    <div class="mb-3">

                        <label>Precio</label>

                        <input type="number"
                        step="0.01"
                        name="precio"
                        class="form-control"
                        required>

                    </div>

                    <div class="mb-3">

                        <label>Stock</label>

                        <input type="number"
                        name="stock"
                        class="form-control"
                        required>

                    </div>

                    <button class="btn btn-success">

                        Guardar Producto

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>