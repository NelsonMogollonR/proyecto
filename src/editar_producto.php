<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: index.php");
    exit();
}

include("conexion.php");

// Obtener los datos del producto a editar
$producto_id = isset($_GET['producto_id']) ? intval($_GET['producto_id']) : 0;
$producto = null;
if ($producto_id > 0) {
    $query = "SELECT * FROM inventario WHERE id_producto = $producto_id";
    $result = mysqli_query($conex, $query);
    $producto = mysqli_fetch_assoc($result);
}

// Actualizar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_producto'])) {
    $producto_id = intval($_POST['producto_id']);
    $categoria = trim($_POST['categoria']);
    $nueva_categoria = trim($_POST['nueva_categoria']);
    if ($nueva_categoria !== '') {
        $categoria = $nueva_categoria;
    }
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = trim($_POST['precio']);
    $stock = trim($_POST['stock']);
    $imagen_url = trim($_POST['imagen_url']);

    $query = "UPDATE inventario SET categoria='$categoria', nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', imagen_url='$imagen_url' WHERE id_producto=$producto_id";
    $result = mysqli_query($conex, $query);

    if ($result) {
        echo "<script>alert('Producto actualizado exitosamente.'); window.location.href = 'agregar_producto.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar producto: " . mysqli_error($conex) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
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
                    <li class="nav-item"><a class="nav-link" href="agregar_producto.php">Agregar Producto</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Formulario para editar producto -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Editar Producto</h2>
        <form method="post" action="editar_producto.php">
            <input type="hidden" name="producto_id" value="<?php echo $producto_id; ?>">
            <div class="mb-3">
                <label for="categoria" class="form-label">Categoría</label>
                <select class="form-select" id="categoria" name="categoria" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="muebles" <?php echo isset($producto) && $producto['categoria'] == 'muebles' ? 'selected' : ''; ?>>Muebles</option>
                    <option value="decoracion" <?php echo isset($producto) && $producto['categoria'] == 'decoracion' ? 'selected' : ''; ?>>Decoración</option>
                    <!-- Otras categorías dinámicas desde la base de datos -->
                    <?php
                    $categorias = mysqli_query($conex, "SELECT DISTINCT categoria FROM inventario");
                    while ($row = mysqli_fetch_assoc($categorias)) {
                        $selected = isset($producto) && $producto['categoria'] == $row['categoria'] ? 'selected' : '';
                        echo "<option value=\"{$row['categoria']}\" $selected>{$row['categoria']}</option>";
                    }
                    ?>
                </select>
                <div class="mt-3">
                    <label for="nueva_categoria" class="form-label">O ingrese una nueva categoría</label>
                    <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria" placeholder="Nueva categoría">
                </div>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre del Producto</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($producto) ? $producto['nombre'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo isset($producto) ? $producto['descripcion'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?php echo isset($producto) ? $producto['precio'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo isset($producto) ? $producto['stock'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="imagen_url" class="form-label">URL de la Imagen</label>
                <input type="text" class="form-control" id="imagen_url" name="imagen_url" value="<?php echo isset($producto) ? $producto['imagen_url'] : ''; ?>" required>
            </div>
            <button type="submit" name="actualizar_producto" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
        <a href="#contacto" class="text-white">Contacto</a>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
