<?php
session_start();
include("conexion.php");

if (isset($_POST['actualizar'])) {
    $id_carrito = intval($_POST['actualizar']);
    $cantidad = intval($_POST['cantidad'][$id_carrito]);

    $query = "UPDATE carrito SET cantidad = $cantidad WHERE id_carrito = $id_carrito";
    mysqli_query($conex, $query);
}

if (isset($_POST['eliminar'])) {
    $id_carrito = intval($_POST['eliminar']);

    $query = "DELETE FROM carrito WHERE id_carrito = $id_carrito";
    mysqli_query($conex, $query);
}

if (isset($_POST['finalizar'])) {
   
    echo "Compra finalizada.";
}

header("Location: carrito.php");
exit();
?>
