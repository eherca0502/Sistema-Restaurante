<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT * FROM usuarios";
$stmt = $conexion->prepare($sql);
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

        <h2>Usuarios</h2>

        <a href="crear_usuario.php" class="btn btn-primary">
            Nuevo Usuario
        </a>

    </div>

    <div class="table-responsive mt-4">

    <table class="table table-bordered table-hover">

        <thead class="table-dark">

            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

        </thead>

        <tbody>

        <?php foreach($usuarios as $u): ?>

            <tr>

                <td>
                    <?= $u['nombre']; ?>
                </td>

                <td>
                    <?= $u['usuario']; ?>
                </td>

                <td>
                    <span class="badge bg-info">
                        <?= ucfirst($u['rol']); ?>
                    </span>
                </td>

                <td>

                    <?php if(isset($u['activo']) && $u['activo'] == 1): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Activo</span>
                    <?php endif; ?>

                </td>

                <td>

                    <a href="editar_usuario.php?id=<?= $u['id']; ?>"
                       class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <a href="qr_usuario.php?id=<?= $u['id']; ?>"
                       class="btn btn-dark btn-sm"
                       target="_blank">
                        Gafete
                    </a>

                    <a href="eliminar_usuario.php?id=<?= $u['id']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
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