<?php

session_start();

require_once "config/database.php";

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios
WHERE usuario = :usuario
AND estado = 1";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':usuario' => $usuario
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user){

    if(password_verify($password, $user['password'])){

        $_SESSION['id'] = $user['id'];
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['rol'] = $user['rol'];

        switch($user['rol']){

            case 'admin':
                header("Location: admin/dashboard.php");
            break;

            case 'caja':
                header("Location: cajero/dashboard.php");
            break;

            case 'cocina':
                header("Location: cocina/dashboard.php");
            break;

            case 'mesero':
                header("Location: mesero/dashboard.php");
            break;
        }

        exit();

    } else {

        $_SESSION['error_login'] = "Contraseña incorrecta";
        header("Location: index.php");
        exit();
    }

} else {

    $_SESSION['error_login'] = "Usuario no encontrado";
    header("Location: index.php");
    exit();
}
?>