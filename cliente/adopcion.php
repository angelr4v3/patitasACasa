<?php
include("template/cabecera.php");
?>
<?php
include("../config/bd.php");

// Verificar si se ha proporcionado el parámetro centroID en la URL
if (isset($_GET['centroID'])) {
    $centroID = $_GET['centroID'];
    $sentenciaSQL = $conexion->prepare("SELECT * FROM animal WHERE id_usuario = :centroID");
    $sentenciaSQL->bindParam(':centroID', $centroID);
    $sentenciaSQL->execute();   
    $listaAnimal = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Manejar el caso cuando no se proporciona el parámetro centroID
    // Por ejemplo, mostrar todos los animales sin filtrar por centro
    $sentenciaSQL = $conexion->prepare("SELECT * FROM animal");
    $sentenciaSQL->execute();   
    $listaAnimal = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="row">
    <?php foreach($listaAnimal as $animal){ ?>
        <div class="col-md-3">
            <div class="card">
                <img class="card-img-top" src="../admin/img/<?php echo $animal['imagen']; ?>" alt="">
                <div class="card-body text-center">
                    <h4 class="card-title">id: <?php echo $animal['id']; ?></h4>
                    <h4 class="card-title">nombre: <?php echo $animal['nombre']; ?></h4>
                    <h4 class="card-title">raza: <?php echo $animal['raza']; ?></h4>
                    <div class="d-flex justify-content-center">
                        <?php if (isset($centroID)) { ?>
                            <a class="btn btn-primary" href="formAdopcion.php?centroID=<?php echo $centroID; ?>&id_animal=<?php echo $animal['id']; ?>" role="button">Adoptar a este amigo</a>
                        <?php } else { ?>
                            <a class="btn btn-primary" href="formAdopcion.php?id_animal=<?php echo $animal['id']; ?>" role="button">Adoptar a este amigo</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php
include("template/pie.php");
?>
