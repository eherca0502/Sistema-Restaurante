<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'admin'){

    header("Location: ../index.php");
    exit();
}



$nombre_restaurante =
$_POST['nombre_restaurante'];

$rfc = $_POST['rfc'];

$telefono = $_POST['telefono'];

$direccion = $_POST['direccion'];

$mensaje_ticket =
$_POST['mensaje_ticket'];



$sqlVerificar =
"SELECT * FROM configuracion LIMIT 1";

$stmtVerificar =
$conexion->prepare($sqlVerificar);

$stmtVerificar->execute();

$config =
$stmtVerificar->fetch(PDO::FETCH_ASSOC);



$logo = '';

if(!empty($_FILES['logo']['name'])){

    $logo = time() . "_" .
    $_FILES['logo']['name'];

    move_uploaded_file(

        $_FILES['logo']['tmp_name'],

        "../assets/img/" . $logo
    );
}



if(!$config){

    $sql = "INSERT INTO configuracion
    (

    nombre_restaurante,
    rfc,
    telefono,
    direccion,
    mensaje_ticket,
    logo

    )

    VALUES
    (

    :nombre_restaurante,
    :rfc,
    :telefono,
    :direccion,
    :mensaje_ticket,
    :logo

    )";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        ':nombre_restaurante' =>
        $nombre_restaurante,

        ':rfc' => $rfc,

        ':telefono' => $telefono,

        ':direccion' => $direccion,

        ':mensaje_ticket' =>
        $mensaje_ticket,

        ':logo' => $logo

    ]);

}else{

    // SI EXISTE -> UPDATE

    if($logo != ''){

        $sql = "UPDATE configuracion SET

        nombre_restaurante =
        :nombre_restaurante,

        rfc = :rfc,

        telefono = :telefono,

        direccion = :direccion,

        mensaje_ticket =
        :mensaje_ticket,

        logo = :logo

        WHERE id = :id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([

            ':nombre_restaurante' =>
            $nombre_restaurante,

            ':rfc' => $rfc,

            ':telefono' => $telefono,

            ':direccion' => $direccion,

            ':mensaje_ticket' =>
            $mensaje_ticket,

            ':logo' => $logo,

            ':id' => $config['id']

        ]);

    }else{

        $sql = "UPDATE configuracion SET

        nombre_restaurante =
        :nombre_restaurante,

        rfc = :rfc,

        telefono = :telefono,

        direccion = :direccion,

        mensaje_ticket =
        :mensaje_ticket

        WHERE id = :id";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([

            ':nombre_restaurante' =>
            $nombre_restaurante,

            ':rfc' => $rfc,

            ':telefono' => $telefono,

            ':direccion' => $direccion,

            ':mensaje_ticket' =>
            $mensaje_ticket,

            ':id' => $config['id']

        ]);
    }
}

header("Location: configuracion.php");
?>