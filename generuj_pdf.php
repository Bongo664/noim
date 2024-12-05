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

// Extract values for 'znakowanie' and 'opcja_bez_znakowania'
$znakowanie = $oferta['znakowanie'] ?? 0;
$opcja_bez_znakowania = $oferta['opcja_bez_znakowania'] ?? 0;

// List of fields to be skipped
$pola_pominiete = ['id', 'cena_przed_marza', 'opcja_z_znakowaniem', 'opcja_bez_znakowania', 'znakowanie','kolory','grafika_produktu'];

foreach ($oferta as $key => $value) {
    // Skip fields listed in $pola_pominiete
    if (in_array($key, $pola_pominiete)) {
        continue;
    }

    // Handle 'kolory_bez_znakowania' field
    if ($key === 'kolory_bez_znakowania') {
        if ($opcja_bez_znakowania == 1) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
        continue;
    }

    // Display data based on 'znakowanie' value
    if ($znakowanie == 1) {
        // Display only fields related to znakowaniem
        if (in_array($key, ['technologia_znakowania', 'liczba_kolorow', 'kolory_znakowania'])) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
    } else {
        // If 'znakowanie' == 0, skip fields related to znakowaniem
        if (!in_array($key, ['technologia_znakowania', 'liczba_kolorow', 'kolory_znakowania'])) {
            $pdf->Cell(0, 10, ucfirst(str_replace('_', ' ', $key)) . ': ' . $value, 0, 1);
        }
    }
}

// Retrieve the image binary data from the database
$imageBlob = $oferta['grafika_produktu']; // Assuming the image is stored as binary blob in the database

if (!empty($imageBlob)) {
    // Create an image from the binary blob data
    $imageResource = imagecreatefromstring($imageBlob);
    
    if ($imageResource !== false) {
        // Define a path for the temporary image
        $tempImagePath = 'temp/temp_image.jpg'; // Save to the 'temp' directory in your project

        // Create the 'temp' folder if it doesn't exist
        if (!file_exists('temp')) {
            mkdir('temp', 0777, true); // Create the 'temp' folder with proper permissions
        }

        // Save the image as JPEG in the temporary folder
        imagejpeg($imageResource, $tempImagePath); // Save as JPEG (or use imagepng() for PNG)
        imagedestroy($imageResource); // Free memory
        
        // Now you can use the image in the PDF
        if (file_exists($tempImagePath)) {
            $pdf->Image($tempImagePath, 10, $pdf->GetY(), 50); // Adjust the size and position as needed
            $pdf->Ln(60); // Add space after the image
        } else {
            error_log("Temporary image file could not be created.");
        }
    } else {
        error_log("Failed to create image from blob.");
    }
}

// Retrieve offer number from the database
$numer_oferty = $oferta['numer_oferty'];

// Generate PDF file name
$filename = 'oferta_' . $numer_oferty . '.pdf';

// Save PDF to a file with a dynamic name
$pdf->Output('D', $filename);
?>
