<?php
// Start output buffering
ob_start();
include_once('db_connect.php');
require('fpdf186/fpdf.php');

// Klasa PDF rozszerzająca FPDF
class PDF extends FPDF {
    function __construct() {
        parent::__construct();
        $this->SetAutoPageBreak(true, 15);

        // Załaduj czcionki (upewnij się, że pliki czcionek znajdują się w folderze 'fpdf186/font/')
        $this->AddFont('DejaVuSans', '', 'DejaVuSans.php');
        $this->AddFont('DejaVuSans', 'B', 'DejaVuSans-Bold.php');

        $this->AddPage();
        $this->AliasNbPages();
    }

    // Nagłówek dokumentu

    // Metoda do obsługi tekstu UTF-8
    function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $txt = $this->utf8_decode($txt);
        parent::Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);
    }

    function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false)
    {
        $txt = $this->utf8_decode($txt);
        parent::MultiCell($w, $h, $txt, $border, $align, $fill);
    }

    // Metoda do dekodowania UTF-8
    function utf8_decode($txt) {
        $result = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $txt);
        return ($result === false) ? $txt : $result;
    }
}

// Reszta kodu pozostaje bez zmian...

// Tworzenie obiektu PDF
$pdf = new PDF();

// Set default font to regular DejaVuSans
$pdf->SetFont('DejaVuSans', '', 12);
$pdf->SetTextColor(0); // Domyślny kolor tekstu (czarny)

// Pobranie ID oferty z parametru GET
$id = $_GET['id'];

// Pobranie danych oferty z bazy danych na podstawie ID
$result = $conn->query("SELECT * FROM oferty WHERE id = $id");
$oferta = $result->fetch_assoc();

// Pobranie wartości dla pól 'znakowanie' i 'opcja_bez_znakowania'
$znakowanie = $oferta['znakowanie'] ?? 0;
$opcja_bez_znakowania = $oferta['opcja_bez_znakowania'] ?? 0;

// Lista pól, które mają zostać pominięte
$pola_pominiete = ['id', 'cena_przed_marza', 'opcja_z_znakowaniem', 'opcja_bez_znakowania', 'znakowanie','kolory','grafika_produktu'];

// Iteracja przez wszystkie pola oferty
// Iteracja przez wszystkie pola oferty
foreach ($oferta as $key => $value) {
    // Pomijanie pól określonych w $pola_pominiete
    if (in_array($key, $pola_pominiete)) {
        continue;
    }

    // Jeśli wybrano "ze znakowaniem", pomijaj pole "kolory bez znakowania"
    if ($oferta['opcja_z_znakowaniem'] == 1 && $key === 'kolory_bez_znakowania') {
        continue;
    }

    // Jeśli wybrano "bez znakowania", pomijaj pola związane ze znakowaniem
    if ($oferta['opcja_bez_znakowania'] == 1 && in_array($key, ['technologia_znakowania', 'liczba_kolorow', 'kolory_znakowania'])) {
        continue;
    }

    // Reszta kodu pozostaje bez zmian...

    // Formatowanie kluczy na bardziej czytelne
    // Formatowanie kluczy na bardziej czytelne
    $key_formatted = ucfirst(str_replace('_', ' ', $key));

    // Styl dla nagłówków sekcji
    $pdf->SetFont('DejaVuSans', 'B', 12);
    $pdf->SetTextColor(33, 150, 243); // Kolor nagłówka (niebieski)
    $pdf->Cell(0, 8, $key_formatted, 0, 1);

    // Styl dla wartości
    $pdf->SetFont('DejaVuSans', '', 12);
    $pdf->SetTextColor(0); // Kolor tekstu (czarny)
    $pdf->MultiCell(0, 8, $value); // Automatyczne zawijanie tekstu

    $pdf->Ln(2); // Mały odstęp między wierszami
}


// Dodanie obrazu, jeśli istnieje
$imageBlob = $oferta['grafika_produktu']; 

if (!empty($imageBlob)) {
    $imageResource = imagecreatefromstring($imageBlob);
    if ($imageResource !== false) {
        $tempImagePath = 'temp/temp_image.jpg';
        if (!file_exists('temp')) {
            mkdir('temp', 0777, true);
        }
        imagejpeg($imageResource, $tempImagePath);
        imagedestroy($imageResource);

        if (file_exists($tempImagePath)) {
            $pdf->Image($tempImagePath, 10, $pdf->GetY(), 50); // Dopasowanie rozmiaru i pozycji obrazu
            $pdf->Ln(60);
        }
    }
}

// Pobranie numeru oferty z bazy danych
$numer_oferty = $oferta['numer_oferty'];

// Pobranie aktualnej daty w formacie DD-MM-YYYY
$current_date = date('d-m-Y');

// Generowanie nazwy pliku PDF z datą
$filename = 'oferta_' . $numer_oferty . '_' . $current_date . '.pdf';

// Zapis PDF do pliku z dynamiczną nazwą
// Clear the output buffer
ob_end_clean();

// Zapis PDF do pliku z dynamiczną nazwą
$pdf->Output('D', $filename);
?>

