<?php
include("template/cabecera.php");

$accion=(isset($_POST['accion']))?$_POST['accion']:"";


switch($accion){

    case"Ubicacion":
         
        header("Location:ubicacion.php");
        break;
    }
?>

<section class="contenedor sobre-nosotros">
    <h2 class="titulo">Que hacer</h2>
    <div class="contenedor-sobre-nosotros">
        <img src="img/im2.jpg" alt="" class="imagen-about-us">
        <div class="contenido-textos">
            <h3><span>1</span> Adopta a un amigo peludo </h3>
            <p>es una decisión hermosa y significativa que implica brindarle un hogar amoroso a un animalito que necesita cuidado y protección</p>
            <h3><span>2</span> Comunicate con los centros </h3>
            <p>Antes de adoptar e incluso despues de hacerlo es importante informarse sobre lo mas importante en este tema, por lo que mantener la comunicacion con los mismos centros puede ser de ayuda</p>
            <h3><span>3</span> Realiza donaciones </h3>
            <p>Si no puedes adoptar pero quieres ayudar o incluso si tu atencion fue eficiente, donar siempre podra ser una opcion </p>
            <h3><span>4</span> Encuentra los centros mas cercanos a tu ubicacion</h3>
            <p>Dale click al boton para que encuentres los centros que esten registrados alrededor tuyo</p>
            <form method="POST" enctype="multipart/form-data">
                <div class="boton">
                <button type="submit" name="accion" value="Ubicacion" >Buscar</button>
                </div><br>
            </form>
        </div>
    </div>
</section>


<?php
include("template/pie.php");
?>