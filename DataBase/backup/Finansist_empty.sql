-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 19 2018 г., 20:56
-- Версия сервера: 5.6.38
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `Finansist`
--

-- --------------------------------------------------------

--
-- Структура таблицы `auth_social`
--

CREATE TABLE `auth_social` (
  `id_user` int(11) NOT NULL,
  `vk` char(225) DEFAULT NULL,
  `google` char(225) DEFAULT NULL,
  `telegram` char(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `calc_limit_dates`
--

CREATE TABLE `calc_limit_dates` (
  `Id` tinyint(2) NOT NULL,
  `Date_calc_limit` date NOT NULL,
  `GSZ_Id` tinyint(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `company`
--

CREATE TABLE `company` (
  `Id` int(10) UNSIGNED NOT NULL,
  `INN` bigint(20) NOT NULL,
  `GSZ_Id` tinyint(2) NOT NULL,
  `User_Id` int(10) NOT NULL,
  `Name` char(150) NOT NULL,
  `OPF_Id` tinyint(2) NOT NULL,
  `SNO_Id` tinyint(2) NOT NULL,
  `Date_Registr` date DEFAULT NULL,
  `Date_Begin_Work` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `Corp_Balance_Articles`
--

CREATE TABLE `Corp_Balance_Articles` (
  `Id` tinyint(3) UNSIGNED NOT NULL,
  `Company_Id` int(10) UNSIGNED NOT NULL,
  `Date_Balance` date NOT NULL,
  `Balance_Part` tinyint(1) NOT NULL COMMENT '1 - актив, 2 - пассив',
  `Is_Section` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Признак того, что строка является разделом баланса',
  `Section_Code` char(5) NOT NULL COMMENT 'Код раздела',
  `Code` char(5) NOT NULL COMMENT 'Код статьи',
  `Has_Children` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - есть подкоды (вычисляемый), 0 - нет',
  `Parent_Code` char(5) DEFAULT '0' COMMENT 'Код статьи-родителя',
  `Description` char(100) NOT NULL,
  `Is_Sum_Section` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - сумма раздела',
  `Is_Sum_Part` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - сумма актива или пассива',
  `Value` float NOT NULL DEFAULT '0' COMMENT 'Сумма по статье баланса'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статьи баланса для организация';

-- --------------------------------------------------------

--
-- Структура таблицы `Corp_Balance_Results`
--

CREATE TABLE `Corp_Balance_Results` (
  `Id` tinyint(3) UNSIGNED NOT NULL,
  `Company_Id` int(10) UNSIGNED NOT NULL,
  `Date_Balance` date NOT NULL,
  `Balance_Part` tinyint(1) NOT NULL COMMENT '1 - актив, 2 - пассив',
  `Is_Section` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'Признак того, что строка является разделом баланса',
  `Section_Code` char(5) NOT NULL COMMENT 'Код раздела',
  `Code` char(5) NOT NULL COMMENT 'Код статьи',
  `Has_Children` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '1 - есть подкоды (вычисляемый), 0 - нет',
  `Parent_Code` char(5) DEFAULT '0' COMMENT 'Код статьи-родителя',
  `Description` char(100) NOT NULL,
  `Is_Sum_Section` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - сумма раздела',
  `Is_Sum_Part` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 - сумма актива или пассива',
  `Value` float NOT NULL DEFAULT '0' COMMENT 'Сумма по статье баланса'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статьи баланса для организация';

-- --------------------------------------------------------

--
-- Структура таблицы `forget_password`
--

CREATE TABLE `forget_password` (
  `email_user` varchar(225) NOT NULL,
  `time` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `GSZ`
--

CREATE TABLE `GSZ` (
  `Id` tinyint(2) UNSIGNED NOT NULL,
  `User_Id` int(15) NOT NULL,
  `Brief_Name` char(30) DEFAULT NULL,
  `Full_Name` char(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `not_end_registration`
--

CREATE TABLE `not_end_registration` (
  `id` varchar(225) NOT NULL,
  `social` char(20) NOT NULL,
  `hash` char(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `OPF`
--

CREATE TABLE `OPF` (
  `Id` tinyint(2) UNSIGNED NOT NULL,
  `Brief_Name` varchar(10) NOT NULL,
  `Full_Name` varchar(100) NOT NULL,
  `INN_Length` tinyint(2) UNSIGNED NOT NULL,
  `Is_Corporation` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Признак того, что компания является организацией (не ИП). У ИП=0, у других = 1.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `SNO`
--

CREATE TABLE `SNO` (
  `Id` tinyint(2) UNSIGNED NOT NULL,
  `Brief_Name` varchar(10) NOT NULL,
  `Full_Name` varchar(100) NOT NULL,
  `Cred_Limit_Affect` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` char(64) NOT NULL,
  `nickname` char(64) NOT NULL,
  `password` char(225) NOT NULL,
  `fullname` char(100) DEFAULT NULL,
  `newsletter` tinyint(1) DEFAULT NULL,
  `status_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `auth_social`
--
ALTER TABLE `auth_social`
  ADD PRIMARY KEY (`id_user`);

--
-- Индексы таблицы `calc_limit_dates`
--
ALTER TABLE `calc_limit_dates`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `GSZ_Id` (`GSZ_Id`);

--
-- Индексы таблицы `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `INN` (`INN`,`GSZ_Id`);

--
-- Индексы таблицы `Corp_Balance_Articles`
--
ALTER TABLE `Corp_Balance_Articles`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `Corp_Balance_Results`
--
ALTER TABLE `Corp_Balance_Results`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Company_Id` (`Company_Id`);

--
-- Индексы таблицы `forget_password`
--
ALTER TABLE `forget_password`
  ADD PRIMARY KEY (`email_user`);

--
-- Индексы таблицы `GSZ`
--
ALTER TABLE `GSZ`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `not_end_registration`
--
ALTER TABLE `not_end_registration`
  ADD PRIMARY KEY (`hash`);

--
-- Индексы таблицы `OPF`
--
ALTER TABLE `OPF`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `SNO`
--
ALTER TABLE `SNO`
  ADD PRIMARY KEY (`Id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`nickname`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `calc_limit_dates`
--
ALTER TABLE `calc_limit_dates`
  MODIFY `Id` tinyint(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `company`
--
ALTER TABLE `company`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `Corp_Balance_Articles`
--
ALTER TABLE `Corp_Balance_Articles`
  MODIFY `Id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT для таблицы `Corp_Balance_Results`
--
ALTER TABLE `Corp_Balance_Results`
  MODIFY `Id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `GSZ`
--
ALTER TABLE `GSZ`
  MODIFY `Id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `OPF`
--
ALTER TABLE `OPF`
  MODIFY `Id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `SNO`
--
ALTER TABLE `SNO`
  MODIFY `Id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `calc_limit_dates`
--
ALTER TABLE `calc_limit_dates`
  ADD CONSTRAINT `calc_limit_dates_ibfk_1` FOREIGN KEY (`GSZ_Id`) REFERENCES `GSZ` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Corp_Balance_Results`
--
ALTER TABLE `Corp_Balance_Results`
  ADD CONSTRAINT `corp_balance_results_ibfk_1` FOREIGN KEY (`Company_Id`) REFERENCES `company` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
