<?php
require_once("cred_limit_scripts.php");

if ((!isset($_POST['Company_Id'])) || (!ctype_digit($_POST['Company_Id'])))
{
    $error_message = urlencode("При сохранении баланса был указан некорректный идентификатор компании");
    redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM."?error={$error_message}");
}
if ((!isset($_POST['Balance_Date'])) || (!is_Date($_POST['Balance_Date'])))
{
    $error_message = urlencode("При сохранении баланса была указана некорректная дата баланса");
    redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM."?error={$error_message}");
}

$Company_Id = $_POST['Company_Id'];
$Balance_Date = $_POST['Balance_Date'];

$company = new Company_Item($Company_Id);
// Таблица Corp_Balance_Articles - структура статей баланса для предприятий
// Таблица Individ_Balance_Articles - структура статей баланса для ИП
$Balance_Articles_table = ($company->Is_Corporation ? 'Corp_Balance_Articles' : 'Individ_Balance_Articles');

// Удаляем записи по данной компании за эту дату в таблице Corp_Balance_Results
delete_Balance_Values($Company_Id, $Balance_Date);

// Записываем в Corp_Balance_Results по одной записи для каждого кода
foreach ($_POST as $code => $value) {
    // Обрабатываем в пришедших параметрах только коды статей баланса
    if (!is_numeric($code)) continue;
    
    // Получаем строку для данного кода из справочника статей баланса Corp_Balance_Articles
    $query = "SELECT * FROM `{$Balance_Articles_table}` WHERE `Code`='{$code}'";
    $data = getRow($query);

    // Меняем нужные поля (значения пришли из формы) и записываем эту строку в Corp_Balance_Results
    $data['Id'] = 0;
    $data['Value'] = $value;
    $data['Company_Id'] = $Company_Id;
    $data['Date_Balance'] = $Balance_Date;
    
    addRow("Corp_Balance_Results", $data);
}
$url_param = ['Company_Id' => $Company_Id, 'date' => $Balance_Date];
$url = HTML_PATH_BALANCE_FORM."?".http_build_query($url_param);
redirect($url);

