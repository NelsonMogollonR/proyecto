<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container form-container">
    <!-- Formulario de Login -->
    <h2 class="text-center mb-4">Iniciar Sesión</h2>
    
    <form id="loginForm" class="needs-validation" novalidate method="post" action="process_login.php" target="_blank">
        <!-- Correo Electrónico -->
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">Por favor, ingrese su correo electrónico.</div>
        </div>

        <!-- Contraseña -->
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <div class="invalid-feedback">Por favor, ingrese su contraseña.</div>
        </div>

        <!-- Botones de Login y Salir -->
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            <a href="index.php" class="btn btn-secondary">Salir</a> <!-- Botón de Salir -->
        </div>
    </form>
    <div class="container mt-5"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
