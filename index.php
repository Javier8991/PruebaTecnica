<?php

// Conexión BD
require 'includes/config/database.php';
$db = conectarDb();

$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";

    $usuario = mysqli_real_escape_string($db,$_POST['usuario']);
    $password = mysqli_real_escape_string($db,$_POST['password']);
    $rol = mysqli_real_escape_string($db,$_POST['rol']) ?? '';

    if(!$usuario) {
        $errores[] = 'Verifique el usuario.';
    }

    if(!$password) {
        $errores[] = 'Verifique su contraseña.';
    }

    if(!$rol) {
        $errores[] = 'Asegurese de seleccionar el tipo de usuario';
    }

    if(empty($errores)) {
        // Revisar si el usuario existe
        if($rol === "director") {
            $query = "SELECT * FROM ${rol} WHERE numero_empleado = '${usuario}';";
        }else {
            $query = "SELECT * FROM ${rol} WHERE matricula = ${usuario};";
        }
        $resultado = mysqli_query($db, $query);

        // El usuario existe
        if($resultado->num_rows) {
            // Verificar contraseña
            $usuario = mysqli_fetch_assoc($resultado);
            
            // Contraseña a verificar y el de la BD
            $auth = password_verify($password,$usuario['contrasena']);

            // echo "<pre>";
            // var_dump($usuario);
            // echo "</pre>";

            if($auth) {
                // Autenticado
                session_start();
                if(strtolower($rol) === "director"){
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['numero_empleado'] = $usuario['numero_empleado'];
                    $_SESSION['rol'] = $rol;
                    $_SESSION['login'] = true;

                    header('Location: indexDirectivos.php');
                } else if(strtolower($rol) === "docente"){
                    $_SESSION['ID'] = $usuario['ID'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['matricula'] = $usuario['matricula'];
                    $_SESSION['rol'] = $rol;
                    $_SESSION['login'] = true;

                    header('Location: materias.php?user=2');
                }else if(strtolower($rol)==="alumno") {
                    $_SESSION['ID']=$usuario['ID'];
                    $_SESSION['nombre']=$usuario['nombre'];
                    $_SESSION['apellido_paterno']=$usuario['apellido_paterno'];
                    $_SESSION['apellido_materno']=$usuario['apellido_materno'];
                    $_SESSION['matricula'] = $usuario['matricula'];
                    $_SESSION['rol'] = $rol;
                    $_SESSION['login'] = true;

                    header('Location: materias.php?user=3');
                }
            } else {
                $errores[] = 'La contraseña es incorrecta.';
            }
        } else {
            $errores[] = 'El usuario no existe';
        }
    }
}
include 'includes/funciones.php';
incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h2>Iniciar Sesión</h2>

    <?php foreach($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
    
    <form method="POST" class="formulario">
        <fieldset>
            <legend>Usuario y Contraseña</legend>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario">
            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <label for="rol">Tipo de usuario:</label>
            <select name="rol">
                <option value="" selected disabled>--Seleccione una opción--</option>
                <option value="director">Directivo</option>
                <option value="docente">Docente</option>
                <option value="alumno">Alumno</option>
            </select>
        </fieldset>
        <input type="submit" value="Iniciar Sesión" class="boton btn-principal">
    </form>
</main>

<?php
incluirTemplate('footer');
?>