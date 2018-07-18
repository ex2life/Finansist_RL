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
	
  <body>
  	<div class="container">
	    <header class="header">
			<h1 class="text-center">Изменение пароля</h1>
			
		</header>
		<div id="formlogin" class="jumbotron">
	    <div class="form">
		<div class="row header">
			<h1>Введите новый пароль</h1>
		</div>
		<?php if (has_errors($errors)): ?>
		<div class="error-msg">
		При заполнении формы возникли ошибки, пожалуйста проверьте правильность заполнения полей и нажмите "Войти"!
		</div>
		<?php endif;?>
		<form action="verification_pass.php" method="POST">
			<div hidden class="row">
			<input type="text" name="mail" id="mail" value="<?= isset($form['mail']) ? $form['mail'] : '' ?>">
			<input type="text" name="hash" id="hash" value="<?= isset($form['hash']) ? $form['hash'] : '' ?>">
			</div>
			<div class="row <?= is_error($errors, 'password') ? 'error' : '' ?>">
				<label for="password">Пароль<span class="required">*</span>:</label>
				<input type="password" name="password" id="password" value="">
			</div>
			<div class="row <?= is_error($errors, 'password_confirmation') ? 'error' : '' ?>">
				<label for="password_confirmation">Пароль еще раз<span class="required">*</span>:</label>
				<input type="password" name="password_confirmation" id="password_confirmation" value="">
			</div>
			<div class="row footer">
				<input type="submit" name="save" id="save" value="Сохранить новый пароль"/>
			</div>

		</form>
	</div>
</div>
	</div>
  </body>
</html>