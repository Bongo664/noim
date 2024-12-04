<?php
$conn = new mysqli('localhost', 'root', '', 'produkty');

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}
?>