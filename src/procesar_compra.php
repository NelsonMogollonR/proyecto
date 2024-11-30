<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

// Función para formatear precios como moneda colombiana
function formatoMoneda($cantidad) {
    return '$' . number_format((float)$cantidad, 2, ',', '.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_SESSION['cliente_id'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $codigo_postal = !empty($_POST['codigo_postal']) ? $_POST['codigo_postal'] : 'NULL';
    $metodo_pago = $_POST['metodo_pago'];
    $total_compra = (float)$_POST['total_compra'];  // Asegurarse de que es un valor numérico

    // Guardar la orden en la base de datos
    $query = "INSERT INTO ordenes (id_cliente, nombre, email, telefono, direccion, ciudad, codigo_postal, metodo_pago, total_compra) 
              VALUES ($cliente_id, '$nombre', '$email', '$telefono', '$direccion', '$ciudad', $codigo_postal, '$metodo_pago', $total_compra)";
    if (mysqli_query($conex, $query)) {
        $order_id = mysqli_insert_id($conex);

        foreach ($_SESSION['carrito'] as $item) {
            if (!isset($item['id_producto'])) {
                continue; // Saltar al siguiente artículo en el carrito
            }

            $id_producto = $item['id_producto'];
            $cantidad = $item['cantidad'];
            $precio = ($item['en_oferta'] == 'si' && $item['precio_descuento'] != null) ? $item['precio_descuento'] : $item['precio'];
            $query = "INSERT INTO orden_detalles (id_orden, id_producto, cantidad, precio) 
                      VALUES ($order_id, $id_producto, $cantidad, $precio)";
            if (!mysqli_query($conex, $query)) {
                echo "Error en la inserción de orden_detalles: " . mysqli_error($conex) . "<br>";
                exit();
            }

            // Descontar el stock en la tabla inventario
            $query = "UPDATE inventario SET stock = stock - $cantidad WHERE id_producto = $id_producto";
            if (!mysqli_query($conex, $query)) {
                echo "Error en la actualización del inventario: " . mysqli_error($conex) . "<br>";
                exit();
            }

            // Actualizar la cantidad vendida en la tabla inventario
            $query = "UPDATE inventario SET cantidad_vendida = cantidad_vendida + $cantidad WHERE id_producto = $id_producto";
            if (!mysqli_query($conex, $query)) {
                echo "Error en la actualización de cantidad vendida: " . mysqli_error($conex) . "<br>";
                exit();
            }
        }

        // Limpiar el carrito en la base de datos
        $query = "DELETE FROM carrito WHERE id_cliente = $cliente_id";
        if (!mysqli_query($conex, $query)) {
            echo "Error en la limpieza del carrito: " . mysqli_error($conex) . "<br>";
            exit();
        }

        // Limpiar el carrito en la sesión
        $carrito = $_SESSION['carrito'];
        $_SESSION['carrito'] = array();

        // Mostrar la factura
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Factura de Compra</title>';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '</head>';
        echo '<body>';
        echo '<div class="container mt-5">';
        echo '<div class="row">';
        echo '<div class="col-12">';
        echo '<div class="card">';
        echo '<div class="card-header bg-primary text-white">';
        echo '<h4 class="mb-0">Factura de Compra</h4>';
        echo '</div>';
        echo '<div class="card-body">';
        echo '<p><strong>Número de Orden:</strong> ' . $order_id . '</p>';
        echo '<p><strong>Nombre Completo:</strong> ' . $nombre . '</p>';
        echo '<p><strong>Correo Electrónico:</strong> ' . $email . '</p>';
        echo '<p><strong>Teléfono:</strong> ' . $telefono . '</p>';
        echo '<p><strong>Dirección de Envío:</strong> ' . $direccion . '</p>';
        echo '<p><strong>Ciudad:</strong> ' . $ciudad . '</p>';
        echo '<p><strong>Código Postal:</strong> ' . $codigo_postal . '</p>';
        echo '<p><strong>Método de Pago:</strong> ' . $metodo_pago . '</p>';
        echo '<h5 class="mt-4">Detalles de la Orden</h5>';
        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Producto</th>';
        echo '<th>Cantidad</th>';
        echo '<th>Precio Unitario</th>';
        echo '<th>Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($carrito as $item) {
            $precio = ($item['en_oferta'] == 'si' && $item['precio_descuento'] != null) ? $item['precio_descuento'] : $item['precio'];
            echo '<tr>';
            echo '<td>' . $item['nombre'] . '</td>';
            echo '<td>' . $item['cantidad'] . '</td>';
            echo '<td>' . formatoMoneda($precio) . '</td>';
            echo '<td>' . formatoMoneda($precio * $item['cantidad']) . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '<h4 class="text-end">Total de la Compra: ' . formatoMoneda($total_compra) . '</h4>';
        echo '</div>';
        echo '<div class="card-footer text-end">';
        echo '<a href="index.php" class="btn btn-secondary">Volver al Inicio</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
        echo '</body>';
        echo '</html>';
    } else {
        echo "Error en la inserción de la orden: " . mysqli_error($conex) . "<br>";
    }
} else {
    echo "Error: Solicitud no permitida.<br>";
}
?>
