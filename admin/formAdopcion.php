<?php 
include("template/cabecera.php"); 
include("../config/bd.php");

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtIDUsuario = (isset($_POST['txtIDUsuario'])) ? $_POST['txtIDUsuario'] : "";
$txtFormulario = (isset($_FILES['txtFormulario']['name'])) ? $_FILES['txtFormulario']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

switch ($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO adopcion (formulario, id_usuario) VALUES (:formulario, :id_usuario);");
        $sentenciaSQL->bindParam(':formulario', $txtFormulario);        
        $sentenciaSQL->bindParam(':id_usuario', $txtIDUsuario);

        $fecha= new DateTime();
        $tmpFormulario = $_FILES["txtFormulario"]["tmp_name"];
        
        if ($_FILES["txtFormulario"]["tmp_name"] != "") {
            $nombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtFormulario"]["name"];
            move_uploaded_file($tmpFormulario, "document/" . $nombreArchivo);
            $sentenciaSQL->bindParam(':formulario', $nombreArchivo);
        }
        $sentenciaSQL->bindParam(':formulario', $nombreArchivo);


        $sentenciaSQL->execute();   
        header("Location: formAdopcion.php");
        break;

        case"Modificar":

            $sentenciaSQL = $conexion->prepare("UPDATE adopcion SET formulario=:formulario, id_usuario=:id_usuario WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->bindParam(':id_usuario', $txtIDUsuario);
            $sentenciaSQL->execute();

            if ($txtFormulario != "") {
                $fecha = new DateTime();
                $archivoPdf = ($txtFormulario != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtFormulario"]["name"] : "documento.pdf";
                $tmpDocumento = $_FILES["txtFormulario"]["tmp_name"];

                move_uploaded_file($tmpDocumento, "document/" . $archivoPdf);

                $sentenciaSQL = $conexion->prepare("SELECT formulario FROM adopcion WHERE id=:id");
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->execute();
                $animal = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

                if (isset($animal["formulario"]) && ($animal["formulario"] != "documento.pdf")) {
                    if (file_exists("document/" . $animal["formulario"])) {
                        unlink("document/" . $animal["formulario"]);
                    }
                }

                $sentenciaSQL = $conexion->prepare("UPDATE adopcion SET formulario=:formulario WHERE id=:id");
                $sentenciaSQL->bindParam(':formulario', $archivoPdf);
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->execute();
            }

            header("Location: formAdopcion.php");


        break;
        $sentenciaSQL = $conexion->prepare("UPDATE adopcion SET formulario=:formulario,  WHERE id=:id");
        $sentenciaSQL->bindParam(':formulario', $txtFormulario); 
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();  

        if ($txtF != "") {
            $fecha = new DateTime();
            $archivoPdf = $fecha->getTimestamp() . "_" . $_FILES["txtFormulario"]["name"];
            $tmpDocumento = $_FILES["txtFormulario"]["tmp_name"];
            
            move_uploaded_file($tmpDocumento, "document/" . $archivoPdf);
            
            $sentenciaSQL = $conexion->prepare("SELECT formulario FROM adopcion WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();
            $animal = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
            
            if (isset($animal["formulario"]) && ($animal["formulario"] != "documento.pdf")) {
                if (file_exists("document/" . $animal["formulario"])) {
                    unlink("document/" . $animal["formulario"]);
                }
            }
            
            $sentenciaSQL = $conexion->prepare("UPDATE adopcion SET formulario=:formulario WHERE id=:id");
            $sentenciaSQL->bindParam(':formulario', $archivoPdf);
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();  
        }

        header("Location: formAdopcion.php");
        break;

    case "Cancelar":
        header("Location: formAdopcion.php");
        break;

    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM adopcion WHERE id=:id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();   
        $animal = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        break;

    case "Borrar":
        $sentenciaSQL= $conexion->prepare("SELECT formulario FROM adopcion WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();   
            $animal=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if( isset($animal["formulario"]) &&($animal["formularo"]!= "documento.pdf")){

                if(file_exists("document/".$animal["formulario"])){
                    unlink("document/".$animal["formulario"]);
                }
            } 

            $sentenciaSQL= $conexion->prepare("DELETE FROM adopcion WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute(); 
            header("Location:formAdopcion.php");
            
        break;
}

$sentenciaSQL = $conexion->prepare("SELECT * FROM adopcion");
$sentenciaSQL->execute();   
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de libros
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
            <div class = "form-group">
            <label for="txtID">ID:</label>
            <input type="text" required readonly class="form-control" value="<?php echo $txtID;?>" name="txtID" id="txtID" placeholder="ID">
            </div>
                <div class="form-group">
                    <label for="txtIDUsuario">centro:</label>
                    <select class="form-control" name="txtIDUsuario" id="txtIDUsuario">
                        <option value="">Nombre del centro</option>
                        <?php
                        // Obtener los usuarios de la base de datos
                        $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios WHERE nit IS NOT NULL AND nit != ''");
                        $stmt->execute();
                        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Generar las opciones del select
                        foreach ($usuarios as $usuario) {
                            $selected = ($txtIdUsuario == $usuario['id']) ? 'selected' : '';
                            echo "<option value='{$usuario['id']}' $selected>{$usuario['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="txtFormulario">Formulario:</label>
                    <br>
                    <?php if ($txtFormulario != "") { ?>
                        <img class="img-thumbnail rounded" src="document/<?php echo $txtFormulario; ?>" width="50" alt="" srcset="">
                    <?php } ?>
                    <input type="file" class="form-control" name="txtFormulario" id="txtFormulario" placeholder="Ingrese el formulario">
                </div>
                <div class="btn-group" role="group" aria-label="">
                    <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
                    <button type="submit" name="accion" <?php echo ($accion != "Seleccionar") ? "disabled" : ""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Id</th> 
                <th>Formulario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listaLibros as $libro) { ?>
                <tr>
                    <td><?php echo $libro['id']; ?></td>
                    <td>
                        <?php if (!empty($libro['formulario'])) { ?>
                            <a href="document/<?php echo $libro['formulario']; ?>" target="_blank">Ver Formulario</a>
                        <?php } else { ?>
                            Sin formulario adjunto
                        <?php } ?>
                    </td>
                    <td>

                        <form method="post">
                            <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>"/>
                            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php 
include("template/pie.php"); 
?>
