<?php
require('./fpdf.php');

class PDF extends FPDF
{
   function Header()
   {
      include '../../modelo/conexion.php';
      $consulta_info = $conexion->query(" select * from institucion ");
      $dato_info = $consulta_info->fetch_object();
      $this->Image('logo.png', 270, 5, 20);
      $this->SetFont('Arial', 'B', 19);
      $this->Cell(95);
      $this->SetTextColor(0, 0, 0);
      $this->Cell(110, 15, utf8_decode($dato_info->nombre), 1, 1, 'C', 0);
      $this->Ln(3);
      $this->SetTextColor(103);
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("Ubicación : " . $dato_info->ubicacion), 0, 0, '', 0);
      $this->Ln(5);
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(59, 10, utf8_decode("Teléfono : " . $dato_info->telefono), 0, 0, '', 0);
      $this->Ln(5);
      $this->Cell(180);
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("RUC : " . $dato_info->ruc), 0, 0, '', 0);
      $this->Ln(10);
      $this->SetTextColor(0, 95, 189);
      $this->Cell(100);
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE ASISTENCIAS INDIVIDUAL"), 0, 1, 'C', 0);
      $this->Ln(7);
      $this->SetFillColor(125, 173, 221);
      $this->SetTextColor(0, 0, 0);
      $this->SetDrawColor(163, 163, 163);
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
      $this->Cell(80, 10, utf8_decode('EMPLEADO'), 1, 0, 'C', 1);
      $this->Cell(30, 10, utf8_decode('CÉDULA'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('CARGO'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('ENTRADA'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('SALIDA'), 1, 1, 'C', 1);
   }
   function Footer()
   {
      $this->SetY(-15);
      $this->SetFont('Arial', 'I', 8);
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
      $this->SetY(-15);
      $this->SetFont('Arial', 'I', 8);
      $hoy = date('d/m/Y');
      $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C');
   }
}

include '../../modelo/conexion.php';

$pdf = new PDF();
$pdf->AddPage("landscape");
$pdf->AliasNbPages();
$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163);

$id_empleado = isset($_GET['txtempleado']) ? intval($_GET['txtempleado']) : 0;
$fecha_inicio = isset($_GET['txtfechainicio']) ? $_GET['txtfechainicio'] : '';
$fecha_final = isset($_GET['txtfechafinal']) ? $_GET['txtfechafinal'] : '';

// Depuración: Imprimir valores recibidos
file_put_contents('debug.log', "ID Empleado: $id_empleado\nFecha Inicio: $fecha_inicio\nFecha Final: $fecha_final\n", FILE_APPEND);

$txthorainicio = isset($_GET['txthorainicio']) ? $_GET['txthorainicio'] : '';
$txthorafinal = isset($_GET['txthorafinal']) ? $_GET['txthorafinal'] : '';

$fecha_hora_inicio = "$fecha_inicio $txthorainicio";
$fecha_hora_final = "$fecha_final $txthorafinal";

// Depuración: Verificar valores enviados
file_put_contents('debug.log', "ID Empleado: $id_empleado\nFecha Hora Inicio: $fecha_hora_inicio\nFecha Hora Final: $fecha_hora_final\n", FILE_APPEND);

if (empty($fecha_inicio) || empty($fecha_final) || empty($txthorainicio) || empty($txthorafinal)) {
    $pdf->Cell(0, 10, utf8_decode('Las fechas u horas seleccionadas no son válidas o están vacías.'), 1, 1, 'C', 0);
    $pdf->Output('Reporte Asistencia Individual.pdf', 'I');
    exit;
}

$consulta_reporte_asistencia = $conexion->query(" select asistencia.entrada,asistencia.salida,empleado.nombre,empleado.apellido,empleado.dni,cargo.nombre as 'nomCargo' from asistencia inner join empleado ON asistencia.id_empleado=empleado.id_empleado inner join cargo ON empleado.cargo=cargo.id_cargo where empleado.id_empleado = $id_empleado and asistencia.entrada >= '$fecha_hora_inicio' and asistencia.salida <= '$fecha_hora_final' ");

if ($consulta_reporte_asistencia->num_rows === 0) {
    $pdf->Cell(0, 10, utf8_decode('No se encontraron registros para los filtros seleccionados. Verifique las fechas, horas y el empleado.'), 1, 1, 'C', 0);
    $pdf->Cell(0, 10, utf8_decode('Datos de ejemplo:'), 1, 1, 'C', 0);
    $pdf->Cell(15, 10, utf8_decode('1'), 1, 0, 'C', 0);
    $pdf->Cell(80, 10, utf8_decode('Ejemplo Nombre'), 1, 0, 'C', 0);
    $pdf->Cell(30, 10, utf8_decode('12345678'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, utf8_decode('Ejemplo Cargo'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, utf8_decode('2025-06-15 08:00:00'), 1, 0, 'C', 0);
    $pdf->Cell(50, 10, utf8_decode('2025-06-15 17:00:00'), 1, 1, 'C', 0);
} else {
    while ($datos_reporte = $consulta_reporte_asistencia->fetch_object()) {
        $i = $i + 1;
        $pdf->Cell(15, 10, utf8_decode($i), 1, 0, 'C', 0);
        $pdf->Cell(80, 10, utf8_decode($datos_reporte->nombre ." ".$datos_reporte->apellido), 1, 0, 'C', 0);
        $pdf->Cell(30, 10, utf8_decode($datos_reporte->dni), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($datos_reporte->nomCargo), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($datos_reporte->entrada), 1, 0, 'C', 0);
        $pdf->Cell(50, 10, utf8_decode($datos_reporte->salida), 1, 1, 'C', 0);
    }
}

$pdf->Output('Reporte Asistencia Individual.pdf', 'I');
