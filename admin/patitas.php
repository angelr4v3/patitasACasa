<?php 
include("template/cabecera.php"); 
include("../config/bd.php");

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtIdUsuario=(isset($_POST['txtIdUsuario']))?$_POST['txtIdUsuario']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtEspecie=(isset($_POST['txtEspecie']))?$_POST['txtEspecie']:"";
$txtRaza=(isset($_POST['txtRaza']))?$_POST['txtRaza']:"";
$txtEdad=(isset($_POST['txtEdad']))?$_POST['txtEdad']:"";
$txtHistoria = (isset($_FILES['txtHistoria']['name'])) ? $_FILES['txtHistoria']['name'] : "";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";


switch($accion){//manejar los casos para el uso de los btn

        case"Agregar":
            $sentenciaSQL= $conexion->prepare("INSERT INTO animal (nombre, especie, raza, edad, historia_clinica, imagen, id_usuario) VALUES (:nombre, :especie, :raza, :edad, :historia_clinica, :imagen, :id_usuario);");
            $sentenciaSQL->bindParam(':nombre', $txtNombre);
            $sentenciaSQL->bindParam(':id_usuario', $txtIdUsuario);
            $sentenciaSQL->bindParam(':especie', $txtEspecie);
            $sentenciaSQL->bindParam(':raza', $txtRaza);
            $sentenciaSQL->bindParam(':edad', $txtEdad);
            $sentenciaSQL->bindParam(':edad', $txtEdad);



            $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";// le asigna un nombre a la imagen

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){

                move_uploaded_file($tmpImagen,"img/".$nombreArchivo);//inserta la imagen en la carpeta img con el nombre asignado
            }

            $sentenciaSQL->bindParam(':imagen', $nombreArchivo);

            $tmpHistoria = $_FILES["txtHistoria"]["tmp_name"];

            if ($tmpHistoria != "") {
                $nombreArchivoHistoria = $fecha->getTimestamp() . "_" . $_FILES["txtHistoria"]["name"];
                move_uploaded_file($tmpHistoria, "document/" . $nombreArchivoHistoria);
                $sentenciaSQL->bindParam(':historia_clinica', $nombreArchivoHistoria);
            }
            $sentenciaSQL->bindParam(':historia_clinica', $nombreArchivoHistoria);

            $sentenciaSQL->execute();   
            header("Location:patitas.php");
            break;


            case"Modificar":

                $sentenciaSQL= $conexion->prepare("UPDATE animal SET nombre=:nombre, raza=:raza, especie=:especie, edad=:edad, id_usuario=:id_usuario  WHERE id=:id");
                $sentenciaSQL->bindParam(':nombre', $txtNombre); 
                $sentenciaSQL->bindParam(':id_usuario', $txtIdUsuario);
                $sentenciaSQL->bindParam(':id', $txtID);
                $sentenciaSQL->bindParam(':especie', $txtEspecie);
                $sentenciaSQL->bindParam(':raza', $txtRaza);
                $sentenciaSQL->bindParam(':edad', $txtEdad);
                $sentenciaSQL->execute();  
                

                if ($txtImagen != "") {
                    $fecha = new DateTime();
                    $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
                    $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
                
                    move_uploaded_file($tmpImagen, "img/" . $nombreArchivo);
                
                    $sentenciaSQL = $conexion->prepare("SELECT imagen FROM animal WHERE id=:id");
                    $sentenciaSQL->bindParam(':id', $txtID);
                    $sentenciaSQL->execute();
                    $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
                
                    if (isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
                        if (file_exists("img/" . $libro["imagen"])) {
                            unlink("img/" . $libro["imagen"]);
                        }
                    }
                
                    $sentenciaSQL = $conexion->prepare("UPDATE animal SET imagen=:imagen WHERE id=:id");
                    $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
                    $sentenciaSQL->bindParam(':id', $txtID);
                    $sentenciaSQL->execute();
                }
                

                if ($txtHistoria != "") {

                    $fecha = new DateTime();
                    $archivoPdf = ($txtHistoria != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtHistoria"]["name"] : "documento.pdf";
                    $tmpDocumento = $_FILES["txtHistoria"]["tmp_name"];
                    
                    move_uploaded_file($tmpDocumento, "document/" . $archivoPdf);
                    
                    $sentenciaSQL = $conexion->prepare("SELECT historia_clinica FROM animal WHERE id=:id");
                    $sentenciaSQL->bindParam(':id', $txtID);
                    $sentenciaSQL->execute();
                    $animal = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
                    
                if (isset($animal["historia_clinica"]) && ($animal["historia_clinica"] != "documento.pdf")) {
                    if (file_exists("document/" . $animal["historia_clinica"])) {
                        unlink("document/" . $animal["historia_clinica"]);
                        }
                    }
                    $sentenciaSQL= $conexion->prepare("UPDATE animal SET historia_clinica=:historia_clinica WHERE id=:id");
                    $sentenciaSQL->bindParam(':historia_clinica', $archivoPdf);
                    $sentenciaSQL->bindParam(':id', $txtID);
                    $sentenciaSQL->execute();  
                }

                header("Location:patitas.php");
 
            break;
        case"Cancelar":
            header("Location:patitas.php");
            break;
        case"Seleccionar":

            $sentenciaSQL= $conexion->prepare("SELECT * FROM animal WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();   
            $animal=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            $txtIdUsuario=$animal['id_usuario'];
            $txtNombre=$animal['nombre'];
            $txtRaza=$animal['raza'];
            $txtEspecie=$animal['especie'];
            $txtEdad=$animal['edad'];
            $txtHistoria=$animal['historia_clinica'];
            $txtImagen=$animal['imagen'];

            break;
        case"Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT imagen, historia_clinica FROM animal WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute();   
            $animal=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

            if( isset($animal["imagen"]) &&($animal["imagen"]!= "imagen.jpg")){

                if(file_exists("img/".$animal["imagen"])){
                    unlink("img/".$animal["imagen"]);
                }
            } 

            if( isset($animal["historia_clinica"]) &&($animal["historia_clinica"]!= "documento.pdf")){

                if(file_exists("document/".$animal["historia_clinica"])){
                    unlink("document/".$animal["historia_clinica"]);
                }
            } 

            $sentenciaSQL= $conexion->prepare("DELETE FROM animal WHERE id=:id");
            $sentenciaSQL->bindParam(':id', $txtID);
            $sentenciaSQL->execute(); 
            header("Location:patitas.php");
            
            break;

}

$sentenciaSQL= $conexion->prepare("SELECT * FROM animal");
$sentenciaSQL->execute();   
$listaLibros=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);


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

                            <label for="txtIdUsuario">Usuario:</label>
                            <select class="form-control" name="txtIdUsuario" id="txtIdUsuario">
                                <option value="">Nombre del centro</option>
                                <?php
                                // Obtener los usuarios de la base de datos
                                $stmt = $conexion->prepare("SELECT id, nombre FROM usuarios  WHERE nit IS NOT NULL AND nit != ''");
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

            <div class = "form-group">
            <label for="txtNombre">Nombre:</label>
            <input type="text" required class="form-control" value="<?php echo $txtNombre;?>" name="txtNombre" id="txtNombre" placeholder="Ingrese el nombre">
            </div>

            
            <div class = "form-group">
            <label for="txtespecie">Especie:</label>
            <input type="text" required class="form-control" value="<?php echo $txtEspecie;?>" name="txtEspecie" id="txtEspecie" placeholder="Ingrese la especie">
            </div>

            
            <div class = "form-group">
            <label for="txtRaza">Raza:</label>
            <input type="text" required class="form-control" value="<?php echo $txtRaza;?>" name="txtRaza" id="txtRaza" placeholder="Ingrese la raza">
            </div>

            
            <div class = "form-group">
            <label for="txtEdad">Edad:</label>
            <input type="text" required class="form-control" value="<?php echo $txtEdad;?>" name="txtEdad" id="txtEdad" placeholder="Ingrese la edad">
            </div>

            <div class = "form-group">
            <label for="txtHistoria">Historia:</label>
            <br>
            
            <?php if($txtHistoria!=""){ ?>

                <img class="img-thumbnail rounded" src="document/<?php echo $txtHistoria; ?>" widht="50" alt="" srcset="">
                 
            <?php } ?>
            <input type="file" class="form-control" name="txtHistoria" id="txtHistoria" placeholder="Ingrese la Historia clinica">
            </div>


            <div class = "form-group">
            <label for="txtImagen">Imagen:</label>
            <br>
            <?php if($txtImagen!=""){ ?>

                <img class="img-thumbnail rounded" src="img/<?php echo $txtImagen; ?>" widht="50" alt="" srcset="">
                 
            <?php } ?>
            <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Ingrese la imagen">
            </div>

            <div class="btn-group" role="group" aria-label="">
                <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Modificar" class="btn btn-warning">Modificar</button>
                <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":""; ?> value="Cancelar" class="btn btn-info">Cancelar</button>
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
                <th>Nombre</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaLibros as $libro) {?>
            <tr>
                <td><?php echo $libro['id']; ?></td>
                <td><?php echo $libro['nombre']; ?></td>
                <td>

                <img class="img-thumbnail rounded" src="img/<?php echo $libro['imagen']; ?>" widht="50" alt="" srcset="">
                    
                
            
                </td>
                <td>
                   
                <form method="post">

                    <input type="hidden" name="txtID" id="txtID" value="<?php echo $libro['id']; ?>"/>
                    
                    <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>
                    <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>


                </form>
            
            
                </td>
            </tr>   
            <?php }?>         
        </tbody>
    </table>
    
</div>


<?php include("template/pie.php"); ?>
