<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pagina</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="img/patas.jpg" type="image/x-icon">
    <link rel="stylesheet" href="./css/estilo.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    
</head>
<body>
    <header>
    <?php $url="http://".$_SERVER['HTTP_HOST']."/proyectofinal"?>
        <nav>
            <a href="<?php echo $url;?>/cliente/index.php">Inicio</a>
            <a href="<?php echo $url;?>/cliente/ubicacion.php">Busca los centros</a>
            <a href="<?php echo $url;?>/cliente/index.php">Eliminar perfil</a>
            <a href="<?php echo $url;?>/cliente/cerrar.php">Cerrar sesion</a>
        </nav>
            <section class="textos-header">
                <h1>Bienvenido a patitas a casa</h1>
                <h2>Busca el centro mas cercano</h2>
                <h2>Encuentra a un nuevo miembro para tu familia</h2>
            </section>
            <div class="wave" style="height: 150px; overflow: hidden;" ><svg viewBox="0 0 500 150" preserveAspectRatio="none" style="height: 100%; width: 100%;"><path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z" 
                style="stroke: none; fill: #fff;"></path></svg></div>
    </header>