<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

/* =========================
   DATOS
========================= */

$nombre = $_POST['nombre'];

$usuario = $_POST['usuario'];

$password = password_hash(

    $_POST['password'],
    PASSWORD_BCRYPT

);

$rol = $_POST['rol'];


/* =========================
   QR TOKEN
========================= */

$qr_token = strtoupper($usuario)

. "_"

. rand(1000,9999);


/* =========================
   GUARDAR
========================= */

$sql = "INSERT INTO usuarios

(

nombre,
usuario,
password,
rol,
qr_token

)

VALUES

(

:nombre,
:usuario,
:password,
:rol,
:qr_token

)";

$stmt = $conexion->prepare($sql);

$stmt->execute([

    ':nombre' => $nombre,
    ':usuario' => $usuario,
    ':password' => $password,
    ':rol' => $rol,
    ':qr_token' => $qr_token

]);

header("Location: usuarios.php");

?>