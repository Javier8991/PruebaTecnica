<?php
require 'includes/config/database.php';
$db = conectarDb();

include 'includes/funciones.php';
$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

$idUser = $_SESSION['ID'] ?? null;
$msg = $_GET['msg'] ?? null;
$user = $_GET['user'] ?? null;

if($user==='2') {
    $query = "SELECT a.ID, a.nombre, a.abreviacion FROM docente_asignatura x JOIN asignatura a ON a.ID=x.Asignatura_ID WHERE x.Docente_ID= '${idUser}'";
}else if($user==='3') {
    $query = "SELECT a.ID, a.nombre, a.abreviacion FROM alumno_asignatura x JOIN asignatura a ON a.ID=x.Asignatura_ID WHERE x.Alumno_ID= '${idUser}'";
}else {
    $query = "SELECT * FROM asignatura;";
}

$resultado = mysqli_query($db, $query);

if($_SERVER['REQUEST_METHOD']==='POST') {
    $id = $_POST['id_eliminar'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $query = "DELETE FROM asignatura WHERE id = '${id}'";
    $resultado = mysqli_query($db,$query) or die(mysqli_error($db));

    if($resultado) {
        header('Location: materias.php?msg=3');
    }
}

incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1>Materias</h1>

    <?php 
        if($msg == 1) {
            echo '<p class="alerta exito">Materia Creada Correctamente</p>';
        }else if($msg == 2) {
            echo '<p class="alerta exito">Materia Actualizada Correctamente</p>';
        }
    ?>
    
    <?php if($user!=="2" && $user!=="3"): ?>
        <a href="materias/crear.php" class="boton btn-secundario">Nueva Materia</a>
        <a href="indexDirectivos.php" class="boton btn-principal">Volver</a>
    <?php else:?>
        <a href="/cerrarSesion.php" class="boton btn-principal">Cerrar Sesión</a>
    <?php endif;?>
    <table class="datos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Abreviación</th>
                <?php if($user!=="2"&&$user!=="3"):?>
                <th>Acciones</th>
                <?php elseif($user==="2"&&$user!=="3"):?>
                <th>Alumnos</th>
                <?php endif;?>
            </tr>
        </thead>

        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultado)):?>
            <tr>
                <td><?php echo $row['ID']?></td>
                <td><?php echo $row['nombre']?></td>
                <td><?php echo $row['abreviacion']?></td>
                <?php if($user!=="2" && $user!=="3" ):?>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_eliminar" value="<?php echo $row['ID']?>">
                        <input type="submit" class="boton btn-gris"
                        value="Borrar">
                    </form>

                    <a href="materias/actualizar.php?id=<?php echo $row['ID']?>" class="boton btn-naranja">Actualizar</a>
                </td>
                <?php elseif($user==="2"&&$user!=="3"):?>
                <td>
                    <a href="alumnoMateria.php?id=<?php echo $row['ID']?>&user=<?php echo $user?>&mat=<?php echo $row['nombre']?>" class="boton btn-naranja">Ver Alumnos</a>
                    </td>
                <?php endif;?>
            </tr>
            <?php endwhile;?>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>