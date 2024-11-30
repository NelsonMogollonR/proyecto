<?php
session_start(); // Iniciar sesión
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validar si el correo existe
    $query = "SELECT * FROM datos WHERE email='$email'";
    $result = mysqli_query($conex, $query);

    if (!$result) {
        echo "<script>alert('Error en la consulta: " . mysqli_error($conex) . "');</script>";
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Validar la contraseña
        if ($row['contraseña'] == $password) {
            $_SESSION['cliente_id'] = $row['id_cliente']; // Actualizar a 'id_cliente'
            $_SESSION['cliente'] = $row['nombres']; // Almacenar nombre del cliente en la sesión
            
            // Verificar si el usuario es administrador
            if ($email == '@admin' && $password == '123') {
                $_SESSION['admin'] = true;
            } else {
                $_SESSION['admin'] = false;
            }
            
            echo "<script>alert('Bienvenido, " . $_SESSION['cliente'] . "'); window.location.href = 'index.php';</script>";
        } else {
            echo "<script>alert('Contraseña incorrecta.'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('El cliente no existe. Redirigiendo a la página de registro.'); window.location.href = 'registro.php';</script>";
    }
}
?>
