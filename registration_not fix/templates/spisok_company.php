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
			<h1>Зарегистрированные пользователи</h1>
			
		</header>
		
		<div id="formlogin" class="jumbotron">
	   	<div class="wrapper">
		<?php if ($current_user): ?>
			<a href="#" class="user"><?= $current_user['fullname'] ?></a>  <a href="logout.php" class="button">Выход</a>
		<?php endif; ?>
	</div>
	<table class="users" border="1">
		<tr>
			<th>ID</th>
			<th>Ник</th>
			<th>Эл.почта</th>
			<th>ФИО</th>
			<th>Пол</th>
			<th>Рассылка</th>
		</tr>
		<?php foreach ($user_list as $i => $user): ?>
		<tr class="<?= ($i+1)%2 == 0 ? 'even' : 'odd' ?>">
			<td><?= $user['id'] ?></td>
			<td><?= htmlspecialchars($user['nickname']) ?></td>
			<td><?= htmlspecialchars($user['email']) ?></td>
			<td><?= htmlspecialchars($user['fullname']) ?></td>
			<td><?= $user['gender'] == 'M' ? 'Джентльмен' : 'Леди' ?></td>
			<td><?= $user['newsletter'] ? 'Да' : 'Нет' ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
	</div>
  </body>
</html>