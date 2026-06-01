<?php
session_start();

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

if(isset($_GET['index'])){

    $index = $_GET['index'];

   
    unset($_SESSION['carrito'][$index]);

   
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}


header("Location: dashboard.php"); 
exit();