-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Gegenereerd op: 05 feb 2017 om 19:50
-- Serverversie: 10.0.28-MariaDB
-- PHP-versie: 5.6.30-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--------------------------------------------------------

--
-- Tabelstructuur voor tabel `wp_pkw_answer`
--

CREATE TABLE `wp_pkw_answer` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wp_pkw_argument`
--

CREATE TABLE `wp_pkw_argument` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `on_yes` int(11) NOT NULL,
  `on_no` int(11) NOT NULL,
  `count_yes` int(11) NOT NULL,
  `count_no` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wp_pkw_question`
--

CREATE TABLE `wp_pkw_question` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `start` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Hold the question to answer';

--
-- Gegevens worden geëxporteerd voor tabel `wp_pkw_question`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `wp_pkw_tree_node`
--

CREATE TABLE `wp_pkw_tree_node` (
  `id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Indexen voor tabel `wp_pkw_answer`
--
ALTER TABLE `wp_pkw_answer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `fk_answer1` (`question_id`);

--
-- Indexen voor tabel `wp_pkw_argument`
--
ALTER TABLE `wp_pkw_argument`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_arkg1` (`answer_id`),
  ADD KEY `fk_arkg2` (`on_yes`),
  ADD KEY `fk_arkg3` (`on_no`);

--
-- Indexen voor tabel `wp_pkw_question`
--
ALTER TABLE `wp_pkw_question`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `wp_pkw_tree_node`
--
ALTER TABLE `wp_pkw_tree_node`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_anstree` (`answer_id`);


ALTER TABLE `wp_pkw_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `wp_pkw_argument`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;
ALTER TABLE `wp_pkw_tree_node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Beperkingen voor tabel `wp_pkw_answer`
--
ALTER TABLE `wp_pkw_answer`
  ADD CONSTRAINT `fk_answer1` FOREIGN KEY (`question_id`) REFERENCES `wp_pkw_question` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `wp_pkw_argument`
--
ALTER TABLE `wp_pkw_argument`
  ADD CONSTRAINT `fk_arkg1` FOREIGN KEY (`answer_id`) REFERENCES `wp_pkw_tree_node` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_arkg2` FOREIGN KEY (`on_yes`) REFERENCES `wp_pkw_tree_node` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_arkg3` FOREIGN KEY (`on_no`) REFERENCES `wp_pkw_tree_node` (`id`) ON DELETE CASCADE;

--
-- Beperkingen voor tabel `wp_pkw_tree_node`
--
ALTER TABLE `wp_pkw_tree_node`
  ADD CONSTRAINT `fk_anstree` FOREIGN KEY (`answer_id`) REFERENCES `wp_pkw_answer` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- DATA


INSERT INTO `wp_pkw_question` (`id`, `text`, `start`) VALUES
(1, 'Wat zal ik stemmen voor de Tweede Kamer op 15 maart 2017?', 1);

INSERT INTO `wp_pkw_answer` ( id, `question_id`, `text`) VALUES (1, 1, 'Piratenpartij');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'VVD');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'PvdA');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'PVV');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'SP');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'CDA');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'D66');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'ChristenUnie');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'GroenLinks');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'SGP');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Partij voor de Dieren');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, '50PLUS');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Ondernemerspartij');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'VNL Voor Nederland');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'DENK');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Nieuwe Wegen');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Forum voor Democratie');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'De BurgerBeweging');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Vrijzinnige Partij');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'GeenPeil');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Artikel 1');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Niet Stemmers');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Libertarische Partij');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Lokaal in de Kamer');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'JEZUS LEEFT');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'StemNL');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Mens en Spirit / Basisinkomen Partij / V-R');
INSERT INTO wp_pkw_answer(question_id, text ) VALUES (1, 'Vrije Democratische Partij');



INSERT INTO `wp_pkw_tree_node` (`id`, `answer_id`) VALUES (1, 1);
INSERT INTO `wp_pkw_tree_node` (`id`, `answer_id`) VALUES (0, 1); -- NULL-value
