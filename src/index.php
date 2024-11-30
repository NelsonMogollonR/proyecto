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

// Consulta para obtener los productos
$query = "SELECT * FROM inventario";
$result = mysqli_query($conex, $query);
if (!$result) {
    echo "Error en la consulta: " . mysqli_error($conex);
    exit();
}

// Función para obtener imágenes desde un directorio
function getImagesFromDirectory($dir) {
    $images = [];
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && !is_dir($dir . $file)) {
                $images[] = $dir . $file;
            }
        }
    }
    return $images;
}

$images = getImagesFromDirectory("imagenes/galeria/");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muebles Modernos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 </head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Logo y enlaces alineados a la izquierda -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="imagenes/Logo.png" alt="Logo" width="80" height="80" class="d-inline-block align-text-top me-2">
                Muebles Modernos
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <!-- Enlace a la sección de productos -->
                    <li class="nav-item"><a class="nav-link" href="#productos">Productos</a></li>
                    <!-- Enlace a la sección de galería -->
                    <li class="nav-item"><a class="nav-link" href="#galeria">Galería</a></li>
                    <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                    <!-- Enlace para agregar productos, visible solo para administradores -->
                    <li class="nav-item"><a class="nav-link" href="agregar_producto.php">Agregar Producto</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['cliente'])): ?>
                    <!-- Mostrar nombre del cliente si está logueado -->
                    <li class="nav-item">
                        <span class="nav-link">Bienvenido, <?php echo $_SESSION['cliente']; ?></span>
                    </li>
                    <!-- Enlace al carrito de compras, visible solo si hay artículos en el carrito -->
                    <li class="nav-item">
                        <a class="nav-link" href="carrito.php">
                            <i class="bi bi-cart3"></i> Carrito de Compras
                            <!-- Mostrar la cantidad de productos en el carrito -->
                            <span id="cart-count" class="badge bg-secondary"><?php echo $cart_count; ?></span>
                        </a>
                    </li>
                    <!-- Enlace para cerrar sesión -->
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                    <?php else: ?>
                    <!-- Enlaces para login y registro si el cliente no está logueado -->
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="registro.php">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido de la página -->

    <!-- Header -->
    <header class="bg-light text-center py-5" style="background-image: url('imagenes/imagendesala.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <h1>Bienvenido a Muebles Modernos</h1>
        <p>Encuentra los mejores muebles para tu hogar</p>
        <div class="d-flex justify-content-center align-items-center">
            <form class="d-flex" action="buscar.php" method="get" role="search">
                <input class="form-control me-2" type="search" name="nombre" placeholder="Buscar productos..." aria-label="Buscar">
                <button class="btn btn-outline-dark" type="submit">Buscar</button>
            </form>
            <div class="mx-2"></div>
            <a href="buscar_avanzado.php" class="btn btn-primary">Búsqueda Avanzada</a>
        </div>
    </header>

    <!-- Productos -->
    <?php include'productosindex.php';?>

   <!-- Galería -->
<section id="galeria" class="py-5 bg-light" style="background-color: #f0f0f0;">
    <div class="container">
        <h2 class="text-center mb-4">Galería de Productos</h2>
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $active = "active";
                $query = "SELECT * FROM inventario";
                $result = mysqli_query($conex, $query);
                $count = 0; // Para contar los productos
                echo '<div class="carousel-item ' . $active . '"><div class="row">'; // Inicia el primer grupo de productos
                while ($row = mysqli_fetch_assoc($result)):
                    if ($count % 3 == 0 && $count != 0) {
                        // Cierra y abre una nueva fila cada 3 productos
                        echo '</div></div><div class="carousel-item"><div class="row">';
                    }
                    ?>
                    <div class="col-md-4">
                        <a href="producto.php?id=<?php echo $row['id_producto']; ?>" data-bs-toggle="modal" data-bs-target="#productoModal<?php echo $row['id_producto']; ?>">
                            <img src="<?php echo $row['imagen_url']; ?>" class="d-block w-100 gallery-img" alt="Producto">
                        </a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="productoModal<?php echo $row['id_producto']; ?>" tabindex="-1" aria-labelledby="productoModalLabel<?php echo $row['id_producto']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="productoModalLabel<?php echo $row['id_producto']; ?>"><?php echo $row['nombre']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="<?php echo $row['imagen_url']; ?>" class="img-fluid" alt="<?php echo $row['nombre']; ?>">
                                    <p class="mt-2">Descripción: <?php echo $row['descripcion']; ?></p>
                                    <p><strong>Precio: </strong><?php echo formatoMoneda($row['precio']); ?></p>
                                    <?php if ($row['en_oferta'] == 'sí'): ?>
                                    <p><strong>Precio en Oferta: </strong><?php echo formatoMoneda($row['precio_descuento']); ?></p>
                                    <p><strong>Descuento:</strong> <?php echo $row['descuento']; ?>%</p>
                                    <?php endif; ?>
                                    <p><strong>Cantidad Disponible:</strong> <?php echo $row['stock']; ?></p>
                                    <p><strong>Cantidad Vendida:</strong> <?php echo $row['cantidad_vendida']; ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $count++;
                    $active = ""; // Eliminar la clase "active" después del primer grupo
                endwhile;
                echo '</div></div>'; // Cierra la última fila y el último elemento de carousel-item
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <div class="row">
            <!-- Columna Izquierda: Logo -->
            <div class="col-md-4 mb-4 mb-md-0 text-md-left">
                <img src="imagenes/Logo.png" alt="Logo" width="80" height="80" class="d-inline-block align-text-top mb-2">
                <p class="mb-0">Muebles Modernos</p>
            </div>
            <!-- Columna Central: Contacto y Preguntas Frecuentes -->
            <div class="col-md-4 mb-4 mb-md-0">
                <h5>Contáctanos</h5>
                <p>Email: contacto@mueblesmodernos.com</p>
                <p>Tel: +57 123 456 7890</p>
                <h5>Preguntas Frecuentes</h5>
                <a href="#" class="text-white">FAQ</a>
            </div>
            <!-- Columna Derecha: Redes Sociales -->
            <div class="col-md-4 text-md-right">
                <h5>Síguenos</h5>
                <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>

   