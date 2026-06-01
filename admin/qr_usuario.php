<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

require_once "../lib/phpqrcode/qrlib.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}

if(!isset($_GET['id'])){

    exit("Usuario no encontrado");
}

$id = $_GET['id'];



$sqlConfig = "SELECT * FROM configuracion LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);



$sql = "SELECT *

FROM usuarios

WHERE id = :id";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$usuario){

    exit("Usuario no encontrado");
}




$qrContenido = $usuario['qr_token'];

$rutaQR = "../temp/";

if(!file_exists($rutaQR)){

    mkdir($rutaQR);
}

$archivoQR = $rutaQR . "qr_" . $usuario['id'] . ".png";

QRcode::png(

    $qrContenido,
    $archivoQR,
    QR_ECLEVEL_H,
    8

);

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>

QR Usuario

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{

    background:#f3f4f6;
    font-family:Arial;
}

.card-qr{

    width:420px;
    margin:40px auto;
    background:white;
    border-radius:20px;
    padding:40px;
    text-align:center;
    box-shadow:0 0 30px rgba(0,0,0,.15);
}

.logo{

    max-width:120px;
    margin-bottom:20px;
}

.qr{

    width:250px;
    margin:20px 0;
}

.nombre{

    font-size:28px;
    font-weight:bold;
}

.rol{

    color:#6b7280;
    font-size:18px;
}

.token{

    margin-top:15px;
    color:#9ca3af;
}

.btn-print{

    margin-top:25px;
}

@media print{

    .btn-print{

        display:none;
    }

    body{

        background:white;
    }

    .card-qr{

        box-shadow:none;
        margin-top:0;
    }
}

</style>

</head>

<body>

<div class="card-qr">

<?php if(!empty($config['logo'])): ?>

<img
src="../assets/img/<?php echo $config['logo']; ?>"
class="logo">

<?php endif; ?>

<h2>

<?php echo $config['nombre_restaurante']; ?>

</h2>

<img
src="<?php echo $archivoQR; ?>"
class="qr">

<div class="nombre">

<?php echo $usuario['nombre']; ?>

</div>

<div class="rol">

<?php echo ucfirst($usuario['rol']); ?>

</div>

<div class="token">

<?php echo $usuario['qr_token']; ?>

</div>

<button
onclick="window.print()"
class="btn btn-dark btn-print">

 Imprimir QR

</button>

</div>

</body>

</html>