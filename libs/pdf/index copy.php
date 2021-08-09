<?php
include 'plantilla.php';
require 'conexion.php';
require '/load.php';



$query  = " SELECT *";
$query  .= " FROM series p ";
$query .= " WHERE p.carton BETWEEN 1 AND 2";

$resultado = $mysqli->query($query);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFillColor(232, 232, 232);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 6, 'id', 1, 0, 'C', 1);
$pdf->Cell(20, 6, 'serie', 1, 0, 'C', 1);
$pdf->Cell(70, 6, 'carton', 1, 1, 'C', 1);

$pdf->SetFont('Arial', '', 10);

while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(70, 6, utf8_decode($row['id']), 1, 0, 'C');
    $pdf->Cell(20, 6, $row['serie'], 1, 0, 'C');
    $pdf->Cell(70, 6, utf8_decode($row['carton']), 1, 1, 'C');
}
$pdf->Output();
