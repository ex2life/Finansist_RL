<?php
	require_once("cred_limit_scripts.php");

    //Проверяем, является ли параметр Id целым числом
    if ((!isset($_POST['GSZ_Id'])) || (!ctype_digit($_POST['GSZ_Id'])))
    {
        $error_message = urlencode("Указаны некорректные параметры для обновления даты расчета лимита");
        redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM."?error={$error_message}");
    }
    
    if ((!isset($_POST['Calc_limit_dates_Id'])) || (!ctype_digit($_POST['Calc_limit_dates_Id'])))
    {
        $error_message = urlencode("Указаны некорректные параметры для обновления даты расчета лимита");
        redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM."?error={$error_message}");
    }

    $data = [];
    $data["Date_calc_limit"] = $_POST['Date_calc_limit'];
    $data["GSZ_Id"] = $_POST['GSZ_Id'];
    $result = setRow("calc_limit_dates", $_POST['Calc_limit_dates_Id'], $data);
    
    if (!$result) $error_message = urlencode("Ошибка при изменении даты расчета кредитного лимита.");
			
    $result ? redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM) : redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM."?error={$error_message}");
