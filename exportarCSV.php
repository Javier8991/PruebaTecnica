<?php 
require 'includes/config/database.php';
$db = conectarDb();

$type = $_GET['type'];
$type = mysqli_real_escape_string($db,$type);
$column = ucfirst($type);
$complemento = $type == "alumno" ? "a.apellido_paterno, a.apellido_materno," : '';
$query = "SELECT a.nombre,".$complemento."a.matricula, y.nombre AS materia,y.abreviacion FROM ${type} a JOIN ${type}_asignatura x ON x.${column}_ID = a.ID JOIN asignatura y ON x.Asignatura_ID = y.ID;";
$resultado = mysqli_query($db,$query);

if($resultado->num_rows) {
    $delimitador = ",";
    $fileName = $type."_". date('d-m-Y').".csv";

    // Puntero de archivo
    $file = fopen('php://memory', 'w');

    // Configurar cabeceras de archivo
    if($type=="alumno"){
        $cabeceras = ['Nombre','Apellido_Paterno', 'Apellido_Materno', 'Matricula', 'Materia', 'Abreviacion'];
    }else {
        $cabeceras = ['Nombre', 'Matricula', 'Materia', 'Abreviacion'];
    }

    fputcsv($file,$cabeceras,$delimitador);

    // generar cada fila de los datos, formatear la línea como csv y escribir en el puntero del archivo
    while($row = mysqli_fetch_assoc($resultado)){
        if($type=="alumno") {
            $datos = [$row['nombre'],$row['apellido_paterno'],$row['apellido_materno'],$row['matricula'],$row['materia'],$row['abreviacion']];
        }else {
            $datos = [$row['nombre'],$row['matricula'],$row['materia'],$row['abreviacion']];
        }
        
        fputcsv($file,$datos,$delimitador);
    }

    // Inicio del archivo
    fseek($file,0);

    // establecer encabezados para descargar archivos en lugar de mostrarlos
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '";');

    // Salida de todos los datos restantes en un puntero de archivo
    fpassthru($file);
    // echo "<pre>";
    // var_dump($cabeceras);
    // echo "</pre>";
}
mysqli_close($db);
exit;
?>