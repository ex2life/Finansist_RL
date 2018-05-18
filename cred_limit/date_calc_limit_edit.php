<?php
	require_once('script/cred_limit_scripts.php');
	$error_message = "NO_ERRORS";

	if ((!isset($get["GSZ_Id"])) || (!ctype_digit($get["GSZ_Id"])))
	{
		$error_message = urlencode("Указаны некорректные параметры ГСЗ для ввода даты расчета лимита");
		redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM.'?error='.$error_message);
	}	

	$GSZ_item = new GSZ_item($get['GSZ_Id']);

	$error_message = get_error_message();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Финансист онлайн - Финансовые данные ГСЗ</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="/css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="/css/style.css" rel="stylesheet"/> 
	<link rel="stylesheet" href="/css/themes/bootstrap.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container">
		<header>
			<h2 class="text-center">ГРУППЫ СВЯЗАННЫХ ЗАЕМЩИКОВ</h2>
		</header>
		<div class="jumbotron">
			<div id="error_message_div" class="alert alert-danger" role="alert">
				<span id="error_message"><?=$error_message?></span>
				<button id="btnError_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>

			<h3><?=$GSZ_item->Brief_Name?></h3>
			<h4>Дата начала деятельности: <?=$GSZ_item->Date_Begin_Work?></h4>
			<h4>Компаний в группе: <?=$GSZ_item->NumberCompany?></h4>
			
			<br>
			<form name="edit_form" id="edit_form" action="<?=HTML_PATH_DATE_CALC_LIMIT_SAVE?>" method="POST">
				<input type="hidden" name="Calc_limit_dates_Id" Id="Calc_limit_dates_Id" value="<?=$GSZ_item->Calc_limit_dates_Id?>">
				<input type="hidden" name="GSZ_Id" Id="GSZ_Id" value="<?=$GSZ_item->Id?>">

				<div class="form-group">
					<label for="Date_calc_limit">Дата расчета кредитного лимита</label>
					<input type="date" required class="form-control company_input" name="Date_calc_limit" id="Date_calc_limit" value="<?=$GSZ_item->Date_calc_limit?>" >
				</div>

				<button type="submit" class="btn btn-primary">Сохранить</button> 
				<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
			</form>
		</div> 
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script> 
	<script type="text/javascript" src="js/cred_limit.js"></script>
</body>
</html>