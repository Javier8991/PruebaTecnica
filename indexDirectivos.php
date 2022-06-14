<?php 
include 'includes/funciones.php';

$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

incluirTemplate('header');

?>

<main class="contenedor seccion">
    
    <div class="btn-centrados">
        <a href="datos.php?page=alumno" class="boton btn-naranja">Alumnos</a>
        <a href="datos.php?page=docente" class="boton btn-naranja">Docentes</a>
        <a href="materias.php" class="boton btn-naranja">Materias</a>
        <a href="/cerrarSesion.php" class="boton btn-naranja">Cerrar Sesión</a>
    </div>
</main>

<?php
incluirTemplate('footer');
?>