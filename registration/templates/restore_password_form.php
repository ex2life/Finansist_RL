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
	<meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="846998585230-oct4e70i9en6bivrhaak2ikremk4ga8q.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
  </head>
	
  <body >
  	<div class="container">
	    <header class="header">
			<h1 class="text-center">Восстановление пароля.</h1>
			
		</header>
		<div id="formlogin" class="jumbotron">
	    <div class="form">
		<div class="row header">
			<h1>Email, указанный при регистрации.</h1>
		</div>
		<?php if (has_errors($errors)): ?>
		<div class="error-msg">
		При заполнении формы возникли ошибки, пожалуйста проверьте правильность заполнения полей и нажмите "Войти"!
		</div>
		<?php endif;?>
		<form action="restore_password.php" method="POST">
						<div class="row <?= is_error($errors, 'email') ? 'error' : '' ?>">
			<label for="email">Адрес электронной почты<span class="required">*</span>:</label>
			<input type="text" name="email" id="email"
					   value="<?= isset($form['email']) ? $form['email'] : '' ?>">
			</div>
			<div class="row footer">
				<input type="submit" name="login" id="login" value="Отправить письмо для смены пароля."/>
			</div>
			<div class="row footer">
				Еще не зарегистрированы? <a href="register.php">Зарегистрируйтесь!</a>
			</div>
		</form>
	</div>
</div>
	</div>
  </body>
</html>