<?php
// подключаемся к базе данных
$dbh = db_connect();
// считываем текущего пользователя
if (is_current_user())
{
$current_user = db_user_find_by_id($dbh, get_current_user_id());
}
else
{
$current_user=false;
}
// закрываем соединение с базой данных
db_close($dbh);
?>
<html>
<div align="right" style="margin-right:5%" class="wrapper">
		<?php if ($current_user): //Если пользователь авторизован?>
			<a href="../" class="btn btn-default">Главная</a>
			<a href="../registration/users_company.php" class="btn btn-default">Мои компании</a>
			<div class="btn-group">
			  <a href="../registration/users_setting.php" title="Настройки профиля" class="btn btn-default"><?= $current_user['fullname'] ?></a>
			  <a href="../registration/logout.php" title="Выход" class="btn btn-default"><img width="20" height="20" src="../img/Out.png"></a>
			</div>
		<?php else: //Если пользователь неавторизован?>
		<form action="/registration/login.php?from=index"  method="POST" class="form-inline">
			<a href="../" class="btn btn-default">Главная</a>
			<div class="input-group mb-2 mr-sm-2 mb-sm-0">
				<div class="input-group-addon"><img width="20" height="20" src="../img/user.png"></div>
				<input type="text" name="username" id="username" class="form-control" placeholder="Имя пользователя">
			</div>
			<div class="input-group mb-2 mr-sm-2 mb-sm-0">
				<div class="input-group-addon"><img width="20" height="20" src="../img/key.png"></div>
				<input type="password" name="password" id="password" class="form-control" placeholder="Пароль">
			</div>
			<button type="submit" name="login" id="login" class="btn btn-default">Вход</button>
			<a href="../registration/register.php" class="btn btn-default">Регистрация</a>
		</form>
		<?php endif; ?>
</div>
</html>
