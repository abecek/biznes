-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 31 Sie 2017, 02:43
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
(1, 1, 2),
(2, 2, 3),
(3, 2, 4),
(4, 3, 4),
(5, 4, 2),
(6, 5, 3),
(7, 6, 2),
(8, 7, 2),
(9, 7, 3);

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
-- Struktura tabeli dla tabeli `expanses`
--

CREATE TABLE `expanses` (
  `id_expanse` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `date_expanse` datetime NOT NULL,
  `value` decimal(6,2) NOT NULL,
  `state` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `id_product` smallint(5) UNSIGNED NOT NULL,
  `state` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `incomes`
--

INSERT INTO `incomes` (`id_income`, `id_sponsor`, `date_income`, `value`, `id_userFrom`, `id_order`, `id_product`, `state`) VALUES
(1, 5, '2017-08-24 15:59:17', '19.90', 43, 2, 3, 'accepted'),
(2, 5, '2017-08-24 15:59:17', '15.00', 43, 2, 4, 'accepted'),
(3, 5, '2017-08-24 17:40:51', '20.00', 44, 6, 2, 'to accept'),
(4, 5, '2017-08-24 17:41:25', '20.00', 44, 7, 2, 'to accept'),
(5, 5, '2017-08-24 17:41:25', '19.90', 44, 7, 3, 'to accept');

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
-- Struktura tabeli dla tabeli `orders`
--

CREATE TABLE `orders` (
  `id_order` int(11) UNSIGNED NOT NULL,
  `date_order` datetime NOT NULL,
  `price_overall` decimal(6,2) NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `id_state` tinyint(3) UNSIGNED NOT NULL,
  `id_payment_method` tinyint(3) UNSIGNED NOT NULL,
  `id_sponsor` int(11) UNSIGNED DEFAULT NULL,
  `id_realization_method` tinyint(3) UNSIGNED DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `orders`
--

INSERT INTO `orders` (`id_order`, `date_order`, `price_overall`, `id_user`, `id_state`, `id_payment_method`, `id_sponsor`, `id_realization_method`) VALUES
(1, '2017-08-24 15:55:30', '200.01', 5, 1, 3, NULL, 2),
(2, '2017-08-24 15:59:17', '349.99', 43, 1, 2, 5, 2),
(3, '2017-08-24 17:01:09', '150.00', 5, 1, 1, NULL, 1),
(4, '2017-08-24 17:02:50', '200.01', 5, 1, 1, NULL, 1),
(5, '2017-08-24 17:05:25', '199.99', 5, 1, 2, NULL, 1),
(6, '2017-08-24 17:40:51', '200.01', 44, 1, 2, 5, 2),
(7, '2017-08-24 17:41:25', '400.00', 44, 1, 1, 5, 1);

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
(1, 'Przelew bankowy'),
(2, 'Płatność Dotpay'),
(3, 'Bitcoin');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `products`
--

CREATE TABLE `products` (
  `id_product` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `version` varchar(3) NOT NULL,
  `rating` decimal(2,1) DEFAULT NULL,
  `id_category` tinyint(3) UNSIGNED NOT NULL,
  `id_program` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `products`
--

INSERT INTO `products` (`id_product`, `name`, `description`, `price`, `version`, `rating`, `id_category`, `id_program`) VALUES
(2, 'Bitcoin affiliate program', 'OPISOPISOPISOPISOPISOPISOPISOPIS', '200.01', '1.1', '5.0', 1, 1),
(3, 'FutureAdPro landing page', 'opiseee22s', '199.99', '1.0', '0.0', 1, 2),
(4, 'NextOne', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla ac lorem imperdiet, venenatis nunc sed, mattis elit. Aenean sem enim, dictum sit amet viverra sed, vulputate sed ex. Sed viverra ipsum sed elit finibus, in lobortis lorem placerat. Curabitur non pharetra libero, gravida malesuada.', '150.00', '1.0', '3.1', 1, 2);

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
(1, 'RevShare'),
(2, 'Crypto Currency');

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
(1, 'DIY - Do it yourself'),
(2, 'Let us do everything!');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `states`
--

CREATE TABLE `states` (
  `id_state` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `states`
--

INSERT INTO `states` (`id_state`, `name`) VALUES
(1, 'not paid'),
(2, 'paid'),
(3, 'in delivery'),
(4, 'delivered');

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
  `is_active` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_user`, `username`, `email`, `password`, `date_register`, `gender`, `id_sponsor`, `rank`, `is_active`) VALUES
(5, 'abecek', 'abcq120123@gmail.com', '$2y$15$GOlGDrHhTNT/p0XxgImG8uJu8otF03jdgOacOd5G2HEg5X3ZfiS/C', '2017-03-17 02:00:14', 'M', NULL, 1, 1),
(43, 'abecek9', 'as4@gmail.com', '$2y$11$SnwxMmNSVcvO8rOF7ROVpOdEKAPV66moFlqREtOokeHh/PnJ4Y5oi', '2017-08-01 00:57:39', 'M', 5, 0, 1),
(44, 'abecek123', 'abecek123@gmail.com', '$2y$11$Xlzv9dWdPZvMHbNeCdVQou1Uw1xkGQ7rNblQyilwtmHWal40yJ976', '2017-08-12 03:41:14', 'M', 5, 0, 1),
(54, 'abecek1993', 'abecek1993@gmail.com', '$2y$11$6VBVjI9r9FawjVFkcLEk7Oflr9u4PNxiz1iBhSKUQc9C4FmlkCCX.', '2017-08-12 04:45:31', 'M', NULL, 0, 1),
(55, 'abecek01', 'amc77774@sjuaq.com', '$2y$11$Y4bQJEsPMII0NDwMcoCobe2.MTJaKrkS9MCjxCRGDI1pm3fymSZfO', '2017-08-17 04:00:45', 'K', 5, 0, 1),
(56, 'abecek02', 'kelzlnxop1ea@10minut.com.pl', '$2y$11$hbTz8.FPMM1V1DRC/1Olp.E6CbEc1OUKOle9jSFYihioUi4iIEvre', '2017-08-17 04:18:41', 'M', NULL, 0, 0),
(57, 'abecek03', 'tgi67461@sjuaq.com', '$2y$11$konqFRYsG9l3VnFouQtZ5.hv46O8Mrjs/wIfFQB8NwMV6kKBN/rfG', '2017-08-17 04:24:03', 'M', 55, 0, 1),
(58, 'abecek2', 'abc21@gmail.com', '$2y$11$kNzQTSRJG3uebWaAieg8HOu5UDfx61H45SvK4gfqbhEwmDF/oXVmK', '2017-08-24 17:09:13', 'M', NULL, 0, 0);

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
(18, 'Ossowskiego', '11', NULL, 'Zgierz', '95-100', 'Polska', 43),
(19, 'Ossowskiego', '1', NULL, 'Zgierz', '95-100', 'Polska', 54),
(20, 'Ossowskiego', '111', NULL, 'Zgierz', '95-100', 'Polska', 44);

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
(19, 'Michał', NULL, 'Błaszczyk', '91571112013', '791577123', 'pl', 43),
(20, 'Michał', NULL, 'Błaszczyk', NULL, '791577123', 'en', 54),
(21, 'Michał', NULL, 'Błaszczyk', '12312312311', '791577123', 'pl', 44);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `carts_id_user_fk1` (`id_order`),
  ADD KEY `carts_id_state_fk1` (`id_product`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `expanses`
--
ALTER TABLE `expanses`
  ADD PRIMARY KEY (`id_expanse`),
  ADD KEY `expanse_id_user_fk1` (`id_user`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id_income`),
  ADD KEY `incomes_id_user_fk1` (`id_sponsor`),
  ADD KEY `incomes_id_userFrom_fk1` (`id_userFrom`),
  ADD KEY `incomes_id_product_fk1` (`id_product`),
  ADD KEY `incomes_id_orders_fk1` (`id_order`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id_message`),
  ADD KEY `messages_id_ticket_fk1` (`id_ticket`),
  ADD KEY `messages_id_user_fk1` (`id_user`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `carts`
--
ALTER TABLE `carts`
  MODIFY `id_cart` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT dla tabeli `expanses`
--
ALTER TABLE `expanses`
  MODIFY `id_expanse` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id_income` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `id_message` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `orders`
--
ALTER TABLE `orders`
  MODIFY `id_order` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT dla tabeli `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id_payment_method` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT dla tabeli `products`
--
ALTER TABLE `products`
  MODIFY `id_product` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `programs`
--
ALTER TABLE `programs`
  MODIFY `id_program` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `realization_methods`
--
ALTER TABLE `realization_methods`
  MODIFY `id_realization_method` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT dla tabeli `states`
--
ALTER TABLE `states`
  MODIFY `id_state` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT dla tabeli `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id_ticket` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT dla tabeli `users_addresses`
--
ALTER TABLE `users_addresses`
  MODIFY `id_user_address` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT dla tabeli `users_data`
--
ALTER TABLE `users_data`
  MODIFY `id_user_data` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_id_state_fk1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`),
  ADD CONSTRAINT `carts_id_user_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`) ON DELETE CASCADE;

--
-- Ograniczenia dla tabeli `expanses`
--
ALTER TABLE `expanses`
  ADD CONSTRAINT `expanse_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ograniczenia dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_id_orders_fk1` FOREIGN KEY (`id_order`) REFERENCES `orders` (`id_order`),
  ADD CONSTRAINT `incomes_id_product_fk1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id_product`),
  ADD CONSTRAINT `incomes_id_userFrom_fk1` FOREIGN KEY (`id_userFrom`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `incomes_id_user_fk1` FOREIGN KEY (`id_sponsor`) REFERENCES `users` (`id_user`);

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
  ADD CONSTRAINT `orders_id_user_fk1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ograniczenia dla tabeli `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_id_category_fk1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `products_id_program_fk1` FOREIGN KEY (`id_program`) REFERENCES `programs` (`id_program`);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
