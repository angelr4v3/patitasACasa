<?php
include("template/cabecera.php");
include("../config/bd.php");

// Obtener el nombre del usuario actual desde la base de datos
// Supongamos que tienes una variable $correo con el correo electrónico del usuario actual
if (isset($_SESSION['correo'])) {
    $correo = $_SESSION['correo'];

    $consulta = $conexion->prepare("SELECT nombre FROM usuarios WHERE correo = :correo");
    $consulta->bindParam(':correo', $correo);
    $consulta->execute();

    if ($consulta->rowCount() > 0) {
        $fila = $consulta->fetch(PDO::FETCH_ASSOC);
        $nombre = $fila['nombre'];
    } else {
        $nombre = "invitado"; // Si no se encuentra el usuario en la base de datos
    }
} else {
    $nombre = "invitado"; // Si la variable de sesión 'correo' no está definida
}

?>

<div class="col-md-12">
    <div class="jumbotron">
        <h1 class="display-3">Bienvenido <?php echo $nombre; ?></h1>
        <p class="lead">Vamos a administrar la plataforma</p>
        <hr class="my-2">
        <p>More info</p>
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="patitas.php" role="button">Revisar animales</a>
        </p>
    </div>
</div>

<?php include("template/pie.php"); ?>
