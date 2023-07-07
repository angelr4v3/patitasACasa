<?php
include("template/cabecera.php");
?>
<?php
include("../config/bd.php");
$txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtIDAnimal = (isset($_GET['id_animal'])) ? $_GET['id_animal'] : "";
$txtFormulario = (isset($_FILES['txtFormulario']['name'])) ? $_FILES['txtFormulario']['name'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : "";
switch ($accion) {//switc para cuando se le da a lops btones
  case "Agregar"://cuando se le da al btn agg
      $sentenciaSQL = $conexion->prepare("INSERT INTO final_adopcion (formulario, id_animal) VALUES (:formulario, :id_animal);"); //consulta en la base de datos
      $sentenciaSQL->bindParam(':formulario', $txtFormulario);
      $sentenciaSQL->bindParam(':id_animal', $txtIDAnimal); 

      $fecha= new DateTime();
      $tmpFormulario = $_FILES["txtFormulario"]["tmp_name"];
      
      if ($_FILES["txtFormulario"]["tmp_name"] != "") {
          $nombreArchivo = $fecha->getTimestamp() . "_" . $_FILES["txtFormulario"]["name"];
          move_uploaded_file($tmpFormulario, "document/" . $nombreArchivo);
          $sentenciaSQL->bindParam(':formulario', $nombreArchivo);
      }
      $sentenciaSQL->bindParam(':formulario', $nombreArchivo);

      $sentenciaSQL->execute();   
      $centroID = $_GET['centroID']; // obtener el valor de centroID de la URL
      $animalID = $_GET['id_animal'];
      $redireccion = "formAdopcion.php?centroID=" . $centroID . "&id_animal=" . $animalID;

      header("Location: " . $redireccion); // Redirecciona a la URL generada
      break;
    }


if (isset($_GET['centroID'])) {
    $centroID = $_GET['centroID'];

    $sentenciaSQL = $conexion->prepare("SELECT formulario FROM adopcion WHERE id_usuario = :centroID");
    $sentenciaSQL->bindParam(':centroID', $centroID);
    $sentenciaSQL->execute();
    $adopcion = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);

    if ($adopcion) {
        $nombreArchivo = $adopcion['formulario'];
        $rutaArchivo = "../admin/document/" . $nombreArchivo; // Ruta completa al archivo PDF

        // Verificar si el archivo existe antes de mostrar el enlace de descarga
        if (file_exists($rutaArchivo)) {
            ?>
            <div class="container-tabla">
                <div class="blue-section"></div>
                <div class="transparent-section">
                    <div class="col-md-12 text-center">
                        <h2>Gracias por adoptar. En nombre de todo el equipo del Centro</h2>
                        <h3 style="margin-bottom: 20px;">Queremos expresar nuestro más sincero agradecimiento por haber decidido adoptar a uno de nuestros adorables compañeros. Es un momento de gran alegría y gratitud para nosotros saber que ha brindado un hogar amoroso a un animal necesitado.</h3>
                        <p>Como parte del proceso de adopción, queremos informarle acerca de las condiciones y compromisos asociados con esta responsabilidad. En primer lugar, nos complace saber que está de acuerdo con permitir que el centro monitoree el bienestar del animal durante el tiempo que consideremos necesario. Este seguimiento periódico es esencial para garantizar que el animal esté recibiendo el cuidado adecuado y se adapte de manera positiva a su nuevo entorno.</p>
                        <p>Entendemos que los animales adoptados requieren un período de adaptación y, en ocasiones, pueden surgir desafíos imprevistos. Por lo tanto, nos reservamos el derecho de reconsiderar la adopción si detectamos alguna situación en la que el bienestar del animal se vea comprometido. Sin embargo, queremos enfatizar que esta medida se toma únicamente para proteger al animal y asegurar su seguridad y felicidad en todo momento.</p>
                        <p>Le pedimos que considere estas condiciones como una forma de mantener una comunicación abierta y honesta con el centro de adopción. Estamos aquí para brindarle apoyo y asesoramiento en cualquier momento que lo necesite. Nuestro objetivo principal es garantizar el bienestar a largo plazo del animal y asegurarnos de que haya encontrado un hogar donde sea amado y cuidado de manera adecuada.</p>
                        <p>Valoramos enormemente su compromiso y dedicación al adoptar a uno de nuestros animales. La decisión de abrir su hogar a un compañero peludo demuestra su bondad y generosidad. Queremos asegurarle que su gesto no solo ha cambiado la vida del animal que ha adoptado, sino que también ha liberado espacio y recursos en nuestro centro para ayudar a otros animales necesitados.</p>
                        <p>En nombre de todo el equipo,</p>
                        <p><a href="<?php echo $rutaArchivo; ?>" target="_blank">Descargar PDF</a></p>
                    </div>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="txtFormulario">Formulario:</label>
                            <br>
                            <?php if ($txtFormulario != "") { ?>
                                <img class="img-thumbnail rounded" src="document/<?php echo $txtFormulario; ?>" width="50" alt="" srcset="">
                            <?php } ?>
                            <input type="file" class="form-control" name="txtFormulario" id="txtFormulario" placeholder="Ingrese el formulario">
                        </div><br>
                        <div class="text-center">
                            <button type="submit" name="accion" <?php echo ($accion == "Seleccionar") ? "disabled" : ""; ?> value="Agregar" class="btn btn-info">Enviar formulario</button>
                        </div>
                    </form>
                </div>
                <div class="blue-section" style="width: 20%;"></div>
            </div>
            <?php
        } else {
            echo "El archivo no existe.";
        }
    } else {
        echo "No se encontró información de adopción para este centro.";
    }
} 
?>

<?php
include("template/pie.php");
?>
