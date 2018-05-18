<?php
	require_once('./script/cred_limit_scripts.php');
	
	if ((!isset($get["GSZ_Id"])) || (!ctype_digit($get["GSZ_Id"])))
	{
		$error_message = urlencode("Указаны некорректные параметры ГСЗ для удаления");
		redirect(HTML_PATH_GSZ_LIST_FORM.'?error='.$error_message);
	}
	$GSZ_item = new GSZ_item($get["GSZ_Id"]);
?>
<!-- =========================================================================== -->
<!DOCTYPE html>
<html>
<head>
	<title>Удаление ГСЗ <?=$GSZ_item->Brief_Name?> | Финансист онлайн</title>
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
				<h3>Удалить группу <?=$GSZ_item->Brief_Name?>?</h3>
			</div>
			<a class="btn btn-primary" href="<?=HTML_PATH_GSZ_SAVE_ITEM?>?action=delete&Id=<?=$GSZ_item->Id?>">Удалить</a> 
			<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
		</div> 
	</div> 	

</div> 
</body>
</html>