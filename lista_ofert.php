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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            padding: 8px 12px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            color: #333;
            font-size: 16px;
        }

        td {
            font-size: 14px;
            color: #555;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-links {
            font-size: 14px;
        }

        .action-links a {
            margin-right: 10px;
            color: #2196F3;
        }

        .action-links a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            font-size: 16px;
            color: #999;
        }

        .btn-add {
            display: inline-block;
            padding: 8px 12px;
            background-color: #2196F3;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .btn-add:hover {
            background-color: #1976D2;
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
    <!-- Link do dodania nowej oferty -->
    <a href="dodaj_oferte.php" class="btn-add">Dodaj nową ofertę</a>
    <br><br>
    
    <!-- Tabela wyników wyszukiwania -->
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
                        <td class="action-links">
                            <a href="szczegoly_oferty.php?id=<?php echo $row['id']; ?>">Szczegóły</a> |
                            <a href="generuj_pdf.php?id=<?php echo $row['id']; ?>">Generuj PDF</a> |
                            <a href="usun_oferta.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć tę ofertę?')">Usuń</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="no-results">Brak ofert spełniających kryteria wyszukiwania.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
