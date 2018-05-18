<?php
	require_once('script/cred_limit_scripts.php');
	$error_message = "NO_ERRORS";
	if ((!isset($get["GSZ_Id"])) || (!ctype_digit($_GET["GSZ_Id"])))
	{
		$error_message = urlencode("Указан некорректный URL для добавления компании в ГСЗ");
		redirect(HTML_PATH_GSZ_LIST_FORM.'?error='.$error_message);
	}
	$GSZ_item = new GSZ_item($get["GSZ_Id"]);
?>

<!DOCTYPE html>
<html>
<head>
	<title>Добавление компании в ГСЗ <?=$GSZ_item->Brief_Name?> | Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="/css/bootstrap.min.css" rel="stylesheet"/> 
	<link rel="stylesheet" href="/css/magnific-popup.css">
	<link href="/css/style.css" rel="stylesheet"/> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container">
		<header>
			<h2 class="text-center">Основные сведения о компании</h2>
		</header>
	
		<div class="jumbotron">
			<div id="error_message_div" class="alert alert-danger" role="alert">
				<span id="error_message"><?=$error_message?></span>
				<button id="btnError_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>
		
			<h3>Новая компания из ГСЗ: <?=$GSZ_item->Brief_Name?></h3>
			<form name="add_form" id="add_form" class="validated_company_form" action="<?=HTML_PATH_COMPANY_SAVE_ITEM?>?action=add" method="POST">
		
				<input type="hidden" name="GSZ_Id" id="GSZ_Id" value=<?=$GSZ_item->Id?>>
				<div class="form-group">
					<label for="Company_Name">Название</label>
					<input type="text" class="form-control" name="Company_Name" id="Company_Name" maxlength="<?=MAX_LENGTH_COMPANY_NAME?>" placeholder="Наименование компании">
				</div>
		
				<div class="form-group">
					<label for="INN">ИНН</label>
					<input type="number" class="form-control company_input" name="INN" id="INN" required min=1111111111 maxlength=12 minlength=10 placeholder="123456789012">
				</div>
			
				<div class="form-group">
					<label for="OPF">Организационно-правовая форма</label>
					<select class="form-control company_input"  name="OPF" id="OPF">
						<?php foreach (get_OPF_names() as $OPF_name => $INN_Length) {?>
						<option data-INN_Length="<?=$INN_Length?>"><?=$OPF_name?></option>
						<?php };?>
					</select>
				</div>
			
				<div class="form-group">
					<label for="SNO">Система налогооблажения</label>
					<select class="form-control company_input"  name="SNO" id="SNO">
						<?php foreach (get_SNO_names() as $SNO_name => $Cred_Limit_Affect) {?>
						<option data-Cred_Limit_Affect="<?=$Cred_Limit_Affect?>"><?=$SNO_name?></option>
						<?php };?>
					</select>
				</div>

				<div class="form-group">
					<label for="Date_Registr">Дата регистрации</label>
					<input type="date" required class="form-control company_input" name="Date_Registr" id="Date_Registr" >
				</div>

				<div class="form-group">
					<label for="Date_Registr">Дата начала деятельности</label>
					<input type="date" class="form-control company_input" name="Date_Begin_Work" id="Date_Begin_Work" >
				</div>


				<button type="submit" class="btn btn-primary" id="btnAddCompany">Сохранить</button> 
				<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
			</form>
		</div> 
	</div> 	
		
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script> 
	<script type="text/javascript" src="js/cred_limit.js"></script>
</body>
</html>