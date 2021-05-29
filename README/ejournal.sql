-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 30 2021 г., 00:00
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ejournal`
--

-- --------------------------------------------------------

--
-- Структура таблицы `discipline`
--

CREATE TABLE `discipline` (
  `id` int NOT NULL,
  `name_discipline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `discipline`
--

INSERT INTO `discipline` (`id`, `name_discipline`) VALUES
(2, 'Геометрия'),
(5, 'Алгебра'),
(6, 'Технология программирования'),
(7, 'Программирование на Python'),
(8, 'Мат анализ');

-- --------------------------------------------------------

--
-- Структура таблицы `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210520230508', '2021-05-21 02:06:16', 8453),
('DoctrineMigrations\\Version20210524234620', '2021-05-25 02:46:32', 662);

-- --------------------------------------------------------

--
-- Структура таблицы `group`
--

CREATE TABLE `group` (
  `id` int NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `group`
--

INSERT INTO `group` (`id`, `group_name`) VALUES
(4, 'МТ-401'),
(8, 'МП-401'),
(9, 'МП-402'),
(10, 'МТ-402'),
(11, 'МН-401'),
(13, 'МН-402'),
(14, 'МТ-301'),
(15, 'МТ-302'),
(16, 'МП-301'),
(17, 'МП-302'),
(18, 'МН-301'),
(19, 'МН-302');

-- --------------------------------------------------------

--
-- Структура таблицы `plus`
--

CREATE TABLE `plus` (
  `id` int NOT NULL,
  `operation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `plus`
--

INSERT INTO `plus` (`id`, `operation`) VALUES
(1, '+'),
(2, '-');

-- --------------------------------------------------------

--
-- Структура таблицы `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `student_id` int NOT NULL,
  `group_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `student`
--

INSERT INTO `student` (`id`, `student_id`, `group_id`) VALUES
(9, 9, 4),
(10, 14, 4),
(11, 13, 18);

-- --------------------------------------------------------

--
-- Структура таблицы `teacher`
--

CREATE TABLE `teacher` (
  `id` int NOT NULL,
  `teacher_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `teacher`
--

INSERT INTO `teacher` (`id`, `teacher_id`) VALUES
(3, 13),
(4, 14);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`, `middle_name`, `is_verified`, `image`) VALUES
(1, 'test@test.com', '[\"ROLE_ADMIN\"]', '$argon2id$v=19$m=65536,t=4,p=1$pE73BmJdSdyqm5VVljhJnA$dUSD9TIeR7s1H2ivNjWHeq1HMpwbUuzO+8C9ZhGJ5+E', 'Андрей', 'Королев', 'Дмитриевич', 1, '60b00e3aa926b.gif'),
(9, 'yy@yy', '[\"ROLE_STUDENT\"]', '$argon2id$v=19$m=65536,t=4,p=1$UpcMCfN79qLXRdmkrZKijg$Evw2anb7hmzVX71LMMpnPJCBNtW33VtrdqpiJepMjoY', 'Тестовый', 'Студент', 'Тестович', 1, NULL),
(13, 'tt@tt', '[\"ROLE_ASTUDENT_ATEACHER\"]', '$argon2id$v=19$m=65536,t=4,p=1$WDuPC/h6N831/ycwOAAMAQ$co80ULBjyQovC47HeQLYJuHazPBNcWqltlYRJNIO0U0', 'Тестовый', 'Препод', 'Тестович', 1, '60afba4d8c9b5.jpg'),
(14, 'kk@kk', '[\"ROLE_ASTUDENT_ATEACHER\"]', '$argon2id$v=19$m=65536,t=4,p=1$Iq7uQCP5b1ajn1HgEKL18Q$gGqnKnwFMFW63ZOtR0UEu1ircL00Q4mZx5OYzilgSlw', 'Имямя', 'Фамилия', 'Отчествович', 1, '60ae79cd2a879.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `visit`
--

CREATE TABLE `visit` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `discipline_id` int DEFAULT NULL,
  `plus_id` int DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `visit`
--

INSERT INTO `visit` (`id`, `student_id`, `teacher_id`, `discipline_id`, `plus_id`, `date`) VALUES
(10, 9, 3, 5, 2, '2021-05-26 15:13:56'),
(11, 9, 3, 5, 1, '2021-05-26 15:13:56'),
(13, 9, 3, 5, 1, '2021-05-26 16:04:22'),
(14, 9, 3, 5, 1, '2021-05-26 16:04:27'),
(15, 9, 3, 5, 2, '2021-05-26 16:06:58'),
(16, 10, 3, 5, 2, '2021-05-24 16:07:01'),
(17, 10, 3, 5, 2, '2021-05-23 16:07:03'),
(18, 10, 3, 5, 2, '2021-05-22 16:07:08'),
(19, 10, 3, 5, 1, '2021-05-21 16:07:17'),
(20, 10, 3, 5, 1, '2021-05-24 16:07:25'),
(21, 10, 3, 5, 1, '2021-05-26 16:07:29'),
(25, 11, 3, 5, 1, '2021-05-26 16:48:29'),
(27, 11, 3, 5, 2, '2021-05-26 19:49:41'),
(28, 9, 3, 5, 1, '2021-05-27 19:28:53'),
(29, 9, 3, 5, 2, '2021-05-27 19:28:55'),
(30, 10, 3, 5, 1, '2021-05-27 21:48:23'),
(31, 10, 3, 5, 2, '2021-05-27 21:48:24');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `discipline`
--
ALTER TABLE `discipline`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Индексы таблицы `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `plus`
--
ALTER TABLE `plus`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B723AF33CB944F1A` (`student_id`),
  ADD KEY `IDX_B723AF33FE54D947` (`group_id`);

--
-- Индексы таблицы `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B0F6A6D541807E1D` (`teacher_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Индексы таблицы `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_437EE939CB944F1A` (`student_id`),
  ADD KEY `IDX_437EE93941807E1D` (`teacher_id`),
  ADD KEY `IDX_437EE939A5522701` (`discipline_id`),
  ADD KEY `IDX_437EE939DF8ABC9B` (`plus_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `discipline`
--
ALTER TABLE `discipline`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `group`
--
ALTER TABLE `group`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `plus`
--
ALTER TABLE `plus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `FK_B723AF33CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_B723AF33FE54D947` FOREIGN KEY (`group_id`) REFERENCES `group` (`id`);

--
-- Ограничения внешнего ключа таблицы `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `FK_B0F6A6D541807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`);

--
-- Ограничения внешнего ключа таблицы `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `FK_437EE93941807E1D` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`),
  ADD CONSTRAINT `FK_437EE939A5522701` FOREIGN KEY (`discipline_id`) REFERENCES `discipline` (`id`),
  ADD CONSTRAINT `FK_437EE939CB944F1A` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `FK_437EE939DF8ABC9B` FOREIGN KEY (`plus_id`) REFERENCES `plus` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
