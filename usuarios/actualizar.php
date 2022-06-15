<?php
include '../includes/funciones.php';

$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

$rol = $_SESSION['rol'] ?? null;
$pagina = $_GET['type'] ?? null;
$idUser = $_GET['id'] ?? null;
$idUser = filter_var($idUser,FILTER_VALIDATE_INT);
$permiso = $_GET['p'] ?? null;
$permiso = filter_var($permiso,FILTER_VALIDATE_INT);

// Variables para regresar a alumnoMateria.php
// ID de materia
$id = $_GET['idmateria'] ?? null;
// Nombre de materia
$materia = $_GET['mat'] ?? null;
// Tipo de usuario
$tipo = $_GET['user'] ?? null;

if(!$idUser) {
    header('Location: ../datos.php?page='.$pagina);
}

require '../includes/config/database.php';
$db = conectarDb();

// Obtener datos de tipo de usuario y usuario
$query = "SELECT * FROM ${pagina} WHERE ID = ${idUser};";
$resultado = mysqli_query($db,$query);
$user = mysqli_fetch_assoc($resultado);

// Obtener datos de las materias
if($rol==="director") {
    $cons = "SELECT a.ID, a.nombre FROM asignatura a;";
    $res = mysqli_query($db,$cons);
    $mayus = ucfirst($pagina);
    $cons2 = "SELECT x.Asignatura_ID FROM ${pagina}_asignatura x WHERE x.${mayus}_ID='${idUser}';";
    $res2 = mysqli_query($db,$cons2);
    $idMaterias = [];
    while($row = mysqli_fetch_assoc($res2)) {
        $idMaterias[] = $row['Asignatura_ID'];
    }
}

// Validar datos
$errores = [];
if($pagina==="docente") {
    $arregloNombre = explode(" ",$user['nombre']);
    if(count($arregloNombre)===4) {
        $nombreCompleto = $arregloNombre[0]." ".$arregloNombre[1];
        $nombre = $nombreCompleto;
        $apellidoPaterno = $pagina!== "docente" ? $user['apellido_paterno']: $arregloNombre[2];
        $apellidoMaterno = $pagina!== "docente" ? $user['apellido_materno'] : $arregloNombre[3];
    }elseif(count($arregloNombre)===3) {
        $nombreCompleto = $arregloNombre[0];
        $nombre = $nombreCompleto;
        $apellidoPaterno = $pagina!== "docente" ? $user['apellido_paterno']: $arregloNombre[1];
        $apellidoMaterno = $pagina!== "docente" ? $user['apellido_materno'] : $arregloNombre[2];
    }
}else {
    $nombre = $user['nombre'];
    $apellidoPaterno = $pagina!== "docente" ? $user['apellido_paterno']: '';
    $apellidoMaterno = $pagina!== "docente" ? $user['apellido_materno'] : '';
}
$matricula = $user['matricula'];
$password = '';


// Proceso al presionar botón Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    $nombre = $_POST['nombre'];
    $apellidoPaterno = $_POST['apellidoP'];
    $apellidoMaterno = $_POST['apellidoM'];
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];

    $nombre = mysqli_real_escape_string($db,$nombre);
    $apellidoPaterno = mysqli_real_escape_string($db,$apellidoPaterno);
    $apellidoMaterno = mysqli_real_escape_string($db,$apellidoMaterno);
    $password = mysqli_real_escape_string($db,$password);
    $matricula = filter_var($matricula,FILTER_VALIDATE_INT);

    if(!$nombre) {
        $errores = 'El nombre es obligatorio.';
    }
    if(!$apellidoPaterno) {
        $errores = 'El apellido paterno es obligatorio.';
    }
    if(!$apellidoMaterno) {
        $errores = 'El apellido materno es obligatorio.';
    }
    if(!$matricula) {
        $errores = 'La matricula es obligatoria.';
    }
    if(!$password) {
        $errores = 'La contraseña es obligatoria.';
    }

    $passHash = password_hash($password,PASSWORD_DEFAULT);

    if(empty($errores)) {
        if($pagina === 'docente') {
            $nombre = $nombre." ".$apellidoPaterno." ".$apellidoMaterno;
            $query = "UPDATE ${pagina} SET nombre='${nombre}',matricula='${matricula}',contrasena='${passHash}',Instituto_ID='1' WHERE id = ${idUser};";
        }else {
            $query = "UPDATE ${pagina} SET nombre='${nombre}',apellido_paterno='${apellidoPaterno}',apellido_materno='${apellidoMaterno}',matricula='${matricula}',contrasena='${passHash}',Instituto_ID='1' WHERE id = ${idUser};";
        }


        $resultado = mysqli_query($db,$query) or die(mysqli_error($db));

        if($resultado) {
            header('Location: ../datos.php?msg=2&page='.$pagina);
        }
    }
}

incluirTemplate('header');
?>

<h1>Editar <?php echo ucfirst($pagina)?></h1>

<main class="contenedor seccion contenido-centrado">
    <?php if($permiso===0): ?>
        <a href="../alumnoMateria.php?id=<?php echo $id?>&user=<?php echo $tipo ?>&mat=<?php echo $materia?>" class="boton btn-principal">Volver</a>
    <?php else: ?>
        <a href="../datos.php?page=<?php echo $pagina ?>" class="boton btn-principal">Volver</a>
        <a href="../materias/cargaMaterias.php?type=<?php echo $pagina?>&id=<?php echo $idUser?>" class="boton btn-secundario">Añadir Materias</a>
        <a href="../materias/cargaMaterias.php?type=<?php echo $pagina?>&id=<?php echo $idUser?>&del=1" class="boton btn-principal">Eliminar Materias</a>
    <?php endif;?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <fieldset>
        <legend>Información del Usuario</legend>
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre del Usuario" value="<?php echo $nombre ?>">
            <label for="apellidoP">Apellido Paterno:</label>
            <input type="text" name="apellidoP" id="apellidoP" placeholder="Apellido Paterno del Usuario" value="<?php echo $apellidoPaterno ?>">
            <label for="apellidoM">Apellido Materno:</label>
            <input type="text" name="apellidoM" id="apellidoM" placeholder="Apellido Materno del Usuario" value="<?php echo $apellidoMaterno ?>">
            <label for="matricula">Matricula:</label>
            <input type="number" name="matricula" id="matricula" placeholder="Matricula del Usuario" value="<?php echo $matricula ?>">
            <?php if($permiso!==0): ?>
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" placeholder="Contraseña del Usuario" value="<?php echo $password ?>">
            <?php endif;?>
        </fieldset>
        <?php if($rol == "director"):?>
            <fieldset>
                <legend>Materias</legend>
                <?php if($pagina=="docente"): ?>
                    <p>Las materias que actualmente imparte el docente son:</p>
                <?php else: ?>
                    <p>Las materias que actualmente cursa el alumno son:</p>
                <?php endif; ?>
                <div class="forma-contacto">
                    <?php while($row = mysqli_fetch_assoc($res)):?>
                        <label for="<?php echo $row['nombre'] ?>"><?php echo $row['nombre'] ?></label>
                        <input type="checkbox" 
                            name="<?php echo $row['nombre'] ?>" 
                            value="<?php echo $row['ID']?>" 
                            id="<?php echo $row['nombre'] ?>" 
                            <?php foreach($idMaterias as $materia) {if($materia==$row['ID']){
                                echo "checked";
                            }}?>>
                    <?php endwhile;?>
                </div>
            </fieldset>
        <?php endif?>

        <input type="submit" value="Actualizar Usuario" class="boton btn-secundario">
    </form>
</main>

<?php
incluirTemplate('footer');
mysqli_close($db);
?>