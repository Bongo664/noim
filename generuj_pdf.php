<?php
include_once('db_connect.php');
require('fpdf186/fpdf.php');

// Klasa PDF rozszerzająca FPDF
class PDF extends FPDF {
    // Nagłówek dokumentu
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Oferta', 0, 1, 'C');
        $this->Ln(10);
    }
}

// Tworzenie obiektu PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

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
foreach ($oferta as $key => $value) {
    // Pomijanie pól określonych w $pola_pominiete
    if (in_array($key, $pola_pominiete)) {
        continue;
    }

    // Obsługa pola 'kolory_bez_znakowania'
    if ($key === 'kolory_bez_znakowania') {
        if ($opcja_bez_znakowania == 1) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
        continue;
    }

    // Wyświetlanie danych w zależności od wartości pola 'znakowanie'
    if ($znakowanie == 1) {
        // Wyświetlanie tylko pól związanych ze znakowaniem
        if (in_array($key, ['technologia_znakowania', 'liczba_kolorow', 'kolory_znakowania'])) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
    } else {
        // Jeśli 'znakowanie' == 0, pomijanie pól związanych ze znakowaniem
        if (!in_array($key, ['technologia_znakowania', 'liczba_kolorow', 'kolory_znakowania'])) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
    }
}

// Pobranie danych binarnych obrazu z bazy danych
$imageBlob = $oferta['grafika_produktu']; // Zakładamy, że obraz jest przechowywany jako blob w bazie danych

if (!empty($imageBlob)) {
    // Tworzenie obrazu z danych binarnych
    $imageResource = imagecreatefromstring($imageBlob);
    
    if ($imageResource !== false) {
        // Ścieżka do tymczasowego pliku obrazu
        $tempImagePath = 'temp/temp_image.jpg'; // Zapisywanie w katalogu 'temp'

        // Tworzenie katalogu 'temp', jeśli nie istnieje
        if (!file_exists('temp')) {
            mkdir('temp', 0777, true); // Tworzenie katalogu z odpowiednimi uprawnieniami
        }

        // Zapisywanie obrazu jako JPEG w katalogu tymczasowym
        imagejpeg($imageResource, $tempImagePath); // Można użyć imagepng() dla PNG
        imagedestroy($imageResource); // Zwolnienie pamięci
        
        // Dodanie obrazu do PDF, jeśli plik został utworzony
        if (file_exists($tempImagePath)) {
            $pdf->Image($tempImagePath, 10, $pdf->GetY(), 50); // Dopasowanie rozmiaru i pozycji obrazu
            $pdf->Ln(60); // Dodanie odstępu po obrazie
        } else {
            error_log("Nie udało się utworzyć pliku tymczasowego obrazu.");
        }
    } else {
        error_log("Nie udało się utworzyć obrazu z danych binarnych.");
    }
}

// Pobranie numeru oferty z bazy danych
$numer_oferty = $oferta['numer_oferty'];

// Generowanie nazwy pliku PDF
$filename = 'oferta_' . $numer_oferty . '.pdf';

// Zapis PDF do pliku z dynamiczną nazwą
$pdf->Output('D', $filename);
?>
