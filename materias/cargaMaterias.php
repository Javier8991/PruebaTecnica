<?php 
include '../includes/funciones.php';

$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

$pagina = $_GET['type'] ?? null;
$idUser = $_GET['id'] ?? null;
$idUser = filter_var($idUser,FILTER_VALIDATE_INT);
$msg = $_GET['msg'] ?? null;
$del = $_GET['del'] ?? null;

require '../includes/config/database.php';
$db = conectarDb();

// Consulta para cargar materias
$tabla = ucfirst($pagina);
$query = "SELECT a.ID, a.nombre FROM asignatura a;";
$resultado = mysqli_query($db,$query);
$cons = "SELECT x.Asignatura_ID FROM ${pagina}_asignatura x WHERE x.${tabla}_ID='${idUser}';";
$res = mysqli_query($db,$cons);
$idMaterias = [];
while($row = mysqli_fetch_assoc($res)) {
    $idMaterias[] = $row['Asignatura_ID'];
}

$errores=[];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    $idAsignatura = $_POST['materia'];
    $idAsignatura = filter_var($idAsignatura,FILTER_VALIDATE_INT);

    if(!$idAsignatura) {
        $errores[] = "Debe de seleccionar una materia.";
    }

    if(empty($errores)) {
        if($del==1){
            $query = "DELETE FROM ${pagina}_asignatura WHERE Asignatura_ID = '${idAsignatura}' AND ${tabla}_ID='${idUser}'; ";
            $resultado = mysqli_query($db,$query) or die(mysqli_error($db));
            $msg = 2;
        } else {
            $query = "INSERT INTO ${pagina}_asignatura(${tabla}_ID,Asignatura_ID) VALUES ('${idUser}','${idAsignatura}');";
            $resultado = mysqli_query($db,$query) or die(mysqli_error($db));
            $msg = 1;
        }
        
        if($resultado) {
            header('Location: cargaMaterias.php?type='.$pagina.'&id='.$idUser.'&msg='.$msg);
        }
    }
}

incluirTemplate('header');
?>


<h1><?php echo $del==1 ? 'Eliminar' : 'Añadir' ?> Materias</h1>


<main class="contenedor seccion contenido-centrado">
    <?php
        if($msg == 1) {
            echo '<p class="alerta exito">Materia Cargada Correctamente</p>';
        }elseif($msg == 2) {
            echo '<p class="alerta exito">Materia Descargada Correctamente</p>';
        }
    ?>
    <a href="../usuarios/actualizar.php?type=<?php echo $pagina ?>&id=<?php echo $idUser ?>" class="boton btn-principal">Volver</a>

    <form class="formulario" method="POST">
        <fieldset>
        <legend>Materias Disponibles</legend>
        <label for="materia">Materia:</label>
            <select name="materia">
                <option value="" selected disabled>--Seleccione una opción--</option>
                <?php while($row = mysqli_fetch_assoc($resultado)):?>
                    <?php if($del==1):?>
                        <?php if(in_array($row['ID'],$idMaterias)):?>
                        <option value="<?php echo $row['ID']?>"><?php echo $row['nombre']?></option>
                        <?php endif;?>
                    <?php else:?>
                        <?php if(!in_array($row['ID'],$idMaterias)):?>
                        <option value="<?php echo $row['ID']?>"><?php echo $row['nombre']?></option>
                        <?php endif;?>
                    <?php endif;?>
                <?php endwhile;?>
            </select>
        </fieldset>

        <input type="submit" value="<?php echo $del==1 ? 'Eliminar' : 'Añadir' ?>" class="boton btn-secundario">
    </form>
</main>

<?php
incluirTemplate('footer');
mysqli_close($db);
?>