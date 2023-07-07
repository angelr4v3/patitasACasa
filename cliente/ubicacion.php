<?php
include("template/cabecera.php");
?>
<?php
include("../config/bd.php");
$sentenciaSQL= $conexion->prepare("SELECT * FROM ubicacion");
$sentenciaSQL->execute();   
$listaCentros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

$SQLB = $conexion->prepare("SELECT * FROM usuarios");
$SQLB->execute();
$usuarios = $SQLB->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="row">
<?php foreach($listaCentros as $centros){ ?>
    <div class="col-md-3">
        <div class="card">
            <img class="card-img-top" src="../admin/img/<?php echo $centros['imagen']; ?>" alt="">
            <div class="card-body text-center">
                <?php
                // Buscar el nombre del centro en la tabla "usuarios"
                $centroID = $centros['id_usuario'];
                $nombreCentro = "";
                foreach($usuarios as $usuario){
                    if($usuario['id'] == $centroID){
                        $nombreCentro = $usuario['nombre'];
                        break;
                    }
                }
                ?>
                <h4 class="card-title">Nombre: <?php echo $nombreCentro; ?></h4>
                <h4 class="card-title">Dirección: <?php echo $centros['direccion']; ?></h4>
                <div class="justify-content-center">
                    <div>
                        <a class="btn btn-primary" href="adopcion.php?centroID=<?php echo $centros['id_usuario']; ?>" role="button">Adopta a un amigo</a>
                    <br><br>
                    </div>
                    <div>
                        <a class="btn btn-primary" href="donacion.php?donacionID=<?php echo $centros['id_usuario']; ?>" role="button">Haz una donación</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
</div>


<?php
include("template/pie.php");
?>
