<?php
include('db_connect.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM oferty WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: lista_ofert.php");
        exit();
    } else {
        echo "Błąd podczas usuwania oferty.";
    }

    $stmt->close();
} else {
    echo "Nie podano ID oferty.";
}

$conn->close();
?>
