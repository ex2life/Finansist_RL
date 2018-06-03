<?php
	require_once('./script/cred_limit_scripts.php');
	session_start();
	if (!is_current_user())
	{
		$error_message = urlencode("Вы не авторизованы. Войдите в учетную запись или зарегистрируйтесь на сервисе");
		redirect('limit.html?error='.$error_message);
	}
	
	include_once ($_SERVER['DOCUMENT_ROOT'].'/registration/header_login.php');
	fill_calc_limit_dates();
    $GSZ_set = get_GSZ_set_with_calc_limit_date();
	$error_message = get_error_message();

?>
<!-- ==================================================================================================== -->
<!DOCTYPE html>
<html>
<head>
	<title>Финансовые данные ГСЗ | Финансист онлайн</title>
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
			<h2 class="text-center">ФИНАНСОВЫЕ ДАННЫЕ ГРУПП СВЯЗАННЫХ ЗАЕМЩИКОВ</h2>
		</header>

		<div class="jumbotron">
			<div id="error_message_div" class="alert alert-danger" role="alert">
				<span id="error_message"><?=$error_message?></span>
				<button id="btnError_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>

			<table class="table">
				<tr>
					<th>Название ГСЗ</th><th>Начало деятельности</th><th>Компаний <br>в группе</th><th>Дата расчета лимита</th>
				</tr>

				<?php 
				foreach ($GSZ_set as $GSZ) { ?>
				<tr>
					<td><?=$GSZ['Brief_Name']?></td><td><?=$GSZ['Date_begin_work']?></td><td style="text-align: center"><?=$GSZ['Count_company']?></td>
					<td><?=$GSZ['Date_calc_limit']?>&nbsp;&nbsp;  <a href="<?=HTML_PATH_DATE_CALC_LIMIT_EDIT_FORM?>?GSZ_Id=<?=$GSZ['GSZ_Id']?>">Изменить</a></td>
					<td><a class="btn btn-primary btn-xs" href="<?=HTML_PATH_FINANCE_COMPANY_LIST_FORM?>?GSZ_Id=<?=$GSZ['GSZ_Id']?>">Компании</a></td>
				</tr>
				<?php
				} 
				?>
			</table>
			<a class="btn btn-warning" href="limit.html">Вернуться</a>
		</div>
	</div>
		
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="js/cred_limit.js"></script>
</body>
</html>