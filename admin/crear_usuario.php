<?php

require_once "../includes/auth.php";

if($_SESSION['rol'] != 'admin'){
    header("Location: ../index.php");
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

            <div class="content">

                <h2>Crear Usuario</h2>

                <form action="guardar_usuario.php"
                method="POST">

                    <div class="mb-3">

                        <label>Nombre</label>

                        <input type="text"
                        name="nombre"
                        class="form-control"
                        required>

                    </div>

                    <div class="mb-3">

                        <label>Usuario</label>

                        <input type="text"
                        name="usuario"
                        class="form-control"
                        required>

                    </div>

                    <div class="mb-3">

                        <label>Contraseña</label>

                        <input type="password"
                        name="password"
                        class="form-control"
                        required>

                    </div>

                    <div class="mb-3">

                        <label>Rol</label>

                        <select name="rol"
                        class="form-control">

                            <option value="admin">
                                Administrador
                            </option>

                            <option value="cajero">
                                Cajero
                            </option>

                            <option value="cocina">
                                Cocina
                            </option>
                            <option value="mesero">Mesero</option>

                        </select>

                    </div>

                    <button class="btn btn-success">
                        Guardar
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php require_once "../includes/footer.php"; ?>