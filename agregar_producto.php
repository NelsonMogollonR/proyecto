<?php
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header("Location: index.php");
    exit();
}

include("conexion.php");

// Función para manejar la subida de la imagen y devolver la ruta
function uploadImage($file) {
    $target_dir = "imagenes/galeria/";
    $unique_name = uniqid() . '_' . basename($file["name"]);
    $target_file = $target_dir . $unique_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Comprobar si el archivo es una imagen real
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('El archivo no es una imagen.');</script>";
        $uploadOk = 0;
    }

    // Verificar el tamaño del archivo (límite de 2 MB)
    if ($file["size"] > 2 * 1024 * 1024) {
        echo "<script>alert('Lo sentimos, tu archivo es demasiado grande. Máximo 2 MB.');</script>";
        $uploadOk = 0;
    }

    // Permitir ciertos formatos de archivo
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Lo sentimos, solo se permiten archivos JPG, JPEG, PNG y GIF.');</script>";
        $uploadOk = 0;
    }

    // Verificar si $uploadOk está configurado en 0 por un error
    if ($uploadOk == 0) {
        echo "<script>alert('Lo sentimos, tu archivo no fue subido.');</script>";
        return null;
    } else {
        // Si todo está bien, intentar subir el archivo
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        } else {
            echo "<script>alert('Lo sentimos, hubo un error al subir tu archivo.');</script>";
            return null;
        }
    }
}

// Agregar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_producto'])) {
    $categoria = trim($_POST['categoria']);
    $nueva_categoria = trim($_POST['nueva_categoria']);
    if ($nueva_categoria !== '') {
        $categoria = $nueva_categoria;
    }
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval(str_replace(',', '.', str_replace('.', '', $_POST['precio'])));
    $stock = trim($_POST['stock']);
    $en_oferta = $_POST['en_oferta'];
    $descuento = $en_oferta == 'si' ? intval($_POST['descuento']) : 0;
    $precio_descuento = $en_oferta == 'si' ? $precio * (1 - $descuento / 100) : NULL;
    $imagen_url = uploadImage($_FILES["imagen"]);

    if ($imagen_url) {
        $query = "INSERT INTO inventario (categoria, nombre, descripcion, precio, stock, en_oferta, descuento, precio_descuento, imagen_url) 
                  VALUES ('$categoria', '$nombre', '$descripcion', '$precio', '$stock', '$en_oferta', '$descuento', '$precio_descuento', '$imagen_url')";
        $result = mysqli_query($conex, $query);

        if ($result) {
            echo "<script>alert('Producto agregado exitosamente.'); window.location.href = 'agregar_producto.php';</script>";
        } else {
            echo "<script>alert('Error al agregar producto: " . mysqli_error($conex) . "');</script>";
        }
    }
}

// Actualizar producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar_producto'])) {
    $producto_id = $_POST['producto_id'];
    $categoria = trim($_POST['categoria']);
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval(str_replace(',', '.', str_replace('.', '', $_POST['precio'])));
    $stock = trim($_POST['stock']);
    $en_oferta = $_POST['en_oferta'];
    $descuento = $en_oferta == 'si' ? intval($_POST['descuento']) : 0;
    $precio_descuento = $en_oferta == 'si' ? $precio * (1 - $descuento / 100) : NULL;
    $imagen_url = isset($_FILES["imagen"]["name"]) && $_FILES["imagen"]["name"] != "" ? uploadImage($_FILES["imagen"]) : trim($_POST['imagen_actual']);

    $query = "UPDATE inventario SET categoria='$categoria', nombre='$nombre', descripcion='$descripcion', precio='$precio', stock='$stock', en_oferta='$en_oferta', descuento='$descuento', precio_descuento='$precio_descuento', imagen_url='$imagen_url' WHERE id_producto=$producto_id";
    $result = mysqli_query($conex, $query);

    if ($result) {
        echo "<script>alert('Producto actualizado exitosamente.'); window.location.href = 'agregar_producto.php';</script>";
    } else {
        echo "<script>alert('Error al actualizar producto: " . mysqli_error($conex) . "');</script>";
    }
}

// Obtener datos del producto para editar
$producto_editar = null;
if (isset($_GET['editar']) && !empty($_GET['editar'])) {
    $producto_id = intval($_GET['editar']);
    $result = mysqli_query($conex, "SELECT * FROM inventario WHERE id_producto = $producto_id");

    if ($result && mysqli_num_rows($result) > 0) {
        $producto_editar = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Producto no encontrado.'); window.location.href = 'agregar_producto.php';</script>";
    }
}

// Buscar producto
$productos = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar_producto'])) {
    $busqueda = trim($_POST['busqueda']);
    $query = "SELECT * FROM inventario WHERE nombre LIKE '%$busqueda%' OR categoria LIKE '%$busqueda%'";
    $result = mysqli_query($conex, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productos[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
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

<div class="container my-5">
    <h2 class="text-center mb-4">Buscar y Gestionar Productos</h2>
    <form method="post" action="agregar_producto.php" class="mb-5">
        <div class="input-group">
            <input type="text" class="form-control" name="busqueda" placeholder="Buscar productos por nombre o categoría">
            <button type="submit" class="btn btn-primary" name="buscar_producto">Buscar</button>
        </div>
    </form>

    <?php if (count($productos) > 0): ?>
        <h3 class="text-center mb-4">Resultados de la Búsqueda</h3>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>En Oferta</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto): ?>
                        <tr>
                            <td>
                                <div class="image-container" style="width: 100px; height: 100px; overflow: hidden;">
                                    <img src="<?php echo $producto['imagen_url']; ?>" class="img-thumbnail" alt="Imagen del producto" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td><?php echo $producto['nombre']; ?></td>
                            <td><?php echo $producto['categoria']; ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td><?php echo $producto['en_oferta']; ?></td>
                            <td>
                                <a href="agregar_producto.php?editar=<?php echo $producto['id_producto']; ?>" class="btn btn-warning">Actualizar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar_producto'])): ?>
        <div class="alert alert-warning text-center">No se encontraron productos para la búsqueda realizada.</div>
    <?php endif; ?>

    <h2 class="text-center mb-4"><?php echo isset($producto_editar) ? 'Editar Producto' : 'Agregar Producto'; ?></h2>
    <form method="post" action="agregar_producto.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoría</label>
            <select class="form-select" id="categoria" name="categoria">
                <option value="">Selecciona una categoría</option>
                <option value="muebles" <?php echo isset($producto_editar) && $producto_editar['categoria'] == 'muebles' ? 'selected' : ''; ?>>Muebles</option>
                <option value="decoracion" <?php echo isset($producto_editar) && $producto_editar['categoria'] == 'decoracion' ? 'selected' : ''; ?>>Decoración</option>
                <?php
                $categorias = mysqli_query($conex, "SELECT DISTINCT categoria FROM inventario");
                while ($row = mysqli_fetch_assoc($categorias)) {
                    $selected = isset($producto_editar) && $producto_editar['categoria'] == $row['categoria'] ? 'selected' : '';
                    echo "<option value=\"{$row['categoria']}\" $selected>{$row['categoria']}</option>";
                }
                ?>
            </select>
            <div class="mt-3">
                <label for="nueva_categoria" class="form-label">O ingrese una nueva categoría</label>
                <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria" placeholder="Nueva categoría">
            </div>
        </div>

        <!-- Nuevo campo: Producto en oferta -->
        <div class="mb-3">
            <label for="en_oferta" class="form-label">¿Producto en oferta?</label>
            <select class="form-select" id="en_oferta" name="en_oferta">
                <option value="no" <?php echo isset($producto_editar) && $producto_editar['en_oferta'] == 'no' ? 'selected' : ''; ?>>No</option>
                <option value="si" <?php echo isset($producto_editar) && $producto_editar['en_oferta'] == 'si' ? 'selected' : ''; ?>>Sí</option>
            </select>
        </div>

        <!-- Nuevo campo: Porcentaje de descuento -->        <!-- Nuevo campo: Porcentaje de descuento -->
        <div class="mb-3" id="descuento_field" style="display: <?php echo isset($producto_editar) && $producto_editar['en_oferta'] == 'si' ? 'block' : 'none'; ?>;">
            <label for="descuento" class="form-label">Porcentaje de descuento</label>
            <input type="number" class="form-control" id="descuento" name="descuento" min="0" max="100" step="1" value="<?php echo isset($producto_editar) ? $producto_editar['descuento'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo isset($producto_editar) ? $producto_editar['nombre'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo isset($producto_editar) ? $producto_editar['descripcion'] : ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" class="form-control" id="precio" name="precio" value="<?php echo isset($producto_editar) ? number_format($producto_editar['precio'], 2, ',', '.') : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?php echo isset($producto_editar) ? $producto_editar['stock'] : ''; ?>">
        </div>

        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen del Producto</label>
            <input class="form-control" type="file" id="imagen" name="imagen">
            <?php if (isset($producto_editar) && $producto_editar['imagen_url']): ?>
                <div class="image-container" style="width: 300px; height: 300px; overflow: hidden; margin-top: 10px;">
                    <img src="<?php echo $producto_editar['imagen_url']; ?>" class="img-thumbnail" alt="Imagen del Producto" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <input type="hidden" name="imagen_actual" value="<?php echo $producto_editar['imagen_url']; ?>">
            <?php endif; ?>
        </div>

        <div class="text-center">
            <?php if (isset($producto_editar)): ?>
                <input type="hidden" name="producto_id" value="<?php echo $producto_editar['id_producto']; ?>">
                <button type="submit" class="btn btn-primary" name="actualizar_producto">Actualizar Producto</button>
            <?php else: ?>
                <button type="submit" class="btn btn-primary" name="agregar_producto">Agregar Producto</button>
            <?php endif; ?>
        </div>
    </form>

    <script>
        document.getElementById('en_oferta').addEventListener('change', function() {
            if (this.value === 'si') {
                document.getElementById('descuento_field').style.display = 'block';
            } else {
                document.getElementById('descuento_field').style.display = 'none';
            }
        });
    </script>
</div>

<footer class="bg-dark text-white text-center py-4">
    <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
    <a href="#contacto" class="text-white">Contacto</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>