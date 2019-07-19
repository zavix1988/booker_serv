-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июл 19 2019 г., 16:42
-- Версия сервера: 10.1.40-MariaDB-0ubuntu0.18.04.1
-- Версия PHP: 7.2.19-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `user12`
--

-- --------------------------------------------------------

--
-- Структура таблицы `booker_events`
--

CREATE TABLE `booker_events` (
  `id` int(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  `room_id` int(25) NOT NULL,
  `create_time` timestamp NULL DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `booker_events`
--

INSERT INTO `booker_events` (`id`, `user_id`, `room_id`, `create_time`, `start_time`, `end_time`, `note`) VALUES
(1, 2, 1, '2019-07-16 12:17:00', '2019-07-17 05:00:00', '2019-07-17 06:00:00', ''),
(3, 2, 1, '2019-07-16 12:34:00', '2019-08-01 05:00:00', '2019-08-01 06:00:00', ''),
(4, 2, 1, '2019-07-17 08:00:00', '2019-07-18 06:00:00', '2019-07-18 07:00:00', '');

-- --------------------------------------------------------

--
-- Структура таблицы `booker_parentroom_room`
--

CREATE TABLE `booker_parentroom_room` (
  `parent_room` int(100) NOT NULL,
  `room` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Структура таблицы `booker_rooms`
--

CREATE TABLE `booker_rooms` (
  `id` int(25) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `booker_rooms`
--

INSERT INTO `booker_rooms` (`id`, `name`) VALUES
(1, 'Conference room'),
(2, 'Game room'),
(3, 'Sport room');

-- --------------------------------------------------------

--
-- Структура таблицы `booker_users`
--

CREATE TABLE `booker_users` (
  `id` int(25) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin','','') NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `login` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `booker_users`
--

INSERT INTO `booker_users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `is_active`, `login`, `token`) VALUES
(1, 'admin', 'admin', 'admin@localhost.loc', '$2y$10$tBaO9wam/h6zGdSJIf/yTuj5FEGrkeYxDUWSl3QUJTBY/yrZwOG0W', 'admin', 1, 'admin', ''),
(2, 'Алексей', 'Жуков', 'zavix1988@gmail.com', '$2y$10$8J1aduVkqQ1zhf4ncdN39OTdqy5HlO0DAgxUFNOkd1HJGDKX9schG', 'user', 1, 'zavix', '0ed6c62693eb0ffd8b696ac52f8aa043'),
(3, 'Алекс', 'Жуков', 'zavix1988@gmail.com', '$2y$10$Fa5MFJsiqoS2SK92XSnQTOv6ybDFzhQ3G03DP3fCuC2nYjgatoJte', 'user', 1, 'zavix1', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `booker_events`
--
ALTER TABLE `booker_events`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `booker_rooms`
--
ALTER TABLE `booker_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `booker_users`
--
ALTER TABLE `booker_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `booker_events`
--
ALTER TABLE `booker_events`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `booker_rooms`
--
ALTER TABLE `booker_rooms`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `booker_users`
--
ALTER TABLE `booker_users`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
