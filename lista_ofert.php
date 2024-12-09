<?php
include('db_connect.php');

// Funkcja do zmiany formatu daty na dd-mm-yyyy
function formatDate($date) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date); // Zakładając, że data jest w formacie Y-m-d
    return $dateObj ? $dateObj->format('d-m-Y') : $date;
}

// Domyślnie puste zapytanie
$searchQuery = '';
$whereClause = '';

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    
    // Analizujemy prefiks w zapytaniu
    if (strpos($searchQuery, 'nr:') === 0) {
        // Szukamy po numerze oferty
        $searchValue = substr($searchQuery, 3); // Usuwamy 'nr:'
        $whereClause = "WHERE numer_oferty LIKE '%" . $conn->real_escape_string($searchValue) . "%'";
    } elseif (strpos($searchQuery, 'naz:') === 0) {
        // Szukamy po nazwie produktu
        $searchValue = substr($searchQuery, 4); // Usuwamy 'naz:'
        $whereClause = "WHERE nazwa_produktu LIKE '%" . $conn->real_escape_string($searchValue) . "%'";
    } elseif (strpos($searchQuery, 'kod:') === 0) {
        // Szukamy po kodzie produktu
        $searchValue = substr($searchQuery, 4); // Usuwamy 'kod:'
        $whereClause = "WHERE kod_produktu LIKE '%" . $conn->real_escape_string($searchValue) . "%'";
    } else {
        // Domyślne wyszukiwanie w wszystkich polach
        $whereClause = "WHERE numer_oferty LIKE '%" . $conn->real_escape_string($searchQuery) . "%' 
                        OR nazwa_produktu LIKE '%" . $conn->real_escape_string($searchQuery) . "%' 
                        OR kod_produktu LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
    }
}

// Zapytanie SQL z dynamicznym filtrowaniem
$query = "SELECT id, numer_oferty, data, nazwa_produktu, kod_produktu 
          FROM oferty 
          $whereClause";

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

        .btn-back {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #4CAF20;
        }
    </style>
</head>
<body>
<a href="index" class="btn-back" style="text-decoration:none;">Powrót do strony głównej</a>
    <h1>Lista ofert</h1>
    
    <!-- Formularz wyszukiwania -->
    <form method="get" action="">
        <p>Wpisz <strong>nr:</strong> przed numerem oferty, <strong>naz:</strong> przed nazwą produktu lub <strong>kod:</strong> przed kodem produktu, aby filtrować.</p>
        <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Wyszukaj ofertę..." />
        <button type="submit">Szukaj</button>
    </form>
    
    <!-- Link do dodania nowej oferty -->
    <a href="dodaj_oferte.php" class="btn-add" style="text-decoration:none;">Dodaj nową ofertę</a>
    
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
                        <td><?php echo htmlspecialchars(formatDate($row['data'])); ?></td>
                        <td><?php echo htmlspecialchars($row['nazwa_produktu']); ?></td>
                        <td><?php echo htmlspecialchars($row['kod_produktu']); ?></td>
                        <td class="action-links">
                            <a href="szczegoly_oferty?id=<?php echo $row['id']; ?>">Szczegóły</a> |
                            <a href="generuj_pdf.php?id=<?php echo $row['id']; ?>"style="margin-left:20px">Generuj PDF</a> |
                            <a href="usun_oferta.php?id=<?php echo $row['id']; ?>" style="margin-left:20px" onclick="return confirm('Czy na pewno chcesz usunąć tę ofertę?')">Usuń</a>
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
