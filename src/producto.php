<?php
session_start();
include("conexion.php");

// Definir la función formatoMoneda
function formatoMoneda($cantidad) {
    return '$' . number_format((float)$cantidad, 2, ',', '.');
}

$producto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$producto = null;
if ($producto_id > 0) {
    $query = "SELECT * FROM inventario WHERE id_producto = $producto_id";
    $result = mysqli_query($conex, $query);
    $producto = mysqli_fetch_assoc($result);
}

// Manejar la adición al carrito
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['producto_id']) && isset($_POST['cantidad'])) {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = intval($_POST['cantidad']);
    $cliente_id = $_SESSION['cliente_id'];

    // Obtener el producto de la base de datos
    $query = "SELECT * FROM inventario WHERE id_producto = $producto_id";
    $result = mysqli_query($conex, $query);
    $producto = mysqli_fetch_assoc($result);

    // Calcular el precio con descuento si aplica
    $precio_final = $producto['precio'];
    if ($producto['en_oferta'] == 'si') {
        $precio_final = $producto['precio'] * (1 - $producto['descuento'] / 100);
    }

    // Verificar si el producto ya está en el carrito
    $query = "SELECT * FROM carrito WHERE id_cliente = $cliente_id AND id_producto = $producto_id";
    $result = mysqli_query($conex, $query);

    if (mysqli_num_rows($result) > 0) {
        // Si el producto ya está en el carrito, actualizar la cantidad y el precio
        $query = "UPDATE carrito SET cantidad = cantidad + $cantidad, precio = $precio_final WHERE id_cliente = $cliente_id AND id_producto = $producto_id";
    } else {
        // Si el producto no está en el carrito, agregarlo
        $query = "INSERT INTO carrito (id_cliente, id_producto, cantidad, precio) VALUES ($cliente_id, $producto_id, $cantidad, $precio_final)";
    }
    mysqli_query($conex, $query);

    // Actualizar la sesión del carrito
    $query = "SELECT c.id_carrito, i.nombre, i.descripcion, c.precio, c.cantidad 
              FROM carrito c 
              JOIN inventario i ON c.id_producto = i.id_producto 
              WHERE c.id_cliente = $cliente_id";
    $result = mysqli_query($conex, $query);

    $_SESSION['carrito'] = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['carrito'][] = $row;
    }

    // Redirigir al carrito o mostrar un mensaje de éxito
    header("Location: carrito.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: red;
            color: white;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
   

    <div class="container my-5">
        <?php if ($producto): ?>
            <div class="row">
                <div class="col-md-6 position-relative">
                    <?php if ($producto['en_oferta'] == 'si'): ?>
                        <div class="discount-badge">- <?php echo $producto['descuento']; ?> %</div>
                    <?php endif; ?>
                    <img src="<?php echo $producto['imagen_url']; ?>" class="img-fluid" alt="<?php echo $producto['nombre']; ?>">
                </div>
                <div class="col-md-6">
                    <h2><?php echo $producto['nombre']; ?></h2>
                    <p><?php echo $producto['descripcion']; ?></p>
                    <p><strong>Precio:</strong> <?php echo formatoMoneda($producto['precio']); ?></p>
                    <?php if ($producto['en_oferta'] == 'si'): ?>
                        <p><strong>Precio con Descuento:</strong> <?php echo formatoMoneda($producto['precio'] * (1 - $producto['descuento'] / 100)); ?></p>
                    <?php endif; ?>
                    <p><strong>Stock:</strong> <?php echo $producto['stock']; ?></p>
                    <form method="post" action="producto.php?id=<?php echo $producto['id_producto']; ?>">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id_producto']; ?>">
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                    </form>
                    <a href="javascript:history.back()" class="btn btn-secondary mt-3">Volver</a>
                </div>
            </div>




            
        <?php else: ?>
            <p class="text-center">Producto no encontrado.</p>
        <?php endif; ?>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
        <a class="nav-link" href="#contacto">Contacto</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
