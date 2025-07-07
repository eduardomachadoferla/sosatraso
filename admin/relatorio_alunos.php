<?php
ob_start(); // evita erro de envio de headers
session_start();
require '../vendor/autoload.php';  

use Fpdf\Fpdf;

$pdf_title = utf8_decode($_SESSION['pdf_title'] ?? 'Relatório');
$datas = $_SESSION['pdf'] ?? [];

$pdf = new Fpdf();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 15);
$pdf->Text(10, 10, $pdf_title, 1);
$pdf->Cell(1, 10, '', 0, 1);  

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(80, 10, 'Nome', 1);  
$pdf->Cell(20, 10, 'Turma', 1);  
$pdf->Cell(60, 10, 'Motivo', 1);  
$pdf->Cell(35, 10, 'Data', 1);  
$pdf->Cell(1, 10, '', 0, 1);  

foreach($datas as $row){
    $nome = mb_convert_encoding($row['nome'] ?? '', 'ISO-8859-1', 'UTF-8');
    $turma = mb_convert_encoding($row['turma'] ?? '', 'ISO-8859-1', 'UTF-8');
    $motivo = mb_convert_encoding($row['motivo'] ?? '', 'ISO-8859-1', 'UTF-8');
    $data_completa = $row['data'] ?? '';

    // formatar data (de YYYY-MM-DD HH:MM:SS para DD/MM/YYYY - HH:MM:SS)
    $data_formatada = '';
    if (!empty($data_completa) && strpos($data_completa, '-') !== false) {
        [$data, $hora] = explode(' ', $data_completa);
        $data_formatada = implode('/', array_reverse(explode('-', $data))) . ' - ' . $hora;
    }

    $pdf->Cell(80, 10, $nome, 1);  
    $pdf->Cell(20, 10, $turma, 1);  
    $pdf->Cell(60, 10, $motivo, 1);  
    $pdf->Cell(35, 10, $data_formatada, 1);  
    $pdf->Cell(1, 10, '', 0, 1);
}

ob_end_clean();
$pdf->Output('D', $pdf_title.'.pdf');
