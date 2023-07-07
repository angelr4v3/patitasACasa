<?php
session_start();
if (!isset($_SESSION['correo'])) {
  header("Location:../login.php");
} else {
  if ($_SESSION['correo'] == "ok") {
    $nombreUsuario = $_SESSION['nombreUsuario'];
  }
}


?>

<!doctype html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  </head>
  <body>

    <?php $url="http://".$_SERVER['HTTP_HOST']."/proyectofinal"?>
  <nav class="navbar navbar-expand navbar-light bg-light">
      <div class="nav navbar-nav">
          <a class="nav-item nav-link active" href="index.php">Admin <span class="sr-only">(current)</span></a>
          <a class="nav-item nav-link" href="<?php echo $url;?>/admin/patitas.php">Patitas</a>
          <a class="nav-item nav-link" href="<?php echo $url;?>/admin/ubicacion.php">Registra tu ubicacion</a>
          <a class="nav-item nav-link" href="<?php echo $url;?>/admin/formAdopcion.php">Formulario de adopcion</a>
          <a class="nav-item nav-link" href="<?php echo $url;?>/admin/Solicitudes.php">Solicitud de adopcion</a>
          <a class="nav-item nav-link" href="<?php echo $url;?>/admin/cerrar.php">Cerrar sesion</a>
        </div>
  </nav>
  <br>


    <div class="container">
        <div class="row">