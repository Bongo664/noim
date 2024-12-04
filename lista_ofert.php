<?php
include('db_connect.php'); 

$query = "SELECT id, numer_oferty, data, nazwa_produktu, kod_produktu FROM oferty";
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
    </style>
</head>
<body>
    <h1>Lista ofert</h1>
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
                    <td colspan="5">Brak ofert w bazie danych.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
