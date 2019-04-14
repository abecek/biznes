-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 27 Wrz 2017, 08:32
-- Wersja serwera: 10.1.19-MariaDB
-- Wersja PHP: 7.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `abeceko2_biznes`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `carts`
--

CREATE TABLE `carts` (
  `id_cart` int(11) UNSIGNED NOT NULL,
  `id_order` int(11) UNSIGNED NOT NULL,
  `id_product` smallint(5) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `carts`
--

INSERT INTO `carts` (`id_cart`, `id_order`, `id_product`) VALUES
(31, 8, 5),
(32, 8, 6),
(33, 8, 10),
(34, 9, 10),
(35, 9, 8),
(36, 9, 5),
(37, 9, 6),
(38, 10, 7),
(39, 10, 9),
(40, 11, 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `categories`
--

CREATE TABLE `categories` (
  `id_category` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `categories`
--

INSERT INTO `categories` (`id_category`, `name`) VALUES
(1, 'Landing Pages');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
--

CREATE TABLE `incomes` (
  `id_income` int(11) UNSIGNED NOT NULL,
  `id_sponsor` int(11) UNSIGNED NOT NULL,
  `date_income` datetime NOT NULL,
  `value` decimal(6,2) NOT NULL,
  `id_userFrom` int(11) UNSIGNED NOT NULL,
  `id_order` int(11) UNSIGNED NOT NULL,
  `state` tinyint(1) UNSIGNED NOT NULL,
  `id_product` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `incomes`
--

INSERT INTO `incomes` (`id_income`, `id_sponsor`, `date_income`, `value`, `id_userFrom`, `id_order`, `state`, `id_product`) VALUES
(23, 5, '2017-07-24 00:21:17', '24.00', 70, 8, 0, 5),
(24, 5, '2017-08-24 00:21:17', '20.00', 70, 8, 0, 6),
(25, 5, '2016-12-24 00:21:17', '17.00', 70, 8, 0, 10),
(26, 5, '2017-01-24 00:22:29', '17.00', 70, 9, 1, 10),
(27, 5, '2017-09-24 00:22:29', '15.00', 70, 9, 0, 8),
(28, 5, '2017-09-24 00:22:29', '24.00', 70, 9, 1, 5),
(29, 5, '2017-09-24 00:22:29', '20.00', 70, 9, 1, 6),
(30, 5, '2016-09-24 00:22:57', '14.00', 70, 10, 1, 7),
(31, 5, '2017-09-24 00:22:57', '13.80', 70, 10, 0, 9);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `invoices`
--

CREATE TABLE `invoices` (
  `id_invoice` int(11) UNSIGNED NOT NULL,
  `id_order` int(11) UNSIGNED NOT NULL,
  `date_exposure` datetime NOT NULL,
  `date_sale` datetime NOT NULL,
  `date_payment` datetime NOT NULL,
  `type` varchar(8) NOT NULL,
  `is_paid` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `invoices`
--

INSERT INTO `invoices` (`id_invoice`, `id_order`, `date_exposure`, `date_sale`, `date_payment`, `type`, `is_paid`) VALUES
(8, 8, '2017-09-24 00:21:17', '2017-09-24 00:21:17', '2017-10-08 00:21:17', 'PROFORMA', 0),
(9, 9, '2017-09-24 00:22:29', '2017-09-24 00:22:29', '2017-10-08 00:22:29', 'PROFORMA', 0),
(10, 10, '2017-09-24 00:22:57', '2017-09-24 00:22:57', '2017-10-08 00:22:57', 'PROFORMA', 0),
(11, 11, '2017-09-24 04:05:17', '2017-09-24 04:05:17', '2017-10-08 04:05:17', 'PROFORMA', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `id_message` int(11) UNSIGNED NOT NULL,
  `text` varchar(2000) NOT NULL,
  `date_message` datetime DEFAULT NULL,
  `id_ticket` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int(11) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` varchar(1200) DEFAULT NULL,
  `date_notification` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `notifications`
--

INSERT INTO `notifications` (`id_notification`, `title`, `text`, `date_notification`) VALUES
(1, 'Testowe powiadomienie', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed volutpat mi sed sapien congue ultricies. Nulla facilisi. Aliquam tristique aliquam odio, ac laoreet leo. Nullam in nunc in tellus suscipit fermentum. Fusce semper metus turpis, volutpat facilisis metus commodo sed. Phasellus pretium at erat et suscipit.', '2017-09-01 00:00:00');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) UNSIGNED NOT NULL,
  `date_order` datetime NOT NULL,
  `price_netto` decimal(6,2) NOT NULL,
  `price_brutto` decimal(6,2) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_state` tinyint(2) UNSIGNED NOT NULL,
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL,
  `id_sponsor` int(11) UNSIGNED DEFAULT NULL,
  `id_realization_method` tinyint(3) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`id_order`, `date_order`, `price_netto`, `price_brutto`, `id_user`, `id_state`, `id_payment_method`, `id_sponsor`, `id_realization_method`) VALUES
(8, '2017-09-24 00:21:17', '305.99', '376.37', 70, 1, 1, 5, 1),
(9, '2017-09-24 00:22:29', '381.98', '469.84', 70, 1, 1, 5, 1),
(10, '2017-09-24 00:22:57', '139.99', '172.19', 70, 1, 1, 5, 1),
(11, '2017-09-24 04:05:17', '120.00', '147.60', 5, 1, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `payment_methods`
--

INSERT INTO `payment_methods` (`id_payment_method`, `name`) VALUES
(1, 'Przelew bankowy');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id_product` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `price` decimal(6,2) DEFAULT NULL,
  `version` varchar(3) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `id_category` tinyint(3) UNSIGNED NOT NULL,
  `id_program` tinyint(3) UNSIGNED NOT NULL,
  `filename` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id_product`, `name`, `description`, `price`, `version`, `rating`, `id_category`, `id_program`, `filename`) VALUES
(5, 'Universal LandingPage I', 'Duży, uniwersalny landing page z formularzem kontaktowym! W pełni responsywny, idealnie będzie się prezentował na urządzeniach mobilnych jak i na laptopie, czy na komputerze stacjonarnym.\r\n\r\nŚwietnie nadaje się dla biznesów polegających na marketingu sieciowym!', '120.00', '1.0', '5.0', 1, 1, 'UniversalLandingPage1'),
(6, 'Universal LandingPage II', 'Średniej wielkości, dobrze prezentujący się landing page.\r\nIdealny do przekazania konkretnych informacji.\r\n\r\nMożliwość dodania formularza kontaktowego na życzenie.', '100.00', '1.0', '4.5', 1, 1, 'UniversalLandingPage2'),
(7, 'Simple Landing Page I', 'Prosta strona przechwytująca, do promocji konkretnego produktu, bądź biznesu. Idealna dla osób, które cenią prostotę.\r\n\r\n', '70.00', '1.0', '3.9', 1, 1, 'SimpleLandingPage1'),
(8, 'Universal LandingPage III', 'Średniej wielkości strona przechwytująca. Ze względu na swoją uniwersalność, dobrze dostosuję się do danej branży.', '75.99', '1.0', '4.7', 1, 1, 'UniversalLandingPage3'),
(9, 'Short LandingPage I', 'Krótki landing, zalecany do promowania pojedynczego produktu bądź przechwycenia potencjalnego klienta!\r\n\r\nMożliwość sprzężenia z serwisami społecznościowymi w cenie!', '69.99', '1.0', '4.1', 1, 1, 'ShortLandingPage1'),
(10, 'Universal LandingPage IV', 'Średniej wielkości strona przechwytująca. Sprawdzi się świetnie w pozyskiwaniu klientów z urządzeń stacjonarnych i laptopów.\r\n\r\nMożliwość zastosowania dużych obrazków jako tła spotęguję pozytywne wrażenia odwiedzających stronę.\r\n\r\nZainstalowanie w stopce formularzu kontaktowego GRATIS!', '85.99', '1.0', '4.3', 1, 1, 'UniversalLandingPage4');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `programs`
--

CREATE TABLE `programs` (
  `id_program` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `programs`
--

INSERT INTO `programs` (`id_program`, `name`) VALUES
(1, 'Żaden'),
(2, 'Kryptowaluty'),
(3, 'RevShare');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ratings`
--

CREATE TABLE `ratings` (
  `id_rating` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_product` smallint(5) UNSIGNED NOT NULL,
  `date_rating` datetime NOT NULL,
  `text` varchar(250) DEFAULT NULL,
  `is_accepted` tinyint(1) DEFAULT '0',
  `value` tinyint(1) DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `realization_methods`
--

CREATE TABLE `realization_methods` (
  `id_realization_method` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `realization_methods`
--

INSERT INTO `realization_methods` (`id_realization_method`, `name`) VALUES
(1, 'DIY - Zrób to sam');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `states`
--

CREATE TABLE `states` (
  `id_state` tinyint(2) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `states`
--

INSERT INTO `states` (`id_state`, `name`) VALUES
(1, 'do zapłaty'),
(2, 'zapłacono'),
(3, 'w realizacji'),
(4, 'zrealizowano');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `tickets`
--

CREATE TABLE `tickets` (
  `id_ticket` int(11) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `text` varchar(2500) NOT NULL,
  `date_open` datetime NOT NULL,
  `date_close` datetime DEFAULT NULL,
  `id_user` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id_user` int(11) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(35) NOT NULL,
  `password` char(60) NOT NULL,
  `date_register` datetime NOT NULL,
  `gender` char(1) NOT NULL,
  `id_sponsor` int(11) UNSIGNED DEFAULT '0',
  `rank` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '0',
  `can_change_email` tinyint(1) DEFAULT '0',
  `date_last_pass_request` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `date_register`, `gender`, `id_sponsor`, `rank`, `is_active`, `can_change_email`, `date_last_pass_request`) VALUES
(5, 'abecek', 'abcq1201513@gmail.co', '$2y$11$DjiROtpsKroZXVi5of53/.C2wV/o9EXMLqRnueLP4MG6ahIPb2zki', '2017-03-17 02:00:14', 'M', NULL, 1, 1, 0, '2017-09-05 03:28:45'),
(43, 'abecek9', 'as4@gmail.com', '$2y$11$SnwxMmNSVcvO8rOF7ROVpOdEKAPV66moFlqREtOokeHh/PnJ4Y5oi', '2017-08-01 00:57:39', 'M', 5, 0, 1, 0, NULL),
(62, 'becek', 'abcq12013@gmail.com', '$2y$11$9OMIPQpxthQwIcNaFs1MSeA.fidywBojBp8fv.aSslBSEWBE2sbTy', '2017-09-07 03:25:45', 'M', NULL, 0, 1, 1, '2017-09-08 01:06:29'),
(69, 'kowalski', 'p463443@mvrht.net', '$2y$11$F9tKj4xpjp4/FNH51lw95OPanEzPG2OvGjQA/TOpqZfAlBmWu4a1u', '2017-09-23 21:24:07', 'M', NULL, 0, 1, 0, NULL),
(70, 'kowalski2', 'vqsrasnwk9ca@10minut.com.pl', '$2y$11$scplSvAjgRJ8Iq7mhGuQyeMm5JmGmqnbSZjnHf6RjR56zIL6nWYIS', '2017-09-23 23:52:18', 'M', 5, 0, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_addresses`
--

CREATE TABLE `users_addresses` (
  `id_user_address` int(11) UNSIGNED NOT NULL,
  `street` varchar(35) NOT NULL,
  `nr_house` varchar(3) NOT NULL,
  `nr_flat` varchar(3) DEFAULT NULL,
  `city` varchar(35) NOT NULL,
  `post_code` char(6) DEFAULT NULL,
  `country` varchar(30) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users_addresses`
--

INSERT INTO `users_addresses` (`id_user_address`, `street`, `nr_house`, `nr_flat`, `city`, `post_code`, `country`, `id_user`) VALUES
(14, 'Ossowskiego', '18', NULL, 'Zgierz', '95-100', 'Polska', 5),
(28, 'Ossowskiego', '16', NULL, 'Zgierz', '95-100', 'Polska', 69),
(29, 'Ossowskiego', '12', NULL, 'Zgierz', '95-100', 'Polska', 70);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users_data`
--

CREATE TABLE `users_data` (
  `id_user_data` int(11) UNSIGNED NOT NULL,
  `name1` varchar(15) NOT NULL,
  `name2` varchar(15) DEFAULT NULL,
  `surname` varchar(35) NOT NULL,
  `identity_number` char(11) DEFAULT NULL,
  `telephone` char(9) NOT NULL,
  `language` char(2) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users_data`
--

INSERT INTO `users_data` (`id_user_data`, `name1`, `name2`, `surname`, `identity_number`, `telephone`, `language`, `id_user`) VALUES
(14, 'Michał', 'Piotr', 'Błaszczyk', '93100812013', '791577123', 'pl', 5),
(29, 'Michał', 'Piotr', 'Błaszczyk', NULL, '791577123', 'pl', 69),
(30, 'Michał', NULL, 'Błaszczyk', NULL, '791577123', 'pl', 70);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `withdraws`
--

CREATE TABLE `withdraws` (
  `id_withdraw` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `date_withdraw` datetime NOT NULL,
  `value` decimal(6,2) NOT NULL,
  `state` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `withdraws`
--

INSERT INTO `withdraws` (`id_withdraw`, `id_user`, `date_withdraw`, `value`, `state`) VALUES
(8, 5, '2017-09-24 00:39:58', '1.01', 0);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `carts_id_order_fk1` (`id_order`),
  ADD KEY `carts_id_product_fk1` (`id_product`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id_income`),
  ADD KEY `incomes_id_user_fk1` (`id_sponsor`),
  ADD KEY `incomes_id_userFrom_fk1` (`id_userFrom`),
  ADD KEY `incomes_id_orders_fk1` (`id_order`),
  ADD KEY `incomes_id_products_fk1` (`id_product`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id_invoice`),
  ADD KEY `invoices_id_order_fk1` (`id_order`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `messages_id_ticket_fk1` (`id_ticket`),
  ADD KEY `messages_id_user_fk1` (`id_user`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `orders_id_user_fk1` (`id_user`),
  ADD KEY `orders_id_state_fk1` (`id_state`),
  ADD KEY `orders_id_payment_fk1` (`id_payment_method`),
  ADD KEY `id_realization_method_fk1` (`id_realization_method`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id_payment_method`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `products_id_category_fk1` (`id_category`),
  ADD KEY `products_id_program_fk1` (`id_program`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id_program`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id_rating`),
  ADD KEY `ratings_id_user_fk2` (`id_user`),
  ADD KEY `ratings_id_product_fk2` (`id_product`);

--
-- Indexes for table `realization_methods`
--
ALTER TABLE `realization_methods`
  ADD PRIMARY KEY (`id_realization_method`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id_state`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id_ticket`),
  ADD KEY `tickets_id_user_fk1` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `login_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`),
  ADD KEY `users_id_sponsor_fk1` (`id_sponsor`);

--
-- Indexes for table `users_addresses`
--
ALTER TABLE `users_addresses`
  ADD PRIMARY KEY (`id_user_address`),
  ADD UNIQUE KEY `id_user_UNIQUE` (`id_user`);

--
-- Indexes for table `users_data`
--
ALTER TABLE `users_data`
  ADD PRIMARY KEY (`id_user_data`),
  ADD UNIQUE KEY `id_user_UNIQUE` (`id_user`),
  ADD UNIQUE KEY `identity_number_UNIQUE` (`identity_number`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`id_withdraw`),
  ADD KEY `withdraw_id_user_fk1` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `carts`
--
ALTER TABLE `carts`
  MODIFY `id_cart` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id_income` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT dla tabeli `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id_invoice` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT dla tabeli `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id_payment_method` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id_product` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT dla tabeli `programs`
--
ALTER TABLE `programs`
  MODIFY `id_program` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT dla tabeli `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id_rating` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `realization_methods`
--
ALTER TABLE `realization_methods`
  MODIFY `id_realization_method` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `states`
--
ALTER TABLE `states`
  MODIFY `id_state` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT dla tabeli `users_addresses`
--
ALTER TABLE `users_addresses`
  MODIFY `id_user_address` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT dla tabeli `users_data`
--
ALTER TABLE `users_data`
  MODIFY `id_user_data` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT dla tabeli `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `id_withdraw` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_id_order_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_id_product_fk1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`);

--
-- Ograniczenia dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_id_orders_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_id_products_fk1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`),
  ADD CONSTRAINT `incomes_id_userFrom_fk1` FOREIGN KEY (`id_userFrom`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `incomes_id_user_fk1` FOREIGN KEY (`id_sponsor`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_id_order_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_id_ticket_fk1` FOREIGN KEY (`id_ticket`) REFERENCES `tickets` (`id_ticket`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `id_realization_method_fk1` FOREIGN KEY (`id_realization_method`) REFERENCES `realization_methods` (`id_realization_method`),
  ADD CONSTRAINT `orders_id_payment_fk1` FOREIGN KEY (`id_payment_method`) REFERENCES `payment_methods` (`id_payment_method`),
  ADD CONSTRAINT `orders_id_state_fk1` FOREIGN KEY (`id_state`) REFERENCES `states` (`id_state`),
  ADD CONSTRAINT `orders_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_id_category_fk1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `products_id_program_fk1` FOREIGN KEY (`id_program`) REFERENCES `programs` (`id_program`);

--
-- Ograniczenia dla tabeli `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_id_product_fk2` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_id_user_fk2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_id_sponsor_fk1` FOREIGN KEY (`id_sponsor`) REFERENCES `users` (`id_user`);

--
-- Ograniczenia dla tabeli `users_addresses`
--
ALTER TABLE `users_addresses`
  ADD CONSTRAINT `users_addresses_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `users_data`
--
ALTER TABLE `users_data`
  ADD CONSTRAINT `users_data_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `withdraws`
--
ALTER TABLE `withdraws`
  ADD CONSTRAINT `withdraw_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
