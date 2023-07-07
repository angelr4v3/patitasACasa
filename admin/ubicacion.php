<?php
include("template/cabecera.php");
include("../config/bd.php");

$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtCentro = (isset($_POST['txtCentro'])) ? $_POST['txtCentro'] : "";
$txtIdUsuario = (isset($_POST['txtIdUsuario'])) ? $_POST['txtIdUsuario'] : "";
$txtPais = (isset($_POST['txtPais'])) ? $_POST['txtPais'] : "";
$txtCiudad = (isset($_POST['txtCiudad'])) ? $_POST['txtCiudad'] : "";
$txtDireccion = (isset($_POST['txtDireccion'])) ? $_POST['txtDireccion'] : "";
$txtLatitud = (isset($_POST['txtLatitud'])) ? $_POST['txtLatitud'] : "";
$txtLongitud = (isset($_POST['txtLongitud'])) ? $_POST['txtLongitud'] : "";
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";

switch ($accion) {
    case "Agregar":
        $stmt = $conexion->prepare("INSERT INTO ubicacion ( id_usuario, direccion, ciudad, pais, latitud, longitud, imagen ) VALUES (:id_usuario, :direccion, :ciudad, :pais, :latitud, :longitud, :imagen)");

        // Vincular los valores del formulario a los marcadores de posición
        $stmt->bindParam(':id_usuario', $txtIdUsuario);
        $stmt->bindParam(':direccion', $txtDireccion);
        $stmt->bindParam(':ciudad', $txtCiudad);
        $stmt->bindParam(':pais', $txtPais);
        $stmt->bindParam(':latitud', $txtLatitud);
        $stmt->bindParam(':longitud', $txtLongitud);


        $fecha= new DateTime();
            $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

            $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

            if($tmpImagen!=""){

                move_uploaded_file($tmpImagen,"img/".$nombreArchivo);
            }

            $stmt->bindParam(':imagen', $nombreArchivo);

        /*if (!empty($txtLatitud) && !empty($txtLongitud)) {
            // Ejecutar la consulta de inserción en la base de datos
            $stmt->execute();
            header("Location: ubicacion.php");
        } else {
            // Mostrar un mensaje de error o tomar alguna acción en caso de que las coordenadas estén vacías
            echo "No se pudieron obtener las coordenadas de latitud y longitud.";
        }*/
        $stmt->execute();
            header("Location: ubicacion.php");
        break;
}

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card">
                <div class="card-header">
                    Ubicacion
                </div>

                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data" onsubmit="obtenerCoordenadas()">

                    <div class="form-group">
                            <label for="txtIdUsuario">Centro:</label>
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

                        <div class="form-group">
                            <label>Pais:</label>
                            <input type="text" required class="form-control" value="<?php echo $txtPais; ?>" name="txtPais" id="txtPais" placeholder="Ingrese el Pais">
                        </div>

                        <div class="form-group">
                            <label>Ciudad:</label>
                            <input type="text" required class="form-control" value="<?php echo $txtCiudad; ?>" name="txtCiudad" id="txtCiudad" placeholder="Ingrese la ciudad">
                        </div>

                        <div class="form-group">
                            <label>Direccion:</label>
                            <input type="text" required class="form-control" value="<?php echo $txtDireccion; ?>" name="txtDireccion" id="txtDireccion" placeholder="Ingrese la direccion">
                        </div>


                        <div class="form-group">
                            <label for="txtImagen">Imagen:</label>
                            <br>

                            <?php if ($txtImagen != "") { ?>

                                <img class="img-thumbnail rounded" src="img/<?php echo $txtImagen; ?>" width="50" alt="" srcset="">

                            <?php } ?>
                            <input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="Ingrese la imagen">
                        </div>

                        <div class="btn-group d-flex justify-content-center" role="group" aria-label="">
                            <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-success">Agregar</button>
                        </div>

                        <input type="hidden" name="txtLatitud" id="txtLatitud">
                        <input type="hidden" name="txtLongitud" id="txtLongitud">

                    </form>
                    <script>
                        // Obtener las coordenadas de latitud y longitud con Google Maps
                        function obtenerCoordenadas() {
                            var txtDireccion = document.querySelector('input[name="txtDireccion"]').value;
                            var url = `https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(txtDireccion)}&key=TU_API_KEY`;

                            fetch(url)
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data); // Imprimir la respuesta de la API en la consola
                                    if (data.results.length > 0) {
                                        var latitud = data.results[0].geometry.location.lat;
                                        var longitud = data.results[0].geometry.location.lng;

                                        console.log(latitud, longitud); // Imprimir los valores de latitud y longitud en la consola

                                        document.getElementById('txtLatitud').value = latitud;
                                        document.getElementById('txtLongitud').value = longitud;
                                    }
                                })
                                .catch(error => console.log(error));
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("template/pie.php"); ?>
