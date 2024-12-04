<?php
include('db_connect.php'); 

// Sprawdzamy, czy użytkownik wprowadził zapytanie wyszukiwania
$searchQuery = ''; // Domyślnie puste zapytanie, jeśli nic nie wpisano
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
}

// Zaktualizowane zapytanie SQL z filtrowaniem po numerze oferty, nazwie produktu lub kodzie
$query = "SELECT id, numer_oferty, data, nazwa_produktu, kod_produktu 
          FROM oferty 
          WHERE numer_oferty LIKE '%$searchQuery%' 
          OR nazwa_produktu LIKE '%$searchQuery%' 
          OR kod_produktu LIKE '%$searchQuery%'";

$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista ofert</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type="text"] {
            padding: 5px;
            margin-right: 10px;
        }
        button {
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Lista ofert</h1>
    
    <!-- Formularz wyszukiwania -->
    <form method="get" action="">
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Wyszukaj ofertę..." />
        <button type="submit">Szukaj</button>
    </form>
    
    <br><br>
    <a href="dodaj_oferte.php">Dodaj nową ofertę</a>
    <br><br>
    
    <table>
        <thead>
            <tr>
                <th>Numer oferty</th>
                <th>Data</th>
                <th>Nazwa produktu</th>
                <th>Kod produktu</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['numer_oferty']); ?></td>
                        <td><?php echo htmlspecialchars($row['data']); ?></td>
                        <td><?php echo htmlspecialchars($row['nazwa_produktu']); ?></td>
                        <td><?php echo htmlspecialchars($row['kod_produktu']); ?></td>
                        <td>
                            <a href="szczegoly_oferty.php?id=<?php echo $row['id']; ?>">Szczegóły</a> |
                            <a href="generuj_pdf.php?id=<?php echo $row['id']; ?>">Generuj PDF</a> |
                            <a href="usun_oferta.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę ofertę?')">Usuń</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Brak ofert spełniających kryteria wyszukiwania.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
