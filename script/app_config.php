<?php
// Настройка кодировки и уровня обработки ошибок
mb_internal_encoding("UTF-8");
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Параметры подключения к БД
require_once 'connection_params.php';
// Служебные функции
require_once 'finansist_scripts.php';


// Защита от xss
$request = xss($_REQUEST);
$post = xss($_POST);
$get = xss($_GET);
