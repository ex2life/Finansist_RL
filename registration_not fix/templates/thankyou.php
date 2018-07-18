

<!DOCTYPE html>

<html>
  <head>
	<title>Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="../css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="../css/style.css" rel="stylesheet"/> 
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
	
  <body>
  	<div class="container">
	    <header class="header">
			<h1 class="text-center">АВТОРИЗАЦИЯ</h1>
			
		</header>
		<div id="formlogin" class="jumbotron">
		<?php $current_user = db_user_find_by_id($dbh, get_current_user_id());?>
	   	<h1>Спасибо за регистрацию, <?php echo $current_user['fullname'] ?>!</h1>
	<div class="info">
		<div class="row">
			<span class="title">Имя пользователя:</span><span class="value"><?= htmlspecialchars($user['username']) ?></span>
		</div>
		<div class="row">
			<span class="title">ФИО:</span><span class="value"><?= htmlspecialchars($user['fullname']) ?></span>
		</div>
		<div class="row">
			<span class="title">Пол:</span><span class="value"><?= isset($user['gender']) ? ($user['gender'] == 'M' ? 'Джентльмен' : 'Леди') : 'не указан' ?></span>
		</div>
		<div class="row">
			<span class="title">Подписка на новости:</span><span class="value"><?= $user['newsletter'] ? 'Да' : 'Нет' ?></span>
		</div>
	</div>
</div>
	</div>
  </body>
</html>