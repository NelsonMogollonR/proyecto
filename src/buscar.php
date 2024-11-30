<?php
session_start();
include("conexion.php");

// Función para actualizar la cuenta del carrito
function updateCartCount() {
    global $conex;
    if (isset($_SESSION['cliente_id'])) {
        $cliente_id = $_SESSION['cliente_id'];

        // Consulta para obtener la cantidad total de productos en el carrito del cliente
        $query = "SELECT SUM(cantidad) AS total_items FROM carrito WHERE id_cliente = $cliente_id";
        $result = mysqli_query($conex, $query);
        
        // Verifica y devuelve el total de artículos
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total_items'] ? intval($row['total_items']) : 0;
        } else {
            // Maneja el error de la consulta
            echo "Error en la consulta: " . mysqli_error($conex) . "<br>";
        }
    }
    return 0;
}

// Inicializar la variable de cuenta del carrito
$cart_count = updateCartCount();

// Definir la función formatoMoneda
function formatoMoneda($cantidad) {
    return '$' . number_format((float)$cantidad, 2, ',', '.');
}

$nombre = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
$searchPerformed = false;

$query = "SELECT * FROM inventario WHERE 1";

if ($nombre !== '') {
    $searchPerformed = true;
    $query .= " AND nombre LIKE '%$nombre%'";
}

$result = mysqli_query($conex, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img {
            width: 200px;
            height: auto;
            object-fit: cover;
            cursor: pointer;
        }
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
        .product-card {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            position: relative;
        }
        .product-info {
            padding-left: 20px;
            flex: 1;
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link" href="buscar_avanzado.php">Búsqueda Avanzada</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['cliente'])): ?>
                        <li class="nav-item">
                            <span class="nav-link">Bienvenido, <?php echo $_SESSION['cliente']; ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="carrito.php">
                                <i class="bi bi-cart3"></i> Carrito de Compras
                                <span id="cart-count" class="badge bg-secondary"><?php echo $cart_count > 0 ? $cart_count : ''; ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="registro.php">Registrarse</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <h2 class="text-center mb-4">Resultados de Búsqueda</h2>
        
        <!-- Barra de búsqueda centrada -->
        <form class="d-flex mx-auto mb-4" action="buscar.php" method="get" role="search">
            <input class="form-control me-2" type="search" name="nombre" placeholder="Buscar productos..." aria-label="Buscar" style="min-width: 400px;">
            <button class="btn btn-outline-dark" type="submit">Buscar</button>
        </form>

        <?php if ($searchPerformed): ?>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
    <div class="product-card">
        <?php if ($row['en_oferta'] == 'si'): ?>
            <div class="discount-badge">- <?php echo $row['descuento']; ?> %</div>
        <?php endif; ?>
        <img src="<?php echo $row['imagen_url']; ?>" class="product-img" alt="<?php echo $row['nombre']; ?>" data-bs-toggle="modal" data-bs-target="#productModal" data-nombre="<?php echo $row['nombre']; ?>" data-descripcion="<?php echo $row['descripcion']; ?>" data-precio="<?php echo formatoMoneda($row['precio']); ?>" <?php if ($row['en_oferta'] == 'si'): ?>data-precio-descuento="<?php echo formatoMoneda($row['precio_descuento']); ?>"<?php endif; ?> data-stock="<?php echo $row['stock']; ?>" data-cantidad-vendida="<?php echo $row['cantidad_vendida']; ?>" data-producto-id="<?php echo $row['id_producto']; ?>">
        <div class="product-info">
            <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
            <p class="card-text"><?php echo $row['descripcion']; ?></p>
            <p class="card-text">
                <strong>Precio:</strong> <?php echo formatoMoneda($row['precio']); ?>
            </p>
            <?php if ($row['en_oferta'] == 'si'): ?>
                <p class="card-text">
                    <strong>Precio con Descuento:</strong> <?php echo formatoMoneda($row['precio_descuento']); ?>
                </p>
            <?php endif; ?>
            
            <!-- Botón para agregar al carrito -->
            <form method="post" action="agregar_al_carrito.php">
                <input type="hidden" name="producto_id" value="<?php echo $row['id_producto']; ?>">
                <input type="hidden" name="cantidad" value="1">
                <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
            </form>
        </div>
    </div>
<?php endwhile; ?>

                </div>
            <?php else: ?>
                <p class="text-center">No se encontraron productos que coincidan con su búsqueda.</p>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center">Por favor, realiza una búsqueda para ver los resultados.</p>
        <?php endif; ?>

        <!-- Botón de Volver -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-secondary">Volver</a>
        </div>
    </div>

 <!-- Modal para mostrar información del producto -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Información del Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nombre:</strong> <span id="modal-nombre"></span></p>
                <p><strong>Descripción:</strong> <span id="modal-descripcion"></span></p>
                <p><strong>Precio:</strong> <span id="modal-precio"></span></p>
                <p id="modal-precio-descuento-container"><strong>Precio con Descuento:</strong> <span id="modal-precio-descuento"></span></p>
                <p><strong>Cantidad en Stock:</strong> <span id="modal-stock"></span></p>
                <p><strong>Cantidad Vendida:</strong> <span id="modal-cantidad-vendida"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>



    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
        <a href="#contacto" class="text-white">Contacto</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    var productModal = document.getElementById('productModal');
    productModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var nombre = button.getAttribute('data-nombre');
        var descripcion = button.getAttribute('data-descripcion');
        var precio = button.getAttribute('data-precio');
        var precioDescuento = button.getAttribute('data-precio-descuento');
        var stock = button.getAttribute('data-stock');
        var cantidadVendida = button.getAttribute('data-cantidad-vendida');
        var productoId = button.getAttribute('data-producto-id');

        var modalNombre = productModal.querySelector('#modal-nombre');
        var modalDescripcion = productModal.querySelector('#modal-descripcion');
        var modalPrecio = productModal.querySelector('#modal-precio');
        var modalPrecioDescuento = productModal.querySelector('#modal-precio-descuento');
        var modalPrecioDescuentoContainer = productModal.querySelector('#modal-precio-descuento-container');
        var modalStock = productModal.querySelector('#modal-stock');
        var modalCantidadVendida = productModal.querySelector('#modal-cantidad-vendida');

        modalNombre.textContent = nombre;
        modalDescripcion.textContent = descripcion;
        modalPrecio.textContent = precio;
        modalStock.textContent = stock;
        modalCantidadVendida.textContent = cantidadVendida;

        if (precioDescuento) {
            modalPrecioDescuentoContainer.style.display = 'block';
            modalPrecioDescuento.textContent = precioDescuento;
        } else {
            modalPrecioDescuentoContainer.style.display = 'none';
        }
    });
</script>

</body>
</html>
