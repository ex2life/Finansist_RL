-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 19 2018 г., 02:00
-- Версия сервера: 5.7.22-0ubuntu18.04.1
-- Версия PHP: 7.2.7-0ubuntu0.18.04.2

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
  `id_user` bigint(20) UNSIGNED NOT NULL,
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
  `INN` bigint(20) UNSIGNED NOT NULL,
  `GSZ_Id` tinyint(2) UNSIGNED NOT NULL,
  `User_Id` bigint(20) UNSIGNED NOT NULL,
  `Name` char(150) NOT NULL,
  `OPF_Id` tinyint(2) UNSIGNED NOT NULL,
  `SNO_Id` tinyint(2) UNSIGNED NOT NULL,
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

--
-- Дамп данных таблицы `Corp_Balance_Articles`
--

INSERT INTO `Corp_Balance_Articles` (`Id`, `Company_Id`, `Date_Balance`, `Balance_Part`, `Is_Section`, `Section_Code`, `Code`, `Has_Children`, `Parent_Code`, `Description`, `Is_Sum_Section`, `Is_Sum_Part`, `Value`) VALUES
(1, 0, '0000-00-00', 1, 0, '11', '1110', 1, '0', 'Нематериальные активы', 0, 0, 0),
(2, 0, '0000-00-00', 1, 0, '11', '11101', 0, '1110', 'Нематериальные активы в организации', 0, 0, 0),
(3, 0, '0000-00-00', 1, 0, '11', '11102', 0, '1110', 'Приобретение нематериальных активов', 0, 0, 0),
(4, 0, '0000-00-00', 1, 0, '11', '1120', 1, '0', 'Результаты исследований и разработок', 0, 0, 0),
(5, 0, '0000-00-00', 1, 0, '11', '11201', 0, '1120', 'Расходы на научно-исследовательские, опытно-конструкторские и технологические работы', 0, 0, 0),
(6, 0, '0000-00-00', 1, 0, '11', '11202', 0, '1120', 'Выполнение научно-исследовательских, опытно-конструкторских и технологических работ', 0, 0, 0),
(7, 0, '0000-00-00', 1, 0, '11', '1130', 0, '0', 'Нематериальные поисковые активы', 0, 0, 0),
(8, 0, '0000-00-00', 1, 0, '11', '1140', 0, '0', 'Материальные поисковые активы', 0, 0, 0),
(9, 0, '0000-00-00', 1, 0, '11', '1150', 1, '0', 'Основные средства', 0, 0, 0),
(10, 0, '0000-00-00', 1, 0, '11', '11501', 0, '1150', 'Основные средства в организации', 0, 0, 0),
(11, 0, '0000-00-00', 1, 0, '11', '11502', 0, '1150', 'Объекты недвижимости, права собственности на которые не зарегистрированы', 0, 0, 0),
(12, 0, '0000-00-00', 1, 0, '11', '11503', 0, '1150', 'Оборудование к установке', 0, 0, 0),
(13, 0, '0000-00-00', 1, 0, '11', '11504', 0, '1150', 'Приобретение земельных участков', 0, 0, 0),
(14, 0, '0000-00-00', 1, 0, '11', '11505', 0, '1150', 'Приобретение объектов природопользования', 0, 0, 0),
(15, 0, '0000-00-00', 1, 0, '11', '11506', 0, '1150', 'Строительство объектов основных средств', 0, 0, 0),
(16, 0, '0000-00-00', 1, 0, '11', '11507', 0, '1150', 'Приобретение объектов основных средств', 0, 0, 0),
(17, 0, '0000-00-00', 1, 0, '11', '11508', 0, '1150', 'Расходы будущих периодов', 0, 0, 0),
(18, 0, '0000-00-00', 1, 0, '11', '11509', 0, '1150', 'Арендованное имущество', 0, 0, 0),
(19, 0, '0000-00-00', 1, 0, '11', '1160', 1, '0', 'Доходные вложения в материальные ценности', 0, 0, 0),
(20, 0, '0000-00-00', 1, 0, '11', '11601', 0, '1160', 'Материальные ценности в организации', 0, 0, 0),
(21, 0, '0000-00-00', 1, 0, '11', '11602', 0, '1160', 'Материальные ценности предоставленные во временное владение и пользование', 0, 0, 0),
(22, 0, '0000-00-00', 1, 0, '11', '11603', 0, '1160', 'Материальные ценности предоставленные во временное пользование', 0, 0, 0),
(23, 0, '0000-00-00', 1, 0, '11', '11604', 0, '1160', 'Прочие доходные вложения', 0, 0, 0),
(24, 0, '0000-00-00', 1, 0, '11', '1170', 0, '0', 'Финансовые вложения', 0, 0, 0),
(25, 0, '0000-00-00', 1, 0, '11', '1180', 0, '0', 'Отложенные налоговые активы', 0, 0, 0),
(26, 0, '0000-00-00', 1, 0, '11', '1190', 1, '0', 'Прочие внеоборотные активы', 0, 0, 0),
(27, 0, '0000-00-00', 1, 0, '11', '11901', 0, '1190', 'Перевод молодняка животных в основное стадо', 0, 0, 0),
(28, 0, '0000-00-00', 1, 0, '11', '11902', 0, '1190', 'Приобретение взрослых животных', 0, 0, 0),
(29, 0, '0000-00-00', 1, 0, '11', '11903', 0, '1190', 'Расходы будущих периодов', 0, 0, 0),
(30, 0, '0000-00-00', 1, 0, '11', '1100', 0, '0', 'Итого по разделу I', 1, 0, 0),
(31, 0, '0000-00-00', 1, 0, '12', '1210', 1, '0', 'Запасы', 0, 0, 0),
(32, 0, '0000-00-00', 1, 0, '12', '12101', 0, '1210', 'Материалы', 0, 0, 0),
(33, 0, '0000-00-00', 1, 0, '12', '12102', 0, '1210', 'Товары', 0, 0, 0),
(34, 0, '0000-00-00', 1, 0, '12', '12103', 0, '1210', 'Готовая продукция', 0, 0, 0),
(35, 0, '0000-00-00', 1, 0, '12', '1220', 0, '0', 'Налог на добавленную стоимость по приобретенным ценностям', 0, 0, 0),
(36, 0, '0000-00-00', 1, 0, '12', '1230', 1, '0', 'Дебиторская задолженность', 0, 0, 0),
(37, 0, '0000-00-00', 1, 0, '12', '12301', 0, '1230', 'Расчеты с поставщиками и подрядчиками', 0, 0, 0),
(38, 0, '0000-00-00', 1, 0, '12', '12302', 0, '1230', 'Расчеты с покупателями и заказчиками', 0, 0, 0),
(39, 0, '0000-00-00', 1, 0, '12', '12303', 0, '1230', 'Расчеты с подотчетными лицами', 0, 0, 0),
(40, 0, '0000-00-00', 1, 0, '12', '12304', 0, '1230', 'Расчеты с персоналом по прочим операциям', 0, 0, 0),
(41, 0, '0000-00-00', 1, 0, '12', '12305', 0, '1230', 'Расчеты с разными дебиторами и кредиторами', 0, 0, 0),
(42, 0, '0000-00-00', 1, 0, '12', '12306', 0, '1230', 'Расходы будущих периодов', 0, 0, 0),
(43, 0, '0000-00-00', 1, 0, '12', '12307', 0, '1230', 'Оценочные обязательства', 0, 0, 0),
(44, 0, '0000-00-00', 1, 0, '12', '1240', 0, '0', 'Финансовые вложения (за исключением денежных эквивалентов)', 0, 0, 0),
(45, 0, '0000-00-00', 1, 0, '12', '1250', 1, '0', 'Денежные средства и денежные эквиваленты', 0, 0, 0),
(46, 0, '0000-00-00', 1, 0, '12', '12501', 0, '1250', 'Касса организации', 0, 0, 0),
(47, 0, '0000-00-00', 1, 0, '12', '12502', 0, '1250', 'Расчетные счета', 0, 0, 0),
(48, 0, '0000-00-00', 1, 0, '12', '1260', 0, '0', 'Прочие оборотные активы', 0, 0, 0),
(49, 0, '0000-00-00', 1, 0, '12', '1200', 0, '0', 'Итого по разделу II', 1, 0, 0),
(50, 0, '0000-00-00', 1, 0, '16', '1600', 0, '0', 'БАЛАНС', 0, 1, 0),
(51, 0, '0000-00-00', 2, 0, '13', '1310', 0, '0', 'Уставный капитал (складочный капитал, уставный фонд, вклады товарищей)', 0, 0, 0),
(52, 0, '0000-00-00', 2, 0, '13', '1320', 0, '0', 'Собственные акции, выкупленные у акционеров', 0, 0, 0),
(53, 0, '0000-00-00', 2, 0, '13', '1340', 0, '0', 'Переоценка внеоборотных активов', 0, 0, 0),
(54, 0, '0000-00-00', 2, 0, '13', '1350', 0, '0', 'Добавочный капитал (без переоценки)', 0, 0, 0),
(55, 0, '0000-00-00', 2, 0, '13', '1360', 0, '0', 'Резервный капитал', 0, 0, 0),
(56, 0, '0000-00-00', 2, 0, '13', '1370', 0, '0', 'Нераспределенная прибыль (непокрытый убыток)', 0, 0, 0),
(57, 0, '0000-00-00', 2, 0, '13', '1300', 0, '0', 'Итого по разделу III', 1, 0, 0),
(58, 0, '0000-00-00', 2, 0, '14', '1410', 0, '0', 'Заемные средства', 0, 0, 0),
(59, 0, '0000-00-00', 2, 0, '14', '1420', 0, '0', 'Отложенные налоговые обязательства', 0, 0, 0),
(60, 0, '0000-00-00', 2, 0, '14', '1430', 0, '0', 'Оценочные обязательства', 0, 0, 0),
(61, 0, '0000-00-00', 2, 0, '14', '1450', 0, '0', 'Прочие обязательства', 0, 0, 0),
(62, 0, '0000-00-00', 2, 0, '14', '1400', 0, '0', 'Итого по разделу IV', 1, 0, 0),
(63, 0, '0000-00-00', 2, 0, '15', '1510', 1, '0', 'Заемные средства', 0, 0, 0),
(64, 0, '0000-00-00', 2, 0, '15', '15101', 0, '1510', 'Краткосрочные займы', 0, 0, 0),
(65, 0, '0000-00-00', 2, 0, '15', '15102', 0, '1510', 'Проценты по краткосрочным займам', 0, 0, 0),
(66, 0, '0000-00-00', 2, 0, '15', '1520', 1, '0', 'Кредиторская задолженность', 0, 0, 0),
(67, 0, '0000-00-00', 2, 0, '15', '15201', 0, '1520', 'Расчеты с поставщиками и подрядчиками', 0, 0, 0),
(68, 0, '0000-00-00', 2, 0, '15', '15202', 0, '1520', 'Расчеты с покупателями и заказчиками', 0, 0, 0),
(69, 0, '0000-00-00', 2, 0, '15', '15203', 0, '1520', 'Расчеты по налогам и сборам', 0, 0, 0),
(70, 0, '0000-00-00', 2, 0, '15', '15204', 0, '1520', 'Расчеты по социальному страхованию и обеспечению', 0, 0, 0),
(71, 0, '0000-00-00', 2, 0, '15', '15205', 0, '1520', 'Расчеты с персоналом по оплате труда', 0, 0, 0),
(72, 0, '0000-00-00', 2, 0, '15', '15206', 0, '1520', 'Расчеты с подотчетными лицами', 0, 0, 0),
(73, 0, '0000-00-00', 2, 0, '15', '15207', 0, '1520', 'Расчеты с персоналом по прочим операциям', 0, 0, 0),
(74, 0, '0000-00-00', 2, 0, '15', '15208', 0, '1520', 'Задолженность участникам (учредителям) по выплате доходов', 0, 0, 0),
(75, 0, '0000-00-00', 2, 0, '15', '15209', 0, '1520', 'Расчеты с разными дебиторами и кредиторами', 0, 0, 0),
(76, 0, '0000-00-00', 2, 0, '15', '1530', 0, '0', 'Доходы будущих периодов', 0, 0, 0),
(77, 0, '0000-00-00', 2, 0, '15', '1540', 1, '0', 'Оценочные обязательства', 0, 0, 0),
(78, 0, '0000-00-00', 2, 0, '15', '15401', 0, '1540', 'Оценочные обязательства по вознаграждениям работников', 0, 0, 0),
(79, 0, '0000-00-00', 2, 0, '15', '15402', 0, '1540', 'Резервы предстоящих расходов прочие', 0, 0, 0),
(80, 0, '0000-00-00', 2, 0, '15', '1550', 0, '0', 'Прочие обязательства', 0, 0, 0),
(81, 0, '0000-00-00', 2, 0, '15', '1500', 0, '0', 'Итого по разделу V', 1, 0, 0),
(82, 0, '0000-00-00', 2, 0, '17', '1700', 0, '0', 'БАЛАНС', 0, 1, 0),
(83, 0, '0000-00-00', 1, 1, '11', '11', 0, '0', 'I. ВНЕОБОРОТНЫЕ АКТИВЫ', 0, 0, 0),
(84, 0, '0000-00-00', 1, 1, '12', '12', 0, '0', 'II. ОБОРОТНЫЕ АКТИВЫ', 0, 0, 0),
(85, 0, '0000-00-00', 2, 1, '13', '13', 0, '0', 'III. КАПИТАЛ И РЕЗЕРВЫ', 0, 0, 0),
(86, 0, '0000-00-00', 2, 1, '14', '14', 0, '0', 'IV. ДОЛГОСРОЧНЫЕ ОБЯЗАТЕЛЬСТВА', 0, 0, 0),
(87, 0, '0000-00-00', 2, 1, '15', '15', 0, '0', 'V. КРАТКОСРОЧНЫЕ ОБЯЗАТЕЛЬСТВА', 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `Corp_Balance_Results`
--

CREATE TABLE `Corp_Balance_Results` (
  `Id` bigint(10) UNSIGNED NOT NULL,
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
  `User_Id` bigint(20) UNSIGNED NOT NULL,
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

--
-- Дамп данных таблицы `OPF`
--

INSERT INTO `OPF` (`Id`, `Brief_Name`, `Full_Name`, `INN_Length`, `Is_Corporation`) VALUES
(1, 'ИП', 'Индивидуальный предприниматель', 12, 0),
(2, 'ООО', 'Общество с ограниченной ответственностью', 10, 1),
(3, 'АО', 'Акционерное общество', 10, 1),
(4, 'ПАО', 'Публичное акционерное общество', 10, 1);

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

--
-- Дамп данных таблицы `SNO`
--

INSERT INTO `SNO` (`Id`, `Brief_Name`, `Full_Name`, `Cred_Limit_Affect`) VALUES
(1, 'OCНО', 'Общая система налогообложения, уплачивается НДС', 1),
(2, 'УСН/Д-Р', 'Упрощенная система налогообложения, объект обложения - доходы, уменьшенные на величину расходов', 1),
(3, 'УСН/Д', 'Упрощенная система налогообложения, объект обложения - доходы', 1),
(4, 'ЕСХН', 'Единый сельхозналог', 1),
(5, 'ЕНВД', 'Единый налог на вмененный доход', 0);

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
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `vk` (`vk`),
  ADD UNIQUE KEY `google` (`google`),
  ADD UNIQUE KEY `telegram` (`telegram`);

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
  ADD UNIQUE KEY `INN` (`INN`,`GSZ_Id`),
  ADD KEY `company_OPF` (`OPF_Id`),
  ADD KEY `company_SNO` (`SNO_Id`),
  ADD KEY `company_user_id` (`User_Id`),
  ADD KEY `company_GZS_id` (`GSZ_Id`);

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
  ADD PRIMARY KEY (`Id`),
  ADD KEY `GSZ_User_id` (`User_Id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nickname` (`nickname`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `calc_limit_dates`
--
ALTER TABLE `calc_limit_dates`
  MODIFY `Id` tinyint(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `company`
--
ALTER TABLE `company`
  MODIFY `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `Corp_Balance_Articles`
--
ALTER TABLE `Corp_Balance_Articles`
  MODIFY `Id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT для таблицы `Corp_Balance_Results`
--
ALTER TABLE `Corp_Balance_Results`
  MODIFY `Id` bigint(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `GSZ`
--
ALTER TABLE `GSZ`
  MODIFY `Id` tinyint(2) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auth_social`
--
ALTER TABLE `auth_social`
  ADD CONSTRAINT `auth_social_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `calc_limit_dates`
--
ALTER TABLE `calc_limit_dates`
  ADD CONSTRAINT `calc_limit_dates_ibfk_1` FOREIGN KEY (`GSZ_Id`) REFERENCES `GSZ` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `company`
--
ALTER TABLE `company`
  ADD CONSTRAINT `company_OPF` FOREIGN KEY (`OPF_Id`) REFERENCES `OPF` (`Id`),
  ADD CONSTRAINT `company_SNO` FOREIGN KEY (`SNO_Id`) REFERENCES `SNO` (`Id`),
  ADD CONSTRAINT `company_ibfk_1` FOREIGN KEY (`GSZ_Id`) REFERENCES `GSZ` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `company_ibfk_2` FOREIGN KEY (`User_Id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `Corp_Balance_Results`
--
ALTER TABLE `Corp_Balance_Results`
  ADD CONSTRAINT `Corp_Balance_Results_ibfk_1` FOREIGN KEY (`Company_Id`) REFERENCES `company` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `GSZ`
--
ALTER TABLE `GSZ`
  ADD CONSTRAINT `GSZ_ibfk_1` FOREIGN KEY (`User_Id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
