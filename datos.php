<?php
require 'includes/config/database.php';
$db = conectarDb();

include 'includes/funciones.php';
$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

// verificar la página
if(isset($_GET)){
    $pagina = ucfirst($_GET['page']) ?? null;
    $msg = $_GET['msg'] ?? null;
}

$query = "SELECT * FROM ${pagina}";
$resultado = mysqli_query($db,$query);

if($_SERVER['REQUEST_METHOD']==='POST') {
    $id = $_POST['id_eliminar'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    $pagina = strtolower($pagina);
    $query = "DELETE FROM ${pagina} WHERE id = '${id}'";
    $resultado = mysqli_query($db,$query) or die(mysqli_error($db));

    // if($pagina==="docente"){
    //     $cons = "DELETE FROM docente_asignatura WHERE Docente_ID = '${id}'";
    //     $res = mysqli_query($db,$cons) or die(mysqli_error($db));
    // }

    if($resultado /*&& $res*/) {
        header('Location: datos.php?msg=3&page='.$pagina);
    }
}

incluirTemplate('header');

?>

<main class="contenedor seccion">
    <h1><?php echo $pagina ?></h1>

    <?php 
        if($msg == 1) {
            echo '<p class="alerta exito">Usuario Creado Correctamente</p>';
        }else if($msg == 2) {
            echo '<p class="alerta exito">Usuario Actualizado Correctamente</p>';
        }else if($msg == 3) {
            echo '<p class="alerta exito">Usuario Eliminado Correctamente</p>';
        }
    ?>
    
    <a href="indexDirectivos.php" class="boton btn-principal">Volver</a>
    <a href="usuarios/crear.php?type=<?php echo strtolower($pagina)?>" class="boton btn-secundario">Nuevo <?php echo $pagina?></a>
    <a href="#" class="boton btn-secundario">Generar CSV</a>
    <a href="#" class="boton btn-secundario">Generar PDF</a>

    <table class="datos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Matricula</th>
                <th>Materias</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while($row = mysqli_fetch_assoc($resultado)): ?>
            <tr>
                <td><?php echo $row['ID']; ?></td>
                <td><?php echo $row['nombre'] ;
                if($pagina==="Alumno") {
                    echo " ".$row['apellido_paterno']." ".$row['apellido_materno'];
                }
                ?></td>
                <td><?php echo $row['matricula']; ?></td>
                <td><?php 
                    if($pagina==="Docente"){
                        $consulta = "SELECT a.nombre FROM docente_asignatura x JOIN asignatura a ON a.ID=x.Asignatura_ID WHERE x.Docente_ID= '${row['ID']}';";
                    }else {
                        $consulta = "SELECT a.nombre FROM alumno_asignatura x JOIN asignatura a ON a.ID=x.Asignatura_ID WHERE x.Alumno_ID= '${row['ID']}';";
                    }
                    
                    $res = mysqli_query($db,$consulta);
                    
                    while($fila = mysqli_fetch_assoc($res)): ?>
                        <p><?php echo $fila['nombre']?></p>
                    <?php endwhile;?>
                </td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_eliminar" value="<?php echo $row['ID'] ?>">
                        <input type="submit" class="boton btn-gris"
                        value="Borrar">
                    </form>

                    <a href="usuarios/actualizar.php?type=<?php echo strtolower($pagina)."&id=".$row['ID']?>" class="boton btn-naranja">Actualizar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php
incluirTemplate('footer');
?>