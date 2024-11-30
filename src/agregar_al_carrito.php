<?php
session_start();
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);
    $cliente_id = isset($_SESSION['cliente_id']) ? intval($_SESSION['cliente_id']) : 0;

    // Asegurarse de que cliente_id esté definido
    if ($cliente_id == 0) {
        die('Error: Cliente no definido.');
    }

    // Obtener el producto de la base de datos
    $query = "SELECT * FROM inventario WHERE id_producto = $producto_id";
    $result = mysqli_query($conex, $query);
    if (!$result) {
        die('Error en la consulta: ' . mysqli_error($conex));
    }
    $producto = mysqli_fetch_assoc($result);

    // Verificar si el producto existe
    if (!$producto) {
        die('Error: Producto no encontrado.');
    }

    // Verificar y aplicar el precio con descuento si aplica
    if ($producto['en_oferta'] == 'si' && $producto['precio_descuento'] != null) {
        $precio_final = $producto['precio_descuento'];
    } else {
        $precio_final = $producto['precio'];
    }

    // Verificar si el producto ya está en el carrito
    $query = "SELECT * FROM carrito WHERE id_cliente = $cliente_id AND id_producto = $producto_id";
    $result = mysqli_query($conex, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad y el precio
        $query = "UPDATE carrito SET cantidad = cantidad + $cantidad, precio = $precio_final WHERE id_cliente = $cliente_id AND id_producto = $producto_id";
    } else {
        // Si el producto no está en el carrito, agregarlo
        $query = "INSERT INTO carrito (id_cliente, id_producto, cantidad, precio) VALUES ($cliente_id, $producto_id, $cantidad, $precio_final)";
    }

    if (!mysqli_query($conex, $query)) {
        die('Error en la inserción o actualización del carrito: ' . mysqli_error($conex));
    }

    // Redirigir al carrito
    header("Location: carrito.php");
    exit();
} else {
    die('Error: Solicitud no válida.');
}
?>
