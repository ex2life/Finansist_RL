<?php
	require_once('./script/cred_limit_scripts.php');
	if ((!isset($get["Company_Id"])) || (!ctype_digit($get["Company_Id"])) )
	{
		$error_message = urlencode("Был указан некорректный код компании для ввода данных по балансу");
		redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM.'?error='.$error_message);
	}
	if ((!isset($get["date"])) || (!is_Date($get["date"])) )
	{
		$error_message = urlencode("Была указана некорректная дата для ввода данных по балансу");
		redirect(HTML_PATH_FINANCE_GSZ_LIST_FORM.'?error='.$error_message);
	}
	
	$company = new Company_Item($get["Company_Id"]);
	
	$GSZ = new GSZ_Item($company->GSZ_Id);
	$Balance_Date = $get["date"];
	$error_message = get_error_message();
	$warning_message = get_warning_message();

	// Вычисляем балансы по активу и пассиву, сравниваем эти балансы друг с другом
	$total_Balance_Active = calculate_Balance($company->Id, $Balance_Date, "active");
	$total_Balance_Passive = calculate_Balance($company->Id, $Balance_Date, "passive");

	if ($total_Balance_Active != $total_Balance_Passive) {
		$warning_message = '<strong>'."Баланс не сходится! Актив: {$total_Balance_Active}, пассив: {$total_Balance_Passive}".'.</strong>';		
	}

	// Формируем массивы значений для дебет и кредита
	$Balance_Active = get_Corporation_Balance_Part($company->Id, $Balance_Date, "active", $company->Is_Corporation);
	$Balance_Passive = get_Corporation_Balance_Part($company->Id, $Balance_Date, "passive", $company->Is_Corporation);
?>
<!-- ==================================================================================================== -->
<!DOCTYPE html>
<html>
<head>
	<title>Баланс <?=$company->Name?> на <?=$Balance_Date?> | Финансист онлайн</title>
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
			<div id="warning_message_div" class="alert alert-danger" role="alert">
				<span id="warning_message"><?=$warning_message?></span>
				<button id="btnWarning_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>

            <h3><?=$company->Name?></h3>
            <h4>ИНН: <?=$company->INN?></h4>
            <h4>Начало деятельности: <?=$company->Date_Begin_Work?> &nbsp Дата баланса: <?=$Balance_Date?></h4>
			<hr>
			<form name="edit_balance" action="<?=HTML_PATH_BALANCE_SAVE_VALUES?>" method="POST">
				<input type="hidden" name="Company_Id" value=<?=$company->Id?>>
				<input type="hidden" name="Balance_Date" value=<?=$Balance_Date?>>
				<table>
					<tr><td style="text-align: center; color: blue;"><b>АКТИВ</b></td><td></td><td></td></tr>
					<tr><td>Наименование показателя</td><td>Код</td><td>Сумма</td></tr>
					<?php foreach($Balance_Active as $article): ?>
					<tr>
						<td><?= $article['Description'] ?></td>
						<?php if ($article['Is_Section']): ?>
							<td></td><td><input type="hidden" name="<?=$article['Code']?>" value="0" ></td>
						<?php elseif ($article['Is_Sum_Section'] || $article['Is_Sum_Part']): ?>
							<td><b><?= $article['Code'] ?></b></td>
							<td style="text-align: right"><?= $article['Value'] ?></td>
							<td><input type="hidden" name="<?=$article['Code']?>" value="<?= $article['Value'] ?>" ></td>
						<?php else: ?>
							<td><?= $article['Code'] ?></td>
							<?php if ($article['Is_Editable_Value']): ?>
								<td><input type="text" name="<?=$article['Code']?>" style="width: 100px; text-align: right" value="<?= $article['Value'] ?>" ></td>
							<?php else: ?>
								<td style="text-align: right"><?= $article['Value'] ?></td>
								<td><input type="hidden" name="<?=$article['Code']?>" value="<?= $article['Value'] ?>" ></td>
							<?php endif; ?>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
					<tr><td style="text-align: center; color: blue;"><b>ПАССИВ</b></td><td></td><td></td></tr>
					<tr><td>Наименование показателя</td><td>Код</td><td>Сумма</td></tr>
					<?php foreach($Balance_Passive as $article): ?>
					<tr>
						<td><?= $article['Description'] ?></td>
						<?php if ($article['Is_Section']): ?>
							<td></td><td><input type="hidden" name="<?=$article['Code']?>" value="0" ></td>
						<?php elseif ($article['Is_Sum_Section'] || $article['Is_Sum_Part']): ?>
							<td><b><?= $article['Code'] ?></b></td>
							<td style="text-align: right"><?= $article['Value'] ?></td>
							<td><input type="hidden" name="<?=$article['Code']?>" value="<?= $article['Value'] ?>" ></td>
						<?php else: ?>
							<td><?= $article['Code'] ?></td>
							<?php if ($article['Is_Editable_Value']): ?>
								<td><input type="text" name="<?=$article['Code']?>" style="width: 100px; text-align: right" value="<?= $article['Value'] ?>" ></td>
							<?php else: ?>
								<td style="text-align: right"><?= $article['Value'] ?></td>
								<td><input type="hidden" name="<?=$article['Code']?>" value="<?= $article['Value'] ?>" ></td>
							<?php endif; ?>
						<?php endif; ?>
					</tr>
					<?php endforeach; ?>
					
				</table>
				<button type="submit" class="btn btn-primary">Сохранить</button> 
				<a class="btn btn-warning" href="<?=HTML_PATH_BALANCE_DATES?>?Company_Id=<?=$company->Id?>">Вернуться</a>
			</form>
		</div>
	</div>
		
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="js/cred_limit.js"></script> 
</body>
</html>