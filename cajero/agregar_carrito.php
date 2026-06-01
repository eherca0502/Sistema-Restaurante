<?php

session_start();

$id = $_POST['id'];

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

$encontrado = false;

foreach($_SESSION['carrito'] as &$item){

    if($item['id'] == $id){

        $item['cantidad']++;
        $item['subtotal'] =
        $item['cantidad'] * $item['precio'];

        $encontrado = true;

        break;
    }
}

if(!$encontrado){

    $_SESSION['carrito'][] = [

        'id' => $_POST['id'],
        'nombre' => $_POST['nombre'],
        'precio' => $_POST['precio'],
        'cantidad' => 1,
        'subtotal' => $_POST['precio']

    ];
}

header("Location: dashboard.php");
?>