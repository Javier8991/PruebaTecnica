<?php 
    require 'fpdf/fpdf.php';
    require 'includes/config/database.php';
    $db = conectarDb();

    $pagina = $_GET['type'] ?? null;
    $pagina = mysqli_real_escape_string($db,$pagina);
    $column = ucfirst($pagina);

    $pdf = new FPDF();
    $pdf->AliasNbPages();
    $pdf-> AddPage();
    // Header
    $pdf->SetFont('Arial','B',15);
    $pdf->Image('build/img/uaeh.png',15,15,30);
    $pdf->Cell(30);
    $pdf->Cell(120,20,'Reporte de '.$column.'s',0,0,'C');
    $pdf->Ln(35);

    // Body
    $pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(60,6,'Nombre',1,0,'C',1);
	$pdf->Cell(40,6,'Matricula',1,0,'C',1);
	$pdf->Cell(40,6,'Materia',1,0,'C',1);
	$pdf->Cell(40,6,'Abreviacion',1,1,'C',1);
	
	$pdf->SetFont('Arial','',10);

    // Llenado de celdas con los datos
    $complemento = $pagina == "alumno" ? "a.apellido_paterno, a.apellido_materno," : '';
    $query = "SELECT a.nombre,".$complemento."a.matricula, y.nombre AS materia,y.abreviacion FROM ${pagina} a JOIN ${pagina}_asignatura x ON x.${column}_ID = a.ID JOIN asignatura y ON x.Asignatura_ID = y.ID;";
    $resultado = mysqli_query($db,$query);

    $prevNom = '';
    $prevMat = '';
    while($row = mysqli_fetch_assoc($resultado)) {
        $apellidos = $pagina == "alumno"? " ".$row['apellido_paterno']." ".$row['apellido_materno'] : '';
		$pdf->Cell(60,6,$row['nombre']!=$prevNom ? $row['nombre'].$apellidos : '',1,0,'C');
		$pdf->Cell(40,6,$row['matricula']!=$prevMat ? $row['matricula'] : '',1,0,'C');
		$pdf->Cell(40,6,utf8_decode($row['materia']),1,0,'C');
		$pdf->Cell(40,6,utf8_decode($row['abreviacion']),1,1,'C');
        $prevNom = $row['nombre'];
        $prevMat = $row['matricula'];
	}

    // Footer
    $pdf->SetY(260);
    $pdf->SetFont('Arial','I',8);
    $pdf->Cell(0,10,'Pagina'.$pdf->PageNo().'/{nb}',0,0,'C');

    $pdf->Output();
?>