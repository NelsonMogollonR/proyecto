<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="stylesRegistro.css">
</head>


    <form method="post" autocomplete="off">
        <!-- Formulario de Registro -->
        <h2>BIENVENIDO</h2>
        <div class="input_group">
            <div class="input-container">
                <input type="text" name="nombres" placeholder="Nombres">
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="input-container">
                <input type="text" name="apellidos" placeholder="Apellidos">
                <i class="fa-solid fa-user"></i>
            </div>

            <!-- Tipo de Documento -->
            <div class="input-container">
                <label for="tipoDocumento" class="form-label">Tipo de Documento</label>
                <select class="form-select" id="tipoDocumento" name="tipoDocumento" required>
                    <option selected disabled value="">Seleccione una opción</option>
                    <option value="TI">DNI</option>
                    <option value="Pasaporte">Pasaporte</option>
                    <option value="Cédula">Cédula</option>
                    <option value="Otro">Otro</option>
                </select>
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div class="input-container">
                <input type="text" name="documento" placeholder="Número de documento">
                <i class="fa-solid fa-id-card"></i>
            </div>
            <div class="input-container">
                <input type="text" name="direccion" placeholder="Dirección vivienda">
                <i class="fa-solid fa-map-location"></i>
            </div>

            <div class="input-container">
                <input type="tel" name="phone" placeholder="Teléfono">
                <i class="fa-solid fa-phone"></i>
            </div>

            <div class="input-container">
                <input type="email" name="email" placeholder="Email">
                <i class="fa-solid fa-envelope"></i>
            </div>

            <div class="input-container">
                <input type="password" name="password" placeholder="Contraseña">
                <i class="fa-solid fa-lock"></i>
            </div>

            <a href="#">Términos y condiciones</a>
            <input type="submit" name="send" class="btn" value="Enviar">
            <input type="button" class="btn btn-secondary" value="Cancelar" onclick="window.location.href='index.php'">
        </div>
        
    </form>


    <?php include("send.php"); ?>
</body>

</html>
