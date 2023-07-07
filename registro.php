<?php
session_start();

include("config/bd.php");

// Verificar si se ha enviado el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar los datos del formulario
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];
    $confirmarContrasena = $_POST["confirmar_contrasena"];
    $numeroCelular = $_POST["numero_celular"];
    $cedula = isset($_POST["cedula"]) ? $_POST["cedula"] : null;
    $nit = isset($_POST["nit"]) ? $_POST["nit"] : null; 

    if ($contrasena !== $confirmarContrasena) {
        $_SESSION["mensaje"] = "Las contraseñas no coinciden. Inténtalo de nuevo.";
        header("Location: registro.php");
        exit();
    }

    // Validar y procesar los datos según el tipo de usuario
    if (isset($_POST["rol"]) && $_POST["rol"] == 1) {
        // Registro de cliente
        // Validar los datos específicos del cliente (opcional)
        if ($cedula && $nit) {
            $_SESSION["mensaje"] = "Solo se puede ingresar la Cédula o el NIT, no ambos.";
            header("Location: registro.php");
            exit();
        }

        // Insertar los datos en la tabla de usuarios
        $query = "INSERT INTO usuarios (nombre, correo, contrasenia, celular, cedula, nit) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $conexion->prepare($query);
        $statement->execute([$nombre, $correo, $contrasena, $numeroCelular, $cedula, $nit]);
        // Redirigir al usuario a una página de éxito o mostrar un mensaje de registro exitoso
        header("Location: login.php");
        exit();

    } elseif (isset($_POST["rol"]) && $_POST["rol"] == 2) {
        // Registro de administrador
        // Validar los datos específicos del administrador (opcional)
        if ($cedula && $nit) {
            $_SESSION["mensaje"] = "Solo se puede ingresar la Cédula o el NIT, no ambos.";
            header("Location: registro.php");
            exit();
        }

        // Insertar los datos en la tabla de usuarios
        $query = "INSERT INTO usuarios (nombre, correo, contrasenia, celular, cedula, nit) VALUES (?, ?, ?, ?, ?, ?)";
        $statement = $conexion->prepare($query);
        $statement->execute([$nombre, $correo, $contrasena, $numeroCelular, $cedula, $nit]);

        // Redirigir al usuario a una página de éxito o mostrar un mensaje de registro exitoso
        header("Location: login.php");
        exit();
    } else {
        // Error: No se especificó un rol válido
        echo "Error en el registro.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css" />

    <title>Document</title>
    <style>
        body {
            background-image: url("./img/fondo2.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">

    <div class="col-md-3">
        
    </div>
        <div class="col-md-6">
<br/><br/>
        <div class="card">
            <div class="card-header">
                
            <h2>Registro de Usuario</h2>
            </div>
            <div class="card-body">
            
            <?php if (isset($_SESSION["mensaje"])): ?>
            <div class="alert alert-danger" role="alert">
            <p><?php echo $_SESSION["mensaje"]; ?></p>
            <?php unset($_SESSION["mensaje"]); ?>
            </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class = "form-group">
                <label>Nombre:</label>
                <input type="text" class="form-control" name="nombre" required>
                </div>
                <div class = "form-group">
                <label>Correo Electrónico:</label>
                <input type="email" class="form-control" name="correo" required>
                </div>
                <div class = "form-group">
                <label>Número de Celular:</label>
                <input type="text" class="form-control" name="numero_celular">
                </div>
                <div class = "form-group">
                <label>Cédula:</label>
                <input type="text" class="form-control" name="cedula">
                </div>
                <div class = "form-group">
                <label>NIT:</label>
                <input type="text" class="form-control" name="nit">
                </div>
                <div class = "form-group">
                <label>Contraseña:</label>
                <input type="password" class="form-control" name="contrasena" required>
                </div>
                <label>Confirmar contraseña</label>
                <input type="password" class="form-control" name="confirmar_contrasena" required><br>
                <input type="hidden" name="rol" value="1"> <!-- Valor 1 para clientes -->
                <!-- <input type="hidden" name="rol" value="2"> <!-- Valor 2 para administradores -->
                <div class="form-group text-center">
                <button type="submit" class="btn btn-dark" value="Registrar">Registrarse</button>
                <a href="index.php" class="btn btn-dark">Volver</a>
                </div>
            </form>
            

            </div>
        </div>
            
        </div>
        
    </div>
  </div> 
  
  <br><br>           
</body>
</html>