<?php
	require_once('script/cred_limit_scripts.php');
	$error_message = "NO_ERRORS";

	if ((!isset($get["Company_Id"])) || (!ctype_digit($get["Company_Id"])))
	{
		$error_message = urlencode("Указан некорректный URL для формы редактирования компании из ГСЗ");
		redirect(HTML_PATH_GSZ_LIST_FORM.'?error='.$error_message);
	}
	
	$company = new Company_item($get["Company_Id"]);
	$error_message = get_error_message();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Изменение данных компании из ГСЗ <?=$company->GSZ_Name?> | Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="/css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="/css/style.css" rel="stylesheet"/> 
	<link rel="stylesheet" href="/css/alertify.css">
	<link rel="stylesheet" href="/css/themes/bootstrap.css">
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

			<h3>Компания из ГСЗ: <?=$company->GSZ_Name?></h3>

			<form name="edit_form" id="edit_form" class="validated_company_form"  action="<?=HTML_PATH_COMPANY_SAVE_ITEM?>?action=update" method="POST">
				<input type="hidden" name="Company_Id" Id="Company_Id" value="<?=$company->Id?>">
				<input type="hidden" name="GSZ_Id" Id="GSZ_Id" value="<?=$company->GSZ_Id?>">
				<div class="form-group">
					<label for="Company_Name">Название</label>
					<input type="text" class="form-control" name="Company_Name" id="Company_Name"  maxlength="<?=MAX_LENGTH_COMPANY_NAME?>" value="<?=$company->Name?>">
				</div>

				<div class="form-group">
					<label for="INN">ИНН</label>
					<input type="text" class="form-control company_input" name="INN" id="INN" maxlength=12 minlength=10 value="<?=$company->INN?>">
				</div>

				<div class="form-group">
				    <label for="OPF">Организационно-правовая форма</label>
				    <select class="form-control company_input" name="OPF" id="OPF">
						<?php
						foreach (get_OPF_names() as $OPF_name => $INN_Length) 
							if ($OPF_name==($company->OPF)): ?>
								<option data-INN_Length="<?=$INN_Length?>" selected><?=$OPF_name?></option>
							<?php else: ?>
								<option data-INN_Length="<?=$INN_Length?>"><?=$OPF_name?></option>
							<?php endif; ?>
					</select>
				</div>

				<div class="form-group">
			    	<label for="SNO">Система налогооблажения</label>
			    	<select class="form-control company_input"  name="SNO" id="SNO" >
					<?php
					foreach (get_SNO_names() as $SNO_name => $Cred_Limit_Affect) 
						if ($SNO_name==($company->SNO)): ?>
							<option data-Cred_Limit_Affect="<?=$Cred_Limit_Affect?>" selected><?=$SNO_name?></option>
						<?php else: ?>
							<option data-Cred_Limit_Affect="<?=$Cred_Limit_Affect?>"><?=$SNO_name?></option>
						<?php endif; ?>
					</select>
				</div>

				<div class="form-group">
					<label for="Date_Registr">Дата регистрации</label>
					<input type="date" required class="form-control company_input" name="Date_Registr" id="Date_Registr" value="<?=$company->Date_Registr?>" >
				</div>

				<div class="form-group">
					<label for="Date_Begin_Work">Дата начала деятельности</label>
					<input type="date" class="form-control company_input" name="Date_Begin_Work" id="Date_Begin_Work" value="<?=$company->Date_Begin_Work?>" >
				</div>

				<button type="submit" class="btn btn-primary">Сохранить</button> 
				<button type="button" class="btn btn-warning" onClick="history.back();">Отменить</button>
			</form>
		</div> 
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="/js/jquery.validate.min.js"></script> 
	<script type="text/javascript" src="/js/alertify.min.js"></script>
	<script type="text/javascript">
		//override defaults
		alertify.defaults.transition = "slide";
		alertify.defaults.theme.ok = "btn btn-primary";
		alertify.defaults.theme.cancel = "btn btn-danger";
		alertify.defaults.theme.input = "form-control";
		</script>
	<script type="text/javascript" src="js/cred_limit.js"></script>
</body>
</html>