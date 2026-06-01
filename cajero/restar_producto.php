<?php

session_start();

$index = $_GET['index'];

$_SESSION['carrito'][$index]['cantidad']--;

if($_SESSION['carrito'][$index]['cantidad'] <= 0){

    unset($_SESSION['carrito'][$index]);

    $_SESSION['carrito'] = array_values(
        $_SESSION['carrito']
    );

}else{

    $_SESSION['carrito'][$index]['subtotal'] =

    $_SESSION['carrito'][$index]['cantidad']
    *
    $_SESSION['carrito'][$index]['precio'];
}

header("Location: dashboard.php");
?>