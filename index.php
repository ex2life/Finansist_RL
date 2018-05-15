<?php
session_start();
require('./registration/lib/common.php');
$dbh = db_connect();
$current_user = db_user_find_by_id($dbh, get_current_user_id());
?>

<html>
  <head>

<div align="right" style="margin-right:5%" class="wrapper">
		<?php if ($current_user): ?>
			<a href=".\registration\users_company.php" class="btn btn-default">Мои компании</a>
			<div class="btn-group">
			  <a href="./registration/users_setting.php" title="Настройки профиля" class="btn btn-default"><?= $current_user['fullname'] ?></a>
			  <a href="./registration/logout.php" title="Выход" class="btn btn-default"><img width="20" height="20" src="../img/Out.png"></a>
			</div>

				

		<?php else: ?>
		<form action="./registration/login.php?from=index"  method="POST" class="form-inline">
			<div class="input-group mb-2 mr-sm-2 mb-sm-0">
				<div class="input-group-addon"><img width="20" height="20" src="../img/user.png"></div>
				<input type="text" name="username" id="username" class="form-control" placeholder="Имя пользователя">
			</div>
			<div class="input-group mb-2 mr-sm-2 mb-sm-0">
				<div class="input-group-addon"><img width="20" height="20" src="../img/key.png"></div>
				<input type="password" name="password" id="password" class="form-control" placeholder="Пароль">
			</div>
			<button type="submit" name="login" id="login" class="btn btn-default">Вход</button>
			<a href="./registration/register.php" class="btn btn-default">Регистрация</a>
		</form>
		<?php endif; ?>
	</div>
	<title>Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="css/style.css" rel="stylesheet"/> 
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
	
  <body>
  	<div class="container">

	    
	    <header>
			<h1 class="text-center">ФИНАНСИСТ ОНЛАЙН</h1>
		</header>
		<div class="row">
			<a class="btn_margin btn btn-default btn-lg col-xs-10 col-sm-6 col-md-4 col-lg-3 col-xs-offset-1 col-sm-offset-5 col-md-offset-7 col-lg-offset-9" href="./cred_calc/calc.php">Кредитный калькулятор </a>
			<a class="btn_margin btn btn-default btn-lg col-xs-10 col-sm-6 col-md-4 col-lg-3 col-xs-offset-1 col-sm-offset-5 col-md-offset-7 col-lg-offset-9" href="#">Расчет суммы кредита</a>
			<a class="btn_margin btn btn-default btn-lg col-xs-10 col-sm-6 col-md-4 col-lg-3 col-xs-offset-1 col-sm-offset-5 col-md-offset-7 col-lg-offset-9" href="#">Финансовый анализ</a>
			<a class="btn_margin btn btn-default btn-lg col-xs-10 col-sm-6 col-md-4 col-lg-3 col-xs-offset-1 col-sm-offset-5 col-md-offset-7 col-lg-offset-9" href="#">Управленческая отчетность</a>
			<a class="btn_margin btn btn-default btn-lg col-xs-10 col-sm-6 col-md-4 col-lg-3 col-xs-offset-1 col-sm-offset-5 col-md-offset-7 col-lg-offset-9" href="#">Анализ инвестиционных проектов</a>
		</div>
	</div>
  </body>

</html>