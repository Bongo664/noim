<?php
include('db_connect.php');

// Funkcja do zmiany formatu daty na dd-mm-yyyy
function formatDate($date) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date); // Zakładając, że data jest w formacie Y-m-d
    return $dateObj ? $dateObj->format('d-m-Y') : $date;
}

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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f9f9f9;
            color: white;
            font-size: 1.1em;
        }

        td {
            background-color: #fff;
            font-size: 1em;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover td {
            background-color: #ddd;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px auto;
            background-color: #007BFF;
            color: white;
            text-align: center;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<a href="javascript:history.back()" class="btn">Powrót</a>
    <h1>Szczegóły oferty - <?php echo htmlspecialchars($oferta['numer_oferty']); ?></h1>
    
    <table>
        <tr>
            <th>Numer oferty</th>
            <td><?php echo htmlspecialchars($oferta['numer_oferty']); ?></td>
        </tr>
        <tr>
            <th>Data</th>
            <td><?php echo htmlspecialchars(formatDate($oferta['data'])); ?></td>
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
            <th>Kolory bez znakowania</th>
            <td><?php echo htmlspecialchars($oferta['kolory_bez_znakowania']); ?></td>
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
            <th>Kolory znakowania</th>
            <td><?php echo htmlspecialchars($oferta['kolory_znakowania']); ?></td>
        </tr>
        <tr>
            <th>Ilość</th>
            <td><?php echo htmlspecialchars($oferta['ilosc']); ?></td>
        </tr>
        <tr>
            <th>Cena produktu</th>
            <td><?php echo htmlspecialchars($oferta['cena_produktu']); ?> PLN</td>
        </tr>
        <tr>
            <th>Cena przygotowana</th>
            <td><?php echo htmlspecialchars($oferta['cena_przygotowana']); ?> PLN</td>
        </tr>
        <tr>
            <th>Cena nadruku</th>
            <td><?php echo htmlspecialchars($oferta['cena_nadruku']); ?> PLN</td>
        </tr>
        <tr>
            <th>Cena jednostkowa</th>
            <td><?php echo htmlspecialchars($oferta['cena_jednostkowa']); ?> PLN</td>
        </tr>
        <tr>
            <th>Cena przed marżą</th>
            <td><?php echo htmlspecialchars($oferta['cena_przed_marza']); ?> PLN</td>
        </tr>
        <tr>
            <th>Cena po marży</th>
            <td><?php echo htmlspecialchars($oferta['cena_po_marzy']); ?> PLN</td>
        </tr>
        <?php if ($oferta['grafika_produktu']) { ?>
            <tr>
                <th>Grafika produktu</th>
                <td>
                    <img src="data:image/png;base64,<?php echo base64_encode($oferta['grafika_produktu']); ?>" alt="Grafika produktu">
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
