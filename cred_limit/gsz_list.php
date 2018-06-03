<?php

	session_start();
	require_once('./script/cred_limit_scripts.php');
	if (!is_current_user())
	{
		$error_message = urlencode("Вы не авторизованы. Войдите в учетную запись или зарегистрируйтесь на сервисе");
		redirect('limit.html?error='.$error_message);
	}
	include_once ($_SERVER['DOCUMENT_ROOT'].'/registration/header_login.php');

	
	$GSZ_set = get_GSZ_set();
	$error_message = get_error_message();
?>
<!-- =================================================================================== -->

<!DOCTYPE html>
<html>
<head>
	<title>Группы связанных заемщиков | Финансист онлайн</title>
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
			<h2 class="text | -center">ГРУППЫ СВЯЗАННЫХ ЗАЕМЩИКОВ</h2>
	</header>
		<div class="jumbotron">
			<div id="error_message_div" class="alert alert-danger" role="alert">
				<span id="error_message"><?=$error_message?></span>
				<button id="btnError_message" type="button" class="btn btn-info btn-xs">Закрыть</button>
			</div>
		
			<table class="table">
				<?php if (empty ($GSZ_set)): ?>
					<h3>Кажется вы не добавили еще ни одной группы. Добавить группы вы можете по кнопке ниже.</h3>
				<?php else: ?>
				<tr><th>Название</th><th>Описание</th><th></th><th></th><th></th></tr>
				<?php	foreach ($GSZ_set as $GSZ) {
						$id = $GSZ['Id']; ?>
				<tr>
					<td><?=$GSZ['Brief_Name']?></td>
					<td><?=$GSZ['Full_Name']?></td>
					<td><a class="btn btn-primary btn-xs" href="<?=HTML_PATH_COMPANY_LIST_FORM?>?GSZ_Id=<?=$id?>">Компании</a></td>
					<td><a class="btn btn-link btn-xs" href="<?=HTML_PATH_GSZ_EDIT_FORM?>?GSZ_Id=<?=$id?>">Изменить</a></td>
					<td><a class="btn btn-link btn-xs" href="<?=HTML_PATH_GSZ_DELETE_FORM?>?GSZ_Id=<?=$id?>">Удалить</a></td>
				</tr>
				<?php
					} //end of foreach
				endif;
				?>
			</table>
			<a class="btn btn-primary" href="<?=HTML_PATH_GSZ_ADD_FORM?>">Добавить</a>
			<a class="btn btn-warning" href="limit.html">Вернуться</a>
		</div>
	</div>
	<script type="text/javascript" src="/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="./js/cred_limit.js"></script>
</body>
</html>