<?php
	require_once('script/cred_limit_scripts.php');

	if ((!isset($get["Company_Id"])) || (!ctype_digit($get["Company_Id"])))
	{
		$error_message = urlencode("Указаны некорректные параметры удаления компании из ГСЗ");
		redirect(HTML_PATH_GSZ_LIST_FORM.'?error='.$error_message);
	}
	
	$company = new Company_item($get["Company_Id"]);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Удаление <?=$company->Name?> из ГСЗ <?=$company->GSZ_Name?> | Финансист онлайн</title>
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
			<h2 class="text-center">ГРУППЫ СВЯЗАННЫХ ЗАЕМЩИКОВ</h2>
		</header>
		<div class="jumbotron">
			<div class="alert alert-info" role="alert">
				<h3>Удалить компанию <?=$company->Name?> (ИНН <?=$company->INN?>) из ГСЗ <?=$company->GSZ_Name?>?</h3>
			</div>
			<a class="btn btn-primary" href="<?=HTML_PATH_COMPANY_SAVE_ITEM?>?action=delete&Company_Id=<?=$company->Id?>&GSZ_Id=<?=$company->GSZ_Id?>">Удалить</a>
			<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
		</div> 
	</div> 	
	
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script> 
	<script type="text/javascript" src="js/cred_limit.js"></script>
</body>
</html>