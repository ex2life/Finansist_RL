<?php
	require_once('./script/cred_limit_scripts.php');
	if ((!isset($get["GSZ_Id"])) || (!ctype_digit($get["GSZ_Id"])))
	{
		$error_message = urlencode("Указаны некорректные параметры ГСЗ для редактирования");
		redirect(HTML_PATH_GSZ_LIST_FORM.'?error='.$error_message);
	}
		
	$GSZ_item = new GSZ_item($get['GSZ_Id']);
?>
<!-- =================================================================================================== -->
<!DOCTYPE html>
<html>
<head>
	<title>Изменение данных о ГСЗ <?=$GSZ_item->Brief_Name?> | Финансист онлайн</title>
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
			<h3 class="text-center">Изменение данных</h3>
			<form name="edit_form" action="<?=HTML_PATH_GSZ_SAVE_ITEM?>?action=update" method="POST">
				<input type="hidden" name="Id" Id="Id" value=<?=$GSZ_item->Id?>>
			
				<div class="form-group">
					<label for="GSZ_Brief_Name">Название</label>
					<input type="text" class="form-control" name="GSZ_Brief_Name" id="GSZ_Brief_Name" maxlength="<?=MAX_LENGTH_GSZ_BRIEF_NAME?>" value="<?=$GSZ_item->Brief_Name?>">
				</div>
				<div class="form-group">
					<label for="GSZ_Full_Name">Описание</label>
					<input type="text" class="form-control" name="GSZ_Full_Name" id="GSZ_Full_Name" maxlength="<?=MAX_LENGTH_GSZ_FULL_NAME?>" value="<?=$GSZ_item->Full_Name?>">
				</div>
				<div class="form-group">
					<label>Компаний в группе: <?=$GSZ_item->NumberCompany?></label> <br>
					<label>Начало деятельности группы: <?=$GSZ_item->Date_Begin_Work?></label>
				</div>
					<button type="submit" class="btn btn-primary">Сохранить</button> 
				<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
			</form>
		</div> 
	</div> 


</body>
</html>