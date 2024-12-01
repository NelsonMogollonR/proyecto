<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$cliente_id = $_SESSION['cliente_id'];
$cart_count = count($_SESSION['carrito']);
$total_compra = 0.0;

// Obtener los datos del cliente
$query = "SELECT nombres, apellidos, telefono, direccion, email FROM datos WHERE id_cliente = $cliente_id";
$result = mysqli_query($conex, $query);
$cliente = mysqli_fetch_assoc($result);

// Calcular el total de la compra considerando descuentos
foreach ($_SESSION['carrito'] as $item) {
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
    <title>Finalizar Compra</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Formulario de Finalización de Compra -->
<div class="container my-5">
    <h2 class="text-center mb-4">Finalizar Compra</h2>
    
    <form action="procesar_compra.php" method="post">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $cliente['nombres'] . ' ' . $cliente['apellidos']; ?>" required>
                <div class="invalid-feedback">Por favor ingrese su nombre completo.</div>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $cliente['email']; ?>" required>
                <div class="invalid-feedback">Por favor ingrese un correo electrónico válido.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $cliente['telefono']; ?>" required>
                <div class="invalid-feedback">Por favor ingrese su número de teléfono.</div>
            </div>
            <div class="col-md-6">
                <label for="direccion" class="form-label">Dirección de Envío</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?php echo $cliente['direccion']; ?>" required>
                <div class="invalid-feedback">Por favor ingrese su dirección de envío.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="ciudad" class="form-label">Ciudad</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                <div class="invalid-feedback">Por favor ingrese su ciudad.</div>
            </div>
            <div class="col-md-6">
                <label for="codigo_postal" class="form-label">Código Postal</label>
                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" required>
                <div class="invalid-feedback">Por favor ingrese su código postal.</div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="metodo_pago" class="form-label">Método de Pago</label>
                <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                   
                     <option value="pago_contra_entrega">Pago Contra Entrega</option>
                </select>
                <div class="invalid-feedback">Por favor seleccione un método de pago.</div>
            </div>
            <div class="col-md-6">
                <label for="total_compra" class="form-label">Total de la Compra</label>
                <input type="hidden" id="total_compra" name="total_compra" value="<?php echo $total_compra; ?>">
                <input type="text" class="form-control" value="<?php echo formatoMoneda($total_compra); ?>" readonly>
            </div>
        </div>
        <div class="text-end">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
            <button type="submit" class="btn btn-success">Confirmar Compra</button>
        </div>
    </form>
</div>

<!-- Footer -->
<footer class="bg-dark text-white text-center py-4">
    <p>&copy; 2023 Muebles Modernos. Todos los derechos reservados.</p>
    <a href="#contacto" class="text-white">Contacto</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
</body>
</html>
