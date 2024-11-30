<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$cliente_id = $_SESSION['cliente_id'];

// Consulta para obtener los productos en el carrito del cliente
$query = "SELECT c.id_carrito, c.id_producto, i.nombre, i.descripcion, i.precio, i.precio_descuento, i.en_oferta, c.cantidad, i.imagen_url 
          FROM carrito c 
          JOIN inventario i ON c.id_producto = i.id_producto 
          WHERE c.id_cliente = $cliente_id";
$result = mysqli_query($conex, $query);

// Inicializar la sesión del carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Actualizar la sesión del carrito con los datos de la consulta
$_SESSION['carrito'] = array();
while ($row = mysqli_fetch_assoc($result)) {
    if (isset($row['id_producto'])) {
        $_SESSION['carrito'][] = $row;
    } else {
        echo "Advertencia: Un artículo en el carrito no tiene id_producto definido y será omitido.<br>";
    }
}

// Contador de artículos en el carrito y total de la compra
$cart_count = 0;
$total_compra = 0.0;
foreach ($_SESSION['carrito'] as $item) {
    $cart_count += $item['cantidad'];
    $precio = ($item['en_oferta'] == 'si' && $item['precio_descuento'] != null) ? $item['precio_descuento'] : $item['precio'];
    $total_compra += $precio * $item['cantidad'];
}

// Función para formatear precios como moneda colombiana
function formatoMoneda($cantidad) {
    return '$' . number_format($cantidad, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Muebles Modernos</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php#productos">Productos</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php#galeria">Galería</a></li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['cliente'])): ?>
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo $_SESSION['cliente']; ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="registro.php">Registrarse</a></li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="carrito.php">
                        <i class="bi bi-cart3"></i> Carrito de Compras
                        <span id="cart-count" class="badge bg-secondary"><?php echo $cart_count; ?></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido del Carrito -->
<div class="container my-5">
    <h2 class="text-center mb-4">Carrito de Compras de: <?php echo $_SESSION['cliente']; ?></h2>
    <?php if ($cart_count > 0): ?>
        <form method="post" action="actualizar_carrito.php">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Imagen</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['carrito'] as $item): ?>
                        <?php
                        $precio = ($item['en_oferta'] == 'si' && $item['precio_descuento'] != null) ? $item['precio_descuento'] : $item['precio'];
                        ?>
                        <tr>
                            <td><?php echo $item['nombre']; ?></td>
                            <td><img src="<?php echo $item['imagen_url']; ?>" alt="<?php echo $item['nombre']; ?>" width="100"></td>
                            <td><?php echo $item['descripcion']; ?></td>
                            <td>
                                <?php
                                if ($item['en_oferta'] == 'si' && $item['precio_descuento'] != null) {
                                    echo '<s>' . formatoMoneda($item['precio']) . '</s> <strong>' . formatoMoneda($item['precio_descuento']) . '</strong>';
                                } else {
                                    echo formatoMoneda($item['precio']);
                                }
                                ?>
                            </td>
                            <td>
                                <input type="number" name="cantidad[<?php echo $item['id_carrito']; ?>]" value="<?php echo $item['cantidad']; ?>" min="1">
                            </td>
                            <td><?php echo formatoMoneda($precio * $item['cantidad']); ?></td>
                            <td>
                                <button type="submit" name="actualizar" value="<?php echo $item['id_carrito']; ?>" class="btn btn-primary btn-sm">Actualizar</button>
                                <button type="submit" name="eliminar" value="<?php echo $item['id_carrito']; ?>" class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
        <form method="post" action="finalizar_compra.php">
            <div class="d-flex justify-content-between">
                <a href="index.php" class="btn btn-secondary">Seguir Comprando</a>
                <button type="submit" class="btn btn-success">Finalizar Compra</button>
            </div>
            <div class="text-end mt-4">
                <h4>Total de la Compra: <?php echo formatoMoneda($total_compra); ?></h4>
            </div>
            <input type="hidden" name="total_compra" value="<?php echo $total_compra; ?>">
        </form>
    <?php else: ?>
        <p class="text-center">Tu carrito de compras está vacío.</p>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
    <a href="#contacto" class="text-white">Contacto</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
