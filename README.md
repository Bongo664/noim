# Dokumentacja projektu generowania PDF z danymi oferty

## Spis treści
1. [Opis projektu](#opis-projektu)
2. [Wymagania](#wymagania)
3. [Struktura projektu](#struktura-projektu)
4. [Instalacja](#instalacja)
5. [Użycie](#użycie)
6. [Opis plików](#opis-plików)
7. [Bezpieczeństwo](#bezpieczeństwo)
8. [Przykłady](#przykłady)
9. [Podsumowanie](#podsumowanie)

## Opis projektu
Projekt ma na celu generowanie dokumentów PDF na podstawie danych oferty przechowywanych w bazie danych. Użytkownik może pobrać PDF, który zawiera szczegóły oferty, w tym tekst i obrazy. Projekt wykorzystuje bibliotekę FPDF do tworzenia plików PDF oraz obsługuje tekst w formacie UTF-8.

## Wymagania
- PHP w wersji 5.0 lub wyższej
- Rozszerzenie gd dla PHP (do obsługi obrazów)
- Biblioteka FPDF (w wersji 1.8.6 lub nowszej)
- Połączenie z bazą danych (MySQL)

## Struktura projektu
Projekt składa się z następujących plików i folderów:

```
/projekt
│
├── db_connect.php          # Plik do łączenia z bazą danych
├── script.php              # Główny skrypt generujący PDF
├── fpdf186/                # Folder z biblioteką FPDF
│   ├── fpdf.php            # Główna klasa FPDF
│   └── font/               # Folder z czcionkami
│       ├── DejaVuSans.php  # Czcionka DejaVuSans
│       └── DejaVuSans-Bold.php # Czcionka DejaVuSans Bold
└── temp/                   # Folder do przechowywania tymczasowych obrazów
```

## Instalacja
1. Skopiuj wszystkie pliki projektu na serwer obsługujący PHP.
2. Upewnij się, że folder `fpdf186/` oraz jego zawartość są poprawnie skopiowane.
3. Skonfiguruj plik `db_connect.php`, aby połączyć się z bazą danych.
4. Upewnij się, że folder `temp/` ma odpowiednie uprawnienia do zapisu.

## Użycie
1. Otwórz przeglądarkę internetową.
2. Wprowadź adres URL skryptu z parametrem GET `id`, który odpowiada ID oferty w bazie danych, np.:
   ```
   http://twoja_domena.com/script.php?id=1
   ```

## Opis plików

### db_connect.php
Plik odpowiedzialny za nawiązanie połączenia z bazą danych. Powinien zawierać dane logowania do bazy danych oraz kod do obsługi połączenia.

### script.php
Główny skrypt, który:
- Inicjalizuje buforowanie wyjścia.
- Ładuje bibliotekę FPDF.
- Tworzy obiekt PDF.
- Pobiera dane oferty z bazy danych na podstawie ID.
- Generuje dokument PDF z danymi oferty.
- Zapisuje PDF do przeglądarki.

### fpdf186/
Folder zawierający bibliotekę FPDF, która jest używana do generowania plików PDF. Zawiera plik `fpdf.php` oraz folder `font/` z czcionkami.

### temp/
Folder do przechowywania tymczasowych obrazów, które są dodawane do dokumentu PDF. Folder ten musi mieć odpowiednie uprawnienia do zapisu.

## Bezpieczeństwo
- Użyj przygotowanych zapytań lub funkcji `mysqli_real_escape_string()` do zabezpieczenia przed SQL Injection.
- Upewnij się, że dane wejściowe (np. ID oferty) są odpowiednio walidowane.
- Zastosuj odpowiednie uprawnienia do folderów, aby zapobiec nieautoryzowanemu dostępowi.

## Przykłady
Aby wygenerować PDF dla oferty o ID `1`, należy otworzyć w przeglądarce adres:
```
http://twoja_domena.com/script.php?id=1
```

## Podsumowanie
Projekt umożliwia generowanie dokumentów PDF z danymi oferty w sposób elastyczny i dostosowany do potrzeb użytkownika. Dzięki obsłudze UTF-8 oraz możliwości dodawania obrazów, dokumenty są czytelne i estetyczne. Projekt jest łatwy w instalacji i użyciu, co czyni go przydatnym narzędziem dla osób potrzebujących generować oferty w formacie PDF.
