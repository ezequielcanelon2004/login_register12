<?php

// Incluir el archivo de conexión
require_once '../../modelo/conexion.php';

// Validar la conexión
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Recibir y validar todos los parámetros del formulario avanzado
$fechaInicio = isset($_GET["fecha_inicio"]) ? $_GET["fecha_inicio"] : null;
$fechaFinal = isset($_GET["fecha_fin"]) ? $_GET["fecha_fin"] : null;
$empleado = isset($_GET["empleado"]) ? $_GET["empleado"] : null;
$cargo = isset($_GET["cargo"]) ? $_GET["cargo"] : null;
$tipo = isset($_GET["tipo"]) ? $_GET["tipo"] : null;
$turno = isset($_GET["turno"]) ? $_GET["turno"] : null;

$whereClauses = [];
if ($fechaInicio && $fechaFinal) {
    $whereClauses[] = "entrada BETWEEN '$fechaInicio' AND '$fechaFinal'";
}
// Resolver ambigüedad en la columna id_empleado
if ($empleado) {
    $whereClauses[] = "asistencia.id_empleado = '$empleado'";
}
// Resolver ambigüedad en la columna id_cargo
if ($cargo) {
    $whereClauses[] = "cargo.id_cargo = '$cargo'";
}
if ($tipo) {
    $whereClauses[] = "tipo_asistencia = '$tipo'";
}
if ($turno) {
    $whereClauses[] = "turno = '$turno'";
}

$whereSQL = '';
if (count($whereClauses) > 0) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

// Construir la consulta SQL
$query = "SELECT asistencia.*, empleado.nombre, empleado.apellido, empleado.dni, cargo.nombre AS nomCargo 
          FROM asistencia 
          JOIN empleado ON asistencia.id_empleado = empleado.id_empleado 
          JOIN cargo ON empleado.cargo = cargo.id_cargo 
          $whereSQL";

// Imprimir la consulta SQL para depuración
// echo "Consulta SQL: $query";

$resultado = $conexion->query($query);

// Establecer encabezados HTTP para el archivo PDF
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="ReporteAsistenciaFecha.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

// Asegurarse de que no haya salida previa
ob_start();

// Validar los datos obtenidos
if ($resultado->num_rows > 0) {
    // Generar el PDF
    require('./fpdf.php');

    class PDF extends FPDF {
        // Cabecera de página
        function Header() {
            include '../../modelo/conexion.php'; //llamamos a la conexion BD
   
            $consulta_info = $conexion->query(" select * from institucion "); //traemos datos de la empresa desde BD
            $dato_info = $consulta_info->fetch_object();
            $this->Image('logo.png', 270, 5, 20); //logo de la institucion,moverDerecha,moverAbajo,tamañoIMG
            $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
            $this->Cell(95); // Movernos a la derecha
            $this->SetTextColor(0, 0, 0); //color
            //creamos una celda o fila
            $this->Cell(110, 15, utf8_decode($dato_info->nombre), 1, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
            $this->Ln(3); // Salto de línea
            $this->SetTextColor(103); //color
   
            /* UBICACION */
            $this->Cell(180);  // mover a la derecha
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(96, 10, utf8_decode("Ubicación : " . $dato_info->ubicacion), 0, 0, '', 0);
            $this->Ln(5);
   
            /* TELEFONO */
            $this->Cell(180);  // mover a la derecha
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(59, 10, utf8_decode("Teléfono : " . $dato_info->telefono), 0, 0, '', 0);
            $this->Ln(5);
   
            /* RUC */
            $this->Cell(180);  // mover a la derecha
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(85, 10, utf8_decode("RUC : " . $dato_info->ruc), 0, 0, '', 0);
            $this->Ln(10);
   
            /* TITULO DE LA TABLA */
            //color
            $this->SetTextColor(0, 95, 189);
            $this->Cell(100); // mover a la derecha
            $this->SetFont('Arial', 'B', 15);
            $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS "), 0, 1, 'C', 0);
            $this->Ln(7);
   
            /* CAMPOS DE LA TABLA */
            //color
            $this->SetFillColor(125, 173, 221); //colorFondo
            $this->SetTextColor(0, 0, 0); //colorTexto
            $this->SetDrawColor(163, 163, 163); //colorBorde
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
            $this->Cell(80, 10, utf8_decode('EMPLEADO'), 1, 0, 'C', 1);
            $this->Cell(30, 10, utf8_decode('CÉDULA'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('CARGO'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
            $this->Cell(50, 10, utf8_decode('SALIDA'), 1, 1, 'C', 1);
        }
   
        // Pie de página
        function Footer() {
            $this->SetY(-15); // Posición: a 1,5 cm del final
            $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
            $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)
   
            $this->SetY(-15); // Posición: a 1,5 cm del final
            $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
            $hoy = date('d/m/Y');
            $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
        }
    }

    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage("landscape");
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetDrawColor(163, 163, 163);

    // Agregar datos al PDF
    while ($row = $resultado->fetch_assoc()) {
        $pdf->Cell(15, 10, utf8_decode($row['id_asistencia']), 1, 0, 'C', 0);
        $pdf->Cell(80, 10, utf8_decode($row['nombre'] . " " . $row['apellido']), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($row['dni']), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($row['nomCargo']), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($row['entrada']), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($row['salida']), 1, 1, 'C', 0);
    }

    // Asegurarse de que no haya salida antes de $pdf->Output()
    ob_start();

    // Generar el PDF
    $pdf->Output();
} else {
    echo "No se encontraron registros para los filtros seleccionados.";
}

ob_end_clean();
?>

