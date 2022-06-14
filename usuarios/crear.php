<?php
include '../includes/funciones.php';

$auth = autenticado();
if(!$auth) {
    header('Location: /');
}

$pagina = $_GET['type'] ?? null;

require '../includes/config/database.php';
$db = conectarDb();

$errores = [];

$nombre = '';
$apellidoPaterno = '';
$apellidoMaterno = '';
$matricula = '';
$password = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if($pagina === 'docente') {
        $nombre = $nombre." ".$apellidoPaterno." ".$apellidoMaterno;
        $query = "INSERT INTO ${pagina}(nombre,matricula,contrasena,Instituto_ID) VALUES('$nombre','$matricula','$passHash','1');";
    }else {
        $query = "INSERT INTO ${pagina}(nombre,apellido_paterno,apellido_materno,matricula,contrasena,Instituto_ID) VALUES('$nombre','$apellidoPaterno','$apellidoMaterno','$matricula','$passHash','1');";
    }    
    $resultado = mysqli_query($db,$query) or die(mysqli_error($db));

    if($resultado) {
        header('Location: ../datos.php?msg=1&page='.$pagina);
    }
}

incluirTemplate('header');
?>

<h1>Nuevo <?php echo ucfirst($pagina)?></h1>

<main class="contenedor seccion contenido-centrado">
    <a href="../datos.php?page=<?php echo $pagina ?>" class="boton btn-principal">Volver</a>

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
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" placeholder="Contraseña del Usuario" value="<?php echo $password ?>">
        </fieldset>

        <input type="submit" value="Crear Usuario" class="boton btn-secundario">
    </form>
</main>

<?php
incluirTemplate('footer');
mysqli_close($db);
?>