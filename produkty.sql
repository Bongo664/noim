-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 02:04 PM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `produkty`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `oferty`
--

CREATE TABLE `oferty` (
  `id` int(11) NOT NULL,
  `numer_oferty` varchar(50) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `nazwa_produktu` varchar(255) DEFAULT NULL,
  `kod_produktu` varchar(50) DEFAULT NULL,
  `opcja_bez_znakowania` tinyint(1) DEFAULT NULL,
  `opcja_z_znakowaniem` tinyint(1) DEFAULT NULL,
  `kolory_bez_znakowania` varchar(255) DEFAULT NULL,
  `technologia_znakowania` varchar(255) DEFAULT NULL,
  `liczba_kolorow` int(11) DEFAULT NULL,
  `kolory_znakowania` varchar(255) DEFAULT NULL,
  `kolory` varchar(255) DEFAULT NULL,
  `ilosc` int(11) DEFAULT NULL,
  `cena_produktu` decimal(10,2) DEFAULT NULL,
  `cena_przygotowana` decimal(10,2) DEFAULT NULL,
  `cena_nadruku` decimal(10,2) DEFAULT NULL,
  `cena_jednostkowa` decimal(10,2) DEFAULT NULL,
  `cena_przed_marza` decimal(10,2) DEFAULT NULL,
  `cena_po_marzy` decimal(10,2) DEFAULT NULL,
  `grafika_produktu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `oferty`
--
ALTER TABLE `oferty`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oferty`
--
ALTER TABLE `oferty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
