<?php
include('db_connect.php'); 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = "SELECT * FROM oferty WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $oferta = $result->fetch_assoc();
    } else {
        die("Nie znaleziono oferty o podanym ID.");
    }
} else {
    die("Nie przekazano ID oferty.");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły oferty</title>
</head>
<body>
    <h1>Szczegóły oferty</h1>
    <a href="lista_ofert.php">Powrót do listy ofert</a>
    <br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>Numer oferty</th>
            <td><?php echo htmlspecialchars($oferta['numer_oferty']); ?></td>
        </tr>
        <tr>
            <th>Data</th>
            <td><?php echo htmlspecialchars($oferta['data']); ?></td>
        </tr>
        <tr>
            <th>Nazwa produktu</th>
            <td><?php echo htmlspecialchars($oferta['nazwa_produktu']); ?></td>
        </tr>
        <tr>
            <th>Kod produktu</th>
            <td><?php echo htmlspecialchars($oferta['kod_produktu']); ?></td>
        </tr>
        <tr>
            <th>Opcja bez znakowania</th>
            <td><?php echo $oferta['opcja_bez_znakowania'] ? 'Tak' : 'Nie'; ?></td>
        </tr>
        <tr>
            <th>Opcja z znakowaniem</th>
            <td><?php echo $oferta['opcja_z_znakowaniem'] ? 'Tak' : 'Nie'; ?></td>
        </tr>
        <tr>
            <th>Technologia znakowania</th>
            <td><?php echo htmlspecialchars($oferta['technologia_znakowania']); ?></td>
        </tr>
        <tr>
            <th>Liczba kolorów</th>
            <td><?php echo htmlspecialchars($oferta['liczba_kolorow']); ?></td>
        </tr>
        <tr>
            <th>Kolory</th>
            <td><?php echo htmlspecialchars($oferta['kolory']); ?></td>
        </tr>
        <tr>
            <th>Ilość</th>
            <td><?php echo htmlspecialchars($oferta['ilosc']); ?></td>
        </tr>
        <tr>
            <th>Cena produktu</th>
            <td><?php echo htmlspecialchars($oferta['cena_produktu']); ?> zł</td>
        </tr>
        <tr>
            <th>Cena przygotowania</th>
            <td><?php echo htmlspecialchars($oferta['cena_przygotowana']); ?> zł</td>
        </tr>
        <tr>
            <th>Cena nadruku</th>
            <td><?php echo htmlspecialchars($oferta['cena_nadruku']); ?> zł</td>
        </tr>
        <tr>
            <th>Cena jednostkowa</th>
            <td><?php echo htmlspecialchars($oferta['cena_jednostkowa']); ?> zł</td>
        </tr>
        <tr>
            <th>Cena przed marżą</th>
            <td><?php echo htmlspecialchars($oferta['cena_przed_marza']); ?> zł</td>
        </tr>
        <tr>
            <th>Cena po marży</th>
            <td><?php echo htmlspecialchars($oferta['cena_po_marzy']); ?> zł</td>
        </tr>
        <tr>
            <th>Grafika produktu</th>
            <td>
                <?php if (!empty($oferta['grafika_produktu'])): ?>
                    <img src="<?php echo htmlspecialchars($oferta['grafika_produktu']); ?>" alt="Grafika produktu" style="max-width: 300px;">
                <?php else: ?>
                    Brak grafiki
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>

<?php
$conn->close();
?>