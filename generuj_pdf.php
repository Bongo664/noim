<?php
include_once('db_connect.php');
require('fpdf186/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Oferta', 0, 1, 'C');
        $this->Ln(10);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM oferty WHERE id = $id");
$oferta = $result->fetch_assoc();

foreach ($oferta as $key => $value) {
    if ($key !== 'cena_przed_marza') {
        $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
    }
}

$pdf->Output('D', 'oferta.pdf');
?>
