<?php

require_once "config/database.php";

date_default_timezone_set('America/Mexico_City');



$sqlConfig = "SELECT * FROM configuracion LIMIT 1";

$stmtConfig = $conexion->prepare($sqlConfig);

$stmtConfig->execute();

$config = $stmtConfig->fetch(PDO::FETCH_ASSOC);




$ip = $_SERVER['REMOTE_ADDR'];

if(

    strpos($ip, '192.168.') !== 0 &&
    strpos($ip, '10.') !== 0 &&
    $ip != '127.0.0.1' &&
    $ip != '::1'

){

    die("Acceso denegado");
}




$mensaje = "";
$tipo = "";




if(isset($_POST['qr'])){

    $qr = trim($_POST['qr']);


   
    $sql = "SELECT * FROM usuarios
    WHERE qr_token = :qr_token";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ':qr_token' => $qr
    ]);

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);


    if($usuario){

        $fecha = date('Y-m-d');
        $hora = date('H:i:s');


        

        $sqlAsistencia = "SELECT *

        FROM asistencia

        WHERE usuario_id = :usuario_id

        AND fecha = :fecha

        LIMIT 1";

        $stmtAsistencia = $conexion->prepare($sqlAsistencia);

        $stmtAsistencia->execute([

            ':usuario_id' => $usuario['id'],
            ':fecha' => $fecha

        ]);

        $asistencia = $stmtAsistencia->fetch(PDO::FETCH_ASSOC);



        if(!$asistencia){

            $sqlEntrada = "INSERT INTO asistencia(

            usuario_id,
            fecha,
            hora_entrada

            )

            VALUES(

            :usuario_id,
            :fecha,
            :hora_entrada

            )";

            $stmtEntrada = $conexion->prepare($sqlEntrada);

            $stmtEntrada->execute([

                ':usuario_id' => $usuario['id'],
                ':fecha' => $fecha,
                ':hora_entrada' => $hora

            ]);

            $mensaje = "✅ Entrada registrada";
            $tipo = "entrada";
        }

      

        else if(
            empty($asistencia['hora_salida'])
        ){

            $sqlSalida = "UPDATE asistencia

            SET hora_salida = :hora_salida

            WHERE id = :id";

            $stmtSalida = $conexion->prepare($sqlSalida);

            $stmtSalida->execute([

                ':hora_salida' => $hora,
                ':id' => $asistencia['id']

            ]);

            $mensaje = " Salida registrada";
            $tipo = "salida";
        }

        else{

            $mensaje = "⚠ Ya registró asistencia";
            $tipo = "error";
        }

    }else{

        $mensaje = " QR inválido";
        $tipo = "error";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>

Asistencia

</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{

    background:
    linear-gradient(
    135deg,
    #111827,
    #1f2937,
    #374151
    );

    color:white;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial;
}

.card-box{

    width:450px;
    background:#1f2937;
    border-radius:25px;
    padding:45px;
    text-align:center;
    box-shadow:0 0 40px rgba(0,0,0,.5);
}

.logo{

    max-width:180px;
    max-height:120px;
    object-fit:contain;
    margin-bottom:25px;
}

.title{

    font-size:30px;
    font-weight:bold;
    margin-bottom:10px;
}

.subtitle{

    color:#d1d5db;
    margin-bottom:30px;
}

input{

    height:65px;
    text-align:center;
    font-size:24px !important;
    border-radius:15px !important;
    border:none !important;
}

.ok{

    color:#22c55e;
    font-weight:bold;
    margin-top:20px;
}

.error{

    color:#ef4444;
    font-weight:bold;
    margin-top:20px;
}

.usuario{

    font-size:22px;
    font-weight:bold;
    margin-top:10px;
}

.hora{

    color:#d1d5db;
    font-size:18px;
}

</style>

</head>

<body>

<div class="card-box">

<?php if(!empty($config['logo'])): ?>

<img
src="assets/img/<?php echo $config['logo']; ?>"
class="logo">

<?php endif; ?>

<div class="title">

Control de Asistencia

</div>

<div class="subtitle">

Escanea tu código QR

</div>

<form method="POST">

<input
type="text"
name="qr"
class="form-control"
placeholder="ESCANEA AQUÍ"
autocomplete="off"
autofocus>

</form>

<?php if($mensaje): ?>

<h3 class="<?= $tipo == 'error' ? 'error' : 'ok'; ?>">

<?= $mensaje; ?>

</h3>

<?php if(isset($usuario['nombre'])): ?>

<div class="usuario">

<?= $usuario['nombre']; ?>

</div>

<div class="hora">

<?= date('h:i A'); ?>

</div>

<?php endif; ?>

<?php endif; ?>

</div>

</body>

</html>