-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Июл 22 2019 г., 15:54
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
(3, 2, 1, '2019-07-16 12:34:00', '2019-08-01 05:00:00', '2019-08-01 06:00:00', ''),
(5, 1, 1, '2019-07-21 02:22:36', '2019-07-22 05:00:00', '2019-07-22 06:00:00', 'hjd'),
(6, 1, 1, '2019-07-21 15:58:33', '2019-07-22 07:00:00', '2019-07-22 08:00:00', 'xcvxc'),
(7, 1, 1, '2019-07-21 15:59:05', '2019-07-22 06:00:00', '2019-07-22 07:00:00', 'ghjfgjhf'),
(8, 1, 1, '2019-07-21 16:17:01', '2019-07-22 12:00:00', '2019-07-22 13:00:00', 'dfghdfgh'),
(9, 1, 1, '2019-07-21 20:52:12', '2019-07-23 05:15:00', '2019-07-23 06:15:00', 'sdgsd'),
(10, 1, 1, '2019-07-21 21:53:59', '2019-07-23 07:00:00', '2019-07-23 08:00:00', ''),
(11, 1, 1, '2019-07-21 21:53:59', '2019-07-30 07:00:00', '2019-07-30 08:00:00', ''),
(12, 1, 1, '2019-07-21 21:53:59', '2019-08-06 07:00:00', '2019-08-06 08:00:00', ''),
(13, 1, 1, '2019-07-22 05:34:00', '2019-07-24 07:00:00', '2019-07-24 08:00:00', 'jklhyjlohyu'),
(15, 1, 1, '2019-07-22 05:39:07', '2019-07-24 08:00:00', '2019-07-24 09:00:00', 'hjkghj'),
(16, 1, 1, '2019-07-22 05:41:12', '2019-07-29 05:00:00', '2019-07-29 06:00:00', 'hykotyui'),
(17, 1, 1, '2019-07-22 05:44:27', '2019-07-29 06:00:00', '2019-07-29 07:00:00', 'uyityuityu'),
(18, 1, 1, '2019-07-22 05:44:27', '2019-08-29 06:00:00', '2019-08-29 07:00:00', 'uyityuityu'),
(19, 3, 1, '2019-07-22 07:25:55', '2019-07-23 11:00:00', '2019-07-23 11:15:00', 'dfghdfgh');

-- --------------------------------------------------------

--
-- Структура таблицы `booker_parentroom_room`
--

CREATE TABLE `booker_parentroom_room` (
  `parent_room_id` int(100) NOT NULL,
  `room_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `booker_parentroom_room`
--

INSERT INTO `booker_parentroom_room` (`parent_room_id`, `room_id`) VALUES
(10, 11),
(10, 12),
(17, 18);

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
(1, 'admin', 'admin', 'admin@localhost.loc', '$2y$10$tBaO9wam/h6zGdSJIf/yTuj5FEGrkeYxDUWSl3QUJTBY/yrZwOG0W', 'admin', 1, 'admin', '001811be0a77f79d51852630abdefef8'),
(2, 'Алексей', 'Жуков', 'zavix1988@gmail.com', '$2y$10$8J1aduVkqQ1zhf4ncdN39OTdqy5HlO0DAgxUFNOkd1HJGDKX9schG', 'user', 1, 'zavix', ''),
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
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
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
