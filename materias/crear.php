<?php
include '../includes/funciones.php';

$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

require '../includes/config/database.php';
$db = conectarDb();

$errores = [];

$nombre = '';
$abreviacion = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $abreviacion = $_POST['abreviacion'];

    $nombre = mysqli_real_escape_string($db,$nombre);
    $abreviacion = mysqli_real_escape_string($db,$abreviacion);

    if(!$nombre) {
        $errores = 'El nombre de la asignatura es obligatorio.';
    }
    if(!$abreviacion) {
        $errores = 'La abreviación de la asignatura es obligatorio.';
    }

    $query = "INSERT INTO asignatura(nombre,abreviacion,Instituto_ID) VALUES('$nombre','$abreviacion','1')";
    
    echo $query;

    $resultado = mysqli_query($db,$query) or die(mysqli_error($db));

    if($resultado) {
        header('Location: ../materias.php?msg=1');
    }
}

incluirTemplate('header');
?>

<h1>Nueva Materia</h1>

<main class="contenedor seccion contenido-centrado">
    <a href="../materias.php" class="boton btn-principal">Volver</a>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Información de Materia</legend>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre de la materia">
            <label for="abreviacion">Abreviación:</label>
            <input type="text" name="abreviacion" id="abreviacion" placeholder="Abreviacion de Materia">
        </fieldset>

        <input type="submit" value="Crear Materia" class="boton btn-secundario">
    </form>
</main>

<?php
incluirTemplate('footer');
?>