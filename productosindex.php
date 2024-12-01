<?php
include("conexion.php");

// Consulta SQL para obtener solo los productos en oferta
$query = "SELECT p.id_producto, p.categoria, p.nombre, p.descripcion, p.precio, p.precio_descuento, p.imagen_url, p.descuento, p.cantidad_vendida, p.stock 
          FROM inventario p 
          WHERE p.en_oferta = 'si'";
$result = mysqli_query($conex, $query);

if (!$result) {
    die('Query Failed: ' . mysqli_error($conex));
}

if (!function_exists('formatoMoneda')) {
    function formatoMoneda($cantidad) {
        return '$' . number_format((float)$cantidad, 2, ',', '.');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Productos en Oferta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            position: relative;
            width: calc(33.333% - 20px);
        }
        @media (max-width: 768px) {
            .card {
                width: calc(50% - 20px);
            }
        }
        @media (max-width: 576px) {
            .card {
                width: 100%;
            }
        }
        .discount-tag {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: red;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Productos en Oferta</h1>
        <div class="gallery">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <div class="discount-tag">
                        <?php echo $row['descuento']; ?>% OFF
                    </div>
                    <img src="<?php echo $row['imagen_url']; ?>" class="card-img-top" alt="<?php echo $row['nombre']; ?>" data-bs-toggle="modal" data-bs-target="#productoModal<?php echo $row['id_producto']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                        <p class="card-text"><?php echo $row['descripcion']; ?></p>
                        <p class="card-text">
                            <strong>Precio Original:</strong> <?php echo formatoMoneda($row['precio']); ?><br>
                            <strong>Precio con Descuento:</strong> <?php echo formatoMoneda($row['precio_descuento']); ?><br>
                        </p>
                        <form method="post" action="agregar_al_carrito.php">
                            <input type="hidden" name="producto_id" value="<?php echo $row['id_producto']; ?>">
                            <input type="hidden" name="cantidad" value="1">
                            <button type="submit" class="btn btn-primary">Agregar al Carrito</button>
                        </form>
                    </div>
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
                                <p>Precio: <?php echo formatoMoneda($row['precio']); ?></p>
                                <?php if ($row['precio_descuento'] > 0): ?>
                                <p>Precio con Descuento: <?php echo formatoMoneda($row['precio_descuento']); ?></p>
                                <p>Descuento: <?php echo $row['descuento']; ?>%</p>
                                <?php endif; ?>
                                <p>Cantidad Disponible: <?php echo $row['stock']; ?></p>
                                <p><strong>Cantidad Vendida:</strong> <?php echo $row['cantidad_vendida']; ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
