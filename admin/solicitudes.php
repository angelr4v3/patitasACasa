<?php
include("template/cabecera.php");
?>
<?php
include("../config/bd.php");

// Verificar si se ha proporcionado el parámetro centroID en la URL
// Manejar el caso cuando no se proporciona el parámetro centroID
$sentenciaSQL = $conexion->prepare("SELECT * FROM final_adopcion");
$sentenciaSQL->execute();
$listaAnimal = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$SQL = $conexion->prepare("SELECT * FROM animal");
$SQL->execute();
$animales = $SQL->fetchAll(PDO::FETCH_ASSOC);

$SQLB = $conexion->prepare("SELECT * FROM usuarios");
$SQLB->execute();
$num = $SQLB->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
    <?php foreach($listaAnimal as $animal){ ?>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php
                    // Buscar el nombre del animal en la tabla "animal"
                    $animalID = $animal['id_animal'];
                    $nombreAnimal = "";
                    $imagenAnimal = "";
                    foreach($animales as $animalDB){
                        if($animalDB['id'] == $animalID){
                            $nombreAnimal = $animalDB['nombre'];
                            $imagenAnimal = $animalDB['imagen'];
                            break;
                        }
                    }
                    ?>
                    <h4 class="card-title">Nombre: <?php echo $nombreAnimal; ?></h4>
                    <?php if (!empty($imagenAnimal)) { ?>
                        <img src="../admin/img/<?php echo $imagenAnimal; ?>" alt="Imagen del animal" class="img-fluid">
                    <?php } ?>
                    <div class="mt-3">
                        <?php $pdfPath = dirname(dirname($_SERVER['PHP_SELF'])) . '/cliente/document/' . $animal['formulario']; ?>
                        <a class="btn btn-secondary" href="<?php echo $pdfPath; ?>" download>Descargar formulario</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>


<?php
include("template/pie.php");
?>
