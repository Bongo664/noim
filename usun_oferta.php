<?php
include('db_connect.php');

// Sprawdzenie, czy został przekazany parametr ID
if (isset($_GET['id'])) {
    // Pobranie ID oferty z parametru GET
    $id = $_GET['id'];

    // Przygotowanie zapytania SQL do usunięcia rekordu z bazy
    $stmt = $conn->prepare("DELETE FROM oferty WHERE id = ?");
    $stmt->bind_param('i', $id); // Powiązanie zmiennej ID jako parametru typu integer

    if ($stmt->execute()) {
        // Przekierowanie na stronę z listą ofert po udanym usunięciu
        header("Location: lista_ofert.php");
        exit(); // Zakończenie skryptu po przekierowaniu
    } else {
        // Wyświetlenie komunikatu błędu w przypadku niepowodzenia
        echo "Błąd podczas usuwania oferty.";
    }

    // Zamknięcie przygotowanego zapytania
    $stmt->close();
} else {
    // Wyświetlenie komunikatu, jeśli nie przekazano ID
    echo "Nie podano ID oferty.";
}

// Zamknięcie połączenia z bazą danych
$conn->close();
?>
