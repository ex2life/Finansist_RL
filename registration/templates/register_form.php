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
	    	<div class="form">
		<div class="row header">
			<h1>Регистрация</h1>
		</div>
		<?php if (has_errors($errors)): ?>
		<div class="error-msg">
		При заполнении формы возникли ошибки, пожалуйста проверьте правильность заполнения полей и нажмите "Зарегистрироваться"!
		</div>
			<?php if (is_error($errors, 'nickname')): ?>
				<?php if ($errors['messages']['nickname']=='@nickname-nick-not-freedom'): ?>
					<div class="error-msg">
						Данный ник занят другим пользователем.
					</div>
				<?php endif;?>
			<?php endif;?>
			<?php if (is_error($errors, 'email')): ?>
				<?php if ($errors['messages']['email']=='@email-email-not-freedom'): ?>
					<div class="error-msg">
						Пользователь с таким адресом электронной почты уже есть на сайте.
					</div>
				<?php endif;?>
			<?php endif;?>
		<?php endif; ?>
		<form action="register.php" method="POST">
			<div class="row <?= is_error($errors, 'nickname') ? 'error' : '' ?>">
				<label for="nickname">Имя пользователя<span class="required">*</span>:</label>
				<input type="text" name="nickname" id="nickname"
					   value="<?= isset($form['nickname']) ? $form['nickname'] : '' ?>">
			</div>
			<?php if (isset($form['socid_soc'])): ?>
			<div>
				<input type="hidden" name="socid_soc" id="socid_soc"
					   value="<?= isset($form['socid_soc']) ? $form['socid_soc'] : '' ?>">
			</div>
			<?php endif; ?>
			<div class="row <?= is_error($errors, 'email') ? 'error' : '' ?>">
				<label for="email">Эл.почта<span class="required">*</span>:</label>
				<input type="text" name="email" id="email"
					   value="<?= isset($form['email']) ? $form['email'] : '' ?>">
			</div>
			<div class="row <?= is_error($errors, 'password') ? 'error' : '' ?>">
				<label for="password">Пароль<span class="required">*</span>:</label>
				<input type="password" name="password" id="password" value="">
			</div>
			<div class="row <?= is_error($errors, 'password_confirmation') ? 'error' : '' ?>">
				<label for="password_confirmation">Пароль еще раз<span class="required">*</span>:</label>
				<input type="password" name="password_confirmation" id="password_confirmation" value="">
			</div>
			<div class="row <?= is_error($errors, 'fullname') ? 'error' : '' ?>">
				<label for="fullname">ФИО<span class="required">*</span>:</label>
				<input type="text" name="fullname" id="fullname"
					   value="<?= isset($form['fullname']) ? $form['fullname'] : '' ?>">
			</div>
			<div class="row">
				<label></label>
				<input type="checkbox" name="newsletter" id="newsletter" value="1"
					<?= isset($form['newsletter']) && $form['newsletter'] == '1' ? 'checked="checked"' : '' ?>
					/>
				<label for="newsletter">Я хочу получать новостную рассылку</label>
			</div>
			<div class="row footer">
				<input type="submit" name="register" id="register" value="Зарегистрироваться"/>
				<input type="reset" name="reset" id="reset" value="Очистить"/>
			</div>
			<div class="row footer">
				Забыли пароль? <a href="restore_password.php">Востановите пароль здесь!</a>
			</div>
			<div class="row footer">
				Уже зарегистрированы? <a href="login.php">Войдите в систему!</a>
			</div>
		</form>
	</div>
</div>
	</div>
  </body>
</html>