<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona główna - Zarządzanie ofertami</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Witaj w systemie zarządzania ofertami</h1>
        <p>Wybierz, co chcesz zrobić:</p>
        <a href="dodaj_oferte.php">Dodaj nową ofertę</a>
        <a href="lista_ofert.php">Przeglądaj listę ofert</a>
        <a href="szczegoly_oferty.php?id=1">Wyświetl przykładową ofertę</a>
    </div>
</body>
</html>
