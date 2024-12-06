<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pobierz dane z formularza
    $numer_oferty = $_POST['numer_oferty'];
    $data = $_POST['data'];
    $nazwa_produktu = $_POST['nazwa_produktu'];
    $kod_produktu = $_POST['kod_produktu'];
    $opcja_bez_znakowania = isset($_POST['bez_znakowania']) ? 1 : 0;
    $opcja_z_znakowaniem = isset($_POST['z_znakowaniem']) ? 1 : 0;
    $kolory_bez_znakowania = $opcja_bez_znakowania ? ($_POST['kolory_bez_znakowania'] ?? '') : '';
    $technologia_znakowania = $opcja_z_znakowaniem ? ($_POST['technologia_znakowania'] ?? '') : '';
    $liczba_kolorow = $opcja_z_znakowaniem ? ($_POST['liczba_kolorow'] ?? 0) : 0;
    $kolory_znakowania = $opcja_z_znakowaniem ? ($_POST['kolory_znakowania'] ?? '') : '';
    $ilosc = $_POST['ilosc'];
    $cena_produktu = $_POST['cena_produktu'];
    $cena_przygotowana = $_POST['cena_przygotowana'];
    $cena_nadruku = $_POST['cena_nadruku'];
    $cena_jednostkowa = $_POST['cena_jednostkowa'];
    $cena_przed_marza = $_POST['cena_przed_marza'];
    $procent_marzy = $_POST['procent_marzy'] / 100;
    $cena_po_marzy = $cena_przed_marza * (1 + $procent_marzy);

    // Obsługa przesyłania pliku
    $grafika_produktu = null;
    if (isset($_FILES['grafika_produktu']) && $_FILES['grafika_produktu']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/png'];  // Akceptujemy tylko pliki PNG
        $fileType = $_FILES['grafika_produktu']['type'];

        // Sprawdzamy, czy typ pliku jest dozwolony
        if (!in_array($fileType, $allowedTypes)) {
            echo "Błąd: Tylko obrazy PNG są dozwolone.";
            exit;
        }

        // Jeśli typ jest poprawny, wczytujemy zawartość pliku
        $grafika_produktu = file_get_contents($_FILES['grafika_produktu']['tmp_name']);
    }

    // Przygotowanie zapytania SQL
    $stmt = $conn->prepare("INSERT INTO oferty 
        (numer_oferty, data, nazwa_produktu, kod_produktu, opcja_bez_znakowania, kolory_bez_znakowania, 
        opcja_z_znakowaniem, technologia_znakowania, liczba_kolorow, kolory_znakowania, ilosc, 
        cena_produktu, cena_przygotowana, cena_nadruku, cena_jednostkowa, cena_przed_marza, cena_po_marzy, grafika_produktu)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die("Błąd przygotowania zapytania: " . $conn->error);
    }

    // Bindowanie parametrów
    $stmt->bind_param(
        'isssisssisssddddds',
        $numer_oferty, $data, $nazwa_produktu, $kod_produktu, 
        $opcja_bez_znakowania, $kolory_bez_znakowania, 
        $opcja_z_znakowaniem, $technologia_znakowania, $liczba_kolorow, 
        $kolory_znakowania, $ilosc, $cena_produktu, $cena_przygotowana, 
        $cena_nadruku, $cena_jednostkowa, $cena_przed_marza, $cena_po_marzy, $grafika_produktu
    );

    // Wykonanie zapytania
    if ($stmt->execute()) {
        echo "Oferta została zapisana.";
    } else {
        echo "Błąd podczas zapisywania oferty: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodawanie nowej oferty</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        form {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
            width:600px;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        input[type="checkbox"],
        input[type="file"] {
            width: 100%;
            max-width: 572px;
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        input[type="number"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .hidden-fields {
            display: none;
            margin-top: 10px;
        }

        .hidden-fields label {
            font-weight: normal;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        input[type="checkbox"] {
            display: none;
        }

        input[type="checkbox"] + label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 14px;
            color: #333;
            display: inline-block;
            line-height: 20px;
        }

        input[type="checkbox"] + label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #bbb;
            background-color: #f1f1f1;
            border-radius: 4px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        input[type="checkbox"]:checked + label::before {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        input[type="checkbox"]:hover + label::before {
            border-color: #388E3C;
        }

        input[type="checkbox"]:checked + label::after {
            content: '✔';
            position: absolute;
            left: 5px;
            top: 0;
            font-size: 16px;
            color: white;
        }

        input[type="checkbox"]:not(:checked) + label::before {
            background-color: #f1f1f1;
            border-color: #bbb;
        }

        input[type="checkbox"]:not(:checked):hover + label::before {
            background-color: #e0e0e0;
            border-color: #888;
        }
        input[type="submit"]{
            margin-top: 20px;
        }
        .check{
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        input[type="submit"], 
        .back-link {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            display: inline-block;
            margin-top: 20px;
        }

        input[type="submit"]:hover, 
        .back-link:hover {
            background-color: #45a049;
        }

        .back-link-container {
            text-align: center;
            margin-top: 20px;
        }


        /* Responsive styles */
        @media (max-width: 768px) {
            form {
                padding: 20px;
            }

            label {
                font-size: 12px;
            }

            input[type="text"],
            input[type="date"],
            input[type="number"] {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
    <script>
        function toggleFields(selectedCheckbox) {
            const bezZnakowaniaCheckbox = document.getElementById('bez_znakowania');
            const zZnakowaniemCheckbox = document.getElementById('z_znakowaniem');
            const bezZnakowaniaFields = document.querySelector('.bez-znakowania-fields');
            const zZnakowaniemFields = document.querySelector('.z-znakowaniem-fields');

            if (selectedCheckbox === 'bez_znakowania' && bezZnakowaniaCheckbox.checked) {
                zZnakowaniemCheckbox.checked = false;
                bezZnakowaniaFields.style.display = 'block';
                zZnakowaniemFields.style.display = 'none';
            } else if (selectedCheckbox === 'z_znakowaniem' && zZnakowaniemCheckbox.checked) {
                bezZnakowaniaCheckbox.checked = false;
                bezZnakowaniaFields.style.display = 'none';
                zZnakowaniemFields.style.display = 'block';
            }
            else {
                bezZnakowaniaFields.style.display = 'none';
                zZnakowaniemFields.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <h1>Dodaj nową ofertę</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="numer_oferty">Numer oferty</label>
        <input type="text" id="numer_oferty" name="numer_oferty" required>

        <label for="data">Data</label>
        <input type="date" id="data" name="data" required>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const today = new Date().toISOString().split('T')[0];  // Ustawia datę w formacie YYYY-MM-DD
                document.getElementById('data').value = today;  // Wstawia dzisiejszą datę do pola
            });
        </script>
        

        <label for="nazwa_produktu">Nazwa produktu</label>
        <input type="text" id="nazwa_produktu" name="nazwa_produktu" required>

        <label for="kod_produktu">Kod produktu</label>
        <input type="text" id="kod_produktu" name="kod_produktu" required>

        <div class="check">
            <input type="checkbox" id="bez_znakowania" name="bez_znakowania" onchange="toggleFields('bez_znakowania')">
            <label for="bez_znakowania">Opcja bez znakowania</label>

            <input type="checkbox" id="z_znakowaniem" name="z_znakowaniem" onchange="toggleFields('z_znakowaniem')">
            <label for="z_znakowaniem">Opcja z znakowaniem</label>
        </div>

        <div class="bez-znakowania-fields hidden-fields">
            <label for="kolory_bez_znakowania">Kolory bez znakowania</label>
            <input type="text" id="kolory_bez_znakowania" name="kolory_bez_znakowania">
        </div>

        <div class="z-znakowaniem-fields hidden-fields">
            <label for="technologia_znakowania">Technologia znakowania</label>
            <input type="text" id="technologia_znakowania" name="technologia_znakowania">
            <label for="liczba_kolorow">Liczba kolorów</label>
            <input type="number" id="liczba_kolorow" name="liczba_kolorow">
            <label for="kolory_znakowania">Kolory znakowania</label>
            <input type="text" id="kolory_znakowania" name="kolory_znakowania">
        </div>

        <label for="ilosc">Ilość</label>
        <input type="number" id="ilosc" name="ilosc" required>

        <label for="cena_produktu">Cena produktu</label>
        <input type="number" id="cena_produktu" name="cena_produktu" required>

        <label for="cena_przygotowana">Cena przygotowana</label>
        <input type="number" id="cena_przygotowana" name="cena_przygotowana" required>

        <label for="cena_nadruku">Cena nadruku</label>
        <input type="number" id="cena_nadruku" name="cena_nadruku" required>

        <label for="cena_jednostkowa">Cena jednostkowa</label>
        <input type="number" id="cena_jednostkowa" name="cena_jednostkowa" required>

        <label for="cena_przed_marza">Cena przed marżą</label>
        <input type="number" id="cena_przed_marza" name="cena_przed_marza" required>

        <label for="procent_marzy">Procent marży</label>
        <input type="number" id="procent_marzy" name="procent_marzy" required>

        <label for="grafika_produktu">Grafika produktu</label>
        <input type="file" id="grafika_produktu" name="grafika_produktu">

        <input type="submit" value="Zapisz ofertę">
    </form>
    <div class="back-link-container">
        <a href="index.php" class="back-link">Powrót do strony głównej</a>
    </div>
</body>
</html>
