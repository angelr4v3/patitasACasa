<?php
session_start();
include("config/bd.php");


// Verificar si se ha enviado el formulario de inicio de sesión
if ($_POST) {
  // Recibir los datos del formulario
  $correo = $_POST['correo'];
  $contrasenia = $_POST['contrasena'];

  // Incluir el archivo de conexión a la base de datos
  try {
    // Consulta a la base de datos
    $consulta = "SELECT * FROM usuarios WHERE correo = :correo AND contrasenia = :contrasenia";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':contrasenia', $contrasenia);
    $stmt->execute();

    // Verificar el resultado de la consulta
    if ($stmt->rowCount() > 0) {
      $_SESSION['correo'] = "ok";
      $_SESSION['nombreUsuario'] = $nombreUsuario;
      header('Location: inicio.php');
    } else {
      $mensaje = "Error: Correo o contraseña incorrecta";
    }
  } catch (PDOException $e) {
    echo "Error de conexión a la base de datos: " . $e->getMessage();
  }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Consulta para verificar las credenciales de inicio de sesión
    $query = "SELECT * FROM usuarios WHERE correo = ? AND contrasenia = ?";
    $statement = $conexion->prepare($query);
    $statement->execute([$correo, $contrasena]);
    $usuario = $statement->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['correo'] = $correo;
        // Verificar el tipo de usuario según los campos "cedula" y "nit"
        if (empty($usuario["cedula"])) {
            // Usuario administrador, redirigir a la carpeta "admin" con el archivo "index.php"
            header("Location: admin/index.php");
            exit();
        } elseif (empty($usuario["nit"])) {
            // Usuario cliente, redirigir a la carpeta "cliente" con el archivo "index.php"
            header("Location: cliente/index.php");
            exit();
        }
    } else {
        // Las credenciales son inválidas
        $_SESSION["mensaje"] = "Credenciales de inicio de sesión inválidas.";
        header("Location: login.php");
        exit();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css" />

    <title>Iniciar sesión</title>
    <style>
        body {
            background-image: url("./img/fondo2.jpg");
            background-size: cover;
        }
    </style>
</head>
<body>
    <br><br><br><br><br><br><br><br>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <br/><br/>
                <div class="card">
                    <div class="card-header">
                       <h2> Iniciar sesión</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION["mensaje"])): ?>
                            <div class="alert alert-danger" role="alert">
                                <p><?php echo $_SESSION["mensaje"]; ?></p>
                                <?php unset($_SESSION["mensaje"]); ?>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="correo">Correo Electrónico:</label>
                                <input type="email" class="form-control" name="correo" required>
                            </div>
                            <div class="form-group">
                                <label for="contrasena">Contraseña:</label>
                                <input type="password" class="form-control" name="contrasena" required><br>
                            </div>
                            <div class="form-group text-center">
                            <button type="submit" class="btn btn-dark" value="Iniciar sesión">Iniciar sesión</button>
                            <a href="index.php" class="btn btn-dark">Volver</a>
                            </div>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>