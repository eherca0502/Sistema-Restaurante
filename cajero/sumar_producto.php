<?php

session_start();

$index = $_GET['index'];

$_SESSION['carrito'][$index]['cantidad']++;

$_SESSION['carrito'][$index]['subtotal'] =

$_SESSION['carrito'][$index]['cantidad']
*
$_SESSION['carrito'][$index]['precio'];

header("Location: dashboard.php");
?>