<?php
include("conexion.php");

if (isset($_POST['send'])) {
    if (
        strlen($_POST['nombres']) >= 1 &&
        strlen($_POST['apellidos']) >= 1 &&
        strlen($_POST['tipoDocumento']) >= 1 &&
        strlen($_POST['documento']) >= 1 &&
        strlen($_POST['direccion']) >= 1 &&
        strlen($_POST['phone']) >= 1 &&
        strlen($_POST['email']) >= 1 &&
        strlen($_POST['password']) >= 1
    ) {
        $nombres = trim($_POST['nombres']);
        $apellidos = trim($_POST['apellidos']);
        $tipoDocumento = trim($_POST['tipoDocumento']);
        $documento = trim($_POST['documento']);
        $direccion = trim($_POST['direccion']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $fecha = date("d/m/y");

        // Validar si el documento ya existe en la base de datos
        $query = "SELECT * FROM datos WHERE documento='$documento'";
        $result = mysqli_query($conex, $query);

        if (mysqli_num_rows($result) > 0) {
            echo "<script>alert('El documento ya está registrado.');</script>";
        } else {
            // Insertar los datos si el documento no existe
            $consulta = "INSERT INTO datos(nombres, apellidos, tipoDocumento, documento, direccion, telefono, email, contraseña, fecha) 
                         VALUES ('$nombres','$apellidos', '$tipoDocumento', '$documento', '$direccion', '$phone', '$email', '$password', '$fecha')";
            $resultado = mysqli_query($conex, $consulta);

            if ($resultado) {
                echo "<script>alert('Datos registrados exitosamente.'); window.location.href = 'index.php';</script>";
                // Limpiar las variables
                unset($_POST['nombres']);
                unset($_POST['apellidos']);
                unset($_POST['tipoDocumento']);
                unset($_POST['documento']);
                unset($_POST['direccion']);
                unset($_POST['phone']);
                unset($_POST['email']);
                unset($_POST['password']);
            } else {
                echo "<script>alert('Error al registrar los datos: " . mysqli_error($conex) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos.');</script>";
    }
}
?>
