<?php

    if(!isset($_SESSION)) {
        session_start();
    }

    $auth = $_SESSION['login'] ?? false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAEH</title>
    <link rel="stylesheet" href="/build/css/app.css">
</head>


<body>
    <header class="header">
        <div class="contenedor contenido-header">
            <img src="https://www.uaeh.edu.mx/images/uaeh.png" alt="Logo UAEH" class="logo">
            <?php if($auth):?>
                <div>
                    <span><?php echo strtoupper($_SESSION['rol']) .": ". ucfirst($_SESSION['nombre'])?></span>
                    <span>Número de empleado: <?php echo $_SESSION['rol']==='director' ? $_SESSION['numero_empleado']: $_SESSION['matricula']?></span>
                </div>
            <?php endif; ?>
            <?php if(!$auth): ?>
            <h1>Universidad Autonoma del Estado de Hidalgo</h1>
            <?php endif; ?>
        </div>
    </header>