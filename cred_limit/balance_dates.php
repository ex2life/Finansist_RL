<?php
	require_once('./script/cred_limit_scripts.php');
	if ((!isset($get["Company_Id"])) || (!ctype_digit($get["Company_Id"])) )
	{
		$error_message = urlencode("Был указан некорректный код компании для ввода данных по балансу");
		redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM.'?error='.$error_message);
	}
    $company = new Company_Item($get["Company_Id"]);

    $GSZ = new GSZ_Item($company->GSZ_Id);
	$Balance_Dates = get_Balance_Dates($GSZ->Date_calc_limit, $company->Is_Corporation);
	$error_message = get_error_message();

	$url_param['GSZ_Id'] = $company->GSZ_Id;
	if ($Balance_Dates[0] < $company->Date_Begin_Work) 
	{
		$url_param['warning'] = "{$company->Name} работает менее 6 полных месяцев, в расчете кредитного лимита участвовать не будет";
		$url = HTML_PATH_FINANCE_COMPANY_LIST_FORM."?".http_build_query($url_param);
		redirect($url);
	};

?>
<!-- ==================================================================================================== -->
<!DOCTYPE html>
<html>
<head>
	<title>Ввод баланса <?=$company->Name?> | Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="/css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="/css/style.css" rel="stylesheet"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container">
		<header>
			<h2 class="text-center">БАЛАНС ОРГАНИЗАЦИИ</h2>
		</header>

		<div class="jumbotron">
			<div id="error_message_div" class="alert alert-danger" role="alert">
				<span id="error_message"><?=$error_message?></span>
				<button id="btnError_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>

            <h3><?=$company->Name?></h3>
            <h4>ИНН: <?=$company->INN?></h4>
            <h4>Организационно-правовая форма: <?=$company->OPF?></h4>
            <h4>Дата начала деятельности: <?=$company->Date_Begin_Work?></h4>
            <h4>Дата расчета лимита: <?=$GSZ->Date_calc_limit?></h4>
            <a class="btn btn-primary btn-lg"  
                href="<?=HTML_PATH_BALANCE_FORM?>?Company_Id=<?=$company->Id?>&date=<?=$Balance_Dates[0]?>">
                Баланс на <?=$Balance_Dates[0]?></a>
            <a class="btn btn-primary btn-lg"  
                href="<?=HTML_PATH_BALANCE_FORM?>?Company_Id=<?=$company->Id?>&date=<?=$Balance_Dates[1]?>">
                Баланс на <?=$Balance_Dates[1]?></a>
            <a class="btn btn-primary btn-lg"  
                href="<?=HTML_PATH_BALANCE_FORM?>?Company_Id=<?=$company->Id?>&date=<?=$Balance_Dates[2]?>">
                Баланс на <?=$Balance_Dates[2]?></a>
            <a class="btn btn-warning btn-lg" href="<?=HTML_PATH_FINANCE_COMPANY_LIST_FORM?>?GSZ_Id=<?=$company->GSZ_Id?>">Вернуться</a>
		</div>
	</div>
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="js/cred_limit.js"></script>

</body>
</html>