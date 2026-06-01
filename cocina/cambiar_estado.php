<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if($_SESSION['rol'] != 'cocina'){

    exit();
}

$id = $_GET['id'];
$estado = $_GET['estado'];
$tipo = $_GET['tipo'];




if($tipo == 'comanda'){

    $sql = "UPDATE comandas

    SET estado = :estado

    WHERE id = :id";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        ':estado' => $estado,
        ':id' => $id

    ]);
}




if($tipo == 'orden'){

    $sql = "UPDATE ordenes

    SET estado_cocina = :estado

    WHERE id = :id";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        ':estado' => $estado,
        ':id' => $id

    ]);
}

echo "ok";
?>
