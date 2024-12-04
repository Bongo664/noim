<?php
include_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numer_oferty = $_POST['numer_oferty'];
    $data = $_POST['data'];
    $nazwa_produktu = $_POST['nazwa_produktu'];
    $kod_produktu = $_POST['kod_produktu'];
    $opcja_bez_znakowania = isset($_POST['bez_znakowania']) ? 1 : 0;
    $opcja_z_znakowaniem = isset($_POST['z_znakowaniem']) ? 1 : 0;
    $kolory_bez_znakowania = $_POST['kolory_bez_znakowania'] ?? '';
    $technologia_znakowania = $_POST['technologia_znakowania'] ?? '';
    $liczba_kolorow = $_POST['liczba_kolorow'] ?? 0;
    $kolory_znakowania = $_POST['kolory_znakowania'] ?? '';
    $ilosc = $_POST['ilosc'];
    $cena_produktu = $_POST['cena_produktu'];
    $cena_przygotowana = $_POST['cena_przygotowana'];
    $cena_nadruku = $_POST['cena_nadruku'];
    $cena_jednostkowa = $_POST['cena_jednostkowa'];
    $cena_przed_marza = $_POST['cena_przed_marza'];
    $procent_marzy = $_POST['procent_marzy'] / 100; 

    $cena_po_marzy = $cena_przed_marza * (1 + $procent_marzy);

    $stmt = $conn->prepare("INSERT INTO oferty 
        (numer_oferty, data, nazwa_produktu, kod_produktu, opcja_bez_znakowania, opcja_z_znakowaniem, kolory_bez_znakowania, technologia_znakowania, liczba_kolorow, kolory_znakowania, ilosc, cena_produktu, cena_przygotowana, cena_nadruku, cena_jednostkowa, cena_przed_marza, cena_po_marzy, grafika_produktu) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssiiisisiiddddss', 
    $numer_oferty, $data, $nazwa_produktu, $kod_produktu, 
    $opcja_bez_znakowania, $opcja_z_znakowaniem, $kolory_bez_znakowania, 
    $technologia_znakowania, $liczba_kolorow, $kolory_znakowania, 
    $ilosc, $cena_produktu, $cena_przygotowana, $cena_nadruku, 
    $cena_jednostkowa, $cena_przed_marza, $cena_po_marzy, $grafika_produktu);
    $stmt->execute();
    echo "Oferta została zapisana.";
}  
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodawanie oferty</title>
    <style>
        .hidden-fields {
            display: none;
            margin-top: 10px;
        }
    </style>
    <script>
        function toggleFields(selectedCheckbox) {
            const bezZnakowaniaCheckbox = document.querySelector('input[name="bez_znakowania"]');
            const zZnakowaniemCheckbox = document.querySelector('input[name="z_znakowaniem"]');
            const bezZnakowaniaFields = document.querySelector('.bez-znakowania-fields');
            const zZnakowaniemFields = document.querySelector('.z-znakowaniem-fields');

            if (selectedCheckbox === 'bez_znakowania' && bezZnakowaniaCheckbox.checked) {
                zZnakowaniemCheckbox.checked = false;
                zZnakowaniemFields.style.display = 'none';
            } else if (selectedCheckbox === 'z_znakowaniem' && zZnakowaniemCheckbox.checked) {
                bezZnakowaniaCheckbox.checked = false;
                bezZnakowaniaFields.style.display = 'none';
            }

            bezZnakowaniaFields.style.display = bezZnakowaniaCheckbox.checked ? 'block' : 'none';
            zZnakowaniemFields.style.display = zZnakowaniemCheckbox.checked ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <form method="post" action="">
        <label>Numer oferty: <input type="text" name="numer_oferty" required></label><br>
        <label>Data: <input type="date" name="data" required></label><br>
        <label>Nazwa produktu: <input type="text" name="nazwa_produktu" required></label><br>
        <label>Kod produktu: <input type="text" name="kod_produktu" required></label><br>
        
        <label>Opcja bez znakowania: <input type="checkbox" name="bez_znakowania" onchange="toggleFields('bez_znakowania')"></label><br>
        <label>Opcja z znakowaniem: <input type="checkbox" name="z_znakowaniem" onchange="toggleFields('z_znakowaniem')"></label><br>
        
        <div class="hidden-fields bez-znakowania-fields">
            <label>Kolory (bez znakowania): <input type="text" name="kolory_bez_znakowania"></label><br>
        </div>
        
        <div class="hidden-fields z-znakowaniem-fields">
            <label>Technologia znakowania: <input type="text" name="technologia_znakowania"></label><br>
            <label>Liczba kolorów: <input type="number" name="liczba_kolorow"></label><br>
            <label>Kolory znakowania: <input type="text" name="kolory_znakowania"></label><br>
        </div>
        
        <label>Ilość: <input type="number" name="ilosc" required></label><br>
        <label>Cena produktu: <input type="number" step="0.01" name="cena_produktu" required></label><br>
        <label>Cena przygotowania: <input type="number" step="0.01" name="cena_przygotowana"></label><br>
        <label>Cena nadruku: <input type="number" step="0.01" name="cena_nadruku"></label><br>
        <label>Cena jednostkowa: <input type="number" step="0.01" name="cena_jednostkowa"></label><br>
        <label>Cena przed marżą: <input type="number" step="0.01" name="cena_przed_marza"></label><br>
        
        <label>Procent marży: <input type="number" step="0.01" name="procent_marzy" required></label><br>

        <label>Cena po marży: <input type="number" step="0.01" name="cena_po_marzy" style="display:none;" value="<?php echo isset($cena_po_marzy) ? $cena_po_marzy : ''; ?>"></label><br>
        
        <label>Grafika produktu (link): <input type="text" name="grafika_produktu"></label><br>
        
        <button type="submit">Zapisz ofertę</button>
    </form>
</body>
</html>
<?php
$conn->close();
?>
