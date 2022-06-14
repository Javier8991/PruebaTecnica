<?php
require 'includes/config/database.php';
$db=conectarDb();

include 'includes/funciones.php';
$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

if(isset($_GET)){
    // ID de asignatura
    $id = $_GET['id'] ?? null;
    // Usuario 2 (Docente)
    $user = $_GET['user'] ?? null;
    // Nombre de la materia
    $mat = $_GET['mat'] ?? null;
}

$query = "SELECT a.ID, a.nombre, a.apellido_paterno, a.apellido_materno, a.matricula FROM alumno a join alumno_asignatura x ON a.ID=x.Alumno_ID WHERE x.Asignatura_ID= '${id}';";
$resultado = mysqli_query($db,$query);

incluirTemplate('header');
?>

<main class="contenedor seccion">
    <h1><?php echo $mat ?></h1>

    <a href="materias.php?user=<?php echo $user?>" class="boton btn-principal">Volver</a>

    <table class="datos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Matricula</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultado)):?>
            <tr>
                <td><?php echo $row['ID']?></td>
                <td><?php echo $row['nombre']." ".$row['apellido_paterno']." ".$row['apellido_materno']?></td>
                <td><?php echo $row['matricula']?></td>
                <td>
                    <a href="usuarios/actualizar.php?type=alumno&id=<?php echo $row['ID']?>&p=0&idmateria=<?php echo $id?>&mat=<?php echo $mat?>&user=<?php echo $user?>" class="boton btn-naranja">Actualizar</a>
                </td>
            </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>