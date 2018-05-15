<!DOCTYPE html>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?152"></script>
<script type="text/javascript">
  VK.init({apiId: 6394999});
</script>
<script type="text/javascript">
  VK.Widgets.Auth("vk_auth", {"width":300,"authUrl":"http://finansist3261.com/registration/login.php?log=vk&"});
</script>
<script type="text/javascript">
	function signOut() {
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut();
	}
	function onSignIn(googleUser) {
		  
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
		var xhr = new XMLHttpRequest();
		xhr.open('POST', './login.php');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			
			if (xhr.responseText=="auth_ok") 
			{	
				signOut();
				document.location.replace("./");
			}
			else
			{	
				signOut();
				document.location.replace("./register.php?reg=google&full_name="+profile.getName()+"&email="+profile.getEmail()+"&nickname="+profile.getEmail().split('@',1)+"&id="+profile.getId()+"google");  
			}
		};
		xhr.send('idtoken=' + id_token+'&log=google');
		
    }
	function setTelegramStatus(status) {
		console.log(status);
	}
	function checkTelegramStatus()
	{
		var img2 = document.createElement("img");
		img2.hidden = true;
		var img = document.body.appendChild(img2);
		img.onload = function()
		{
			setTelegramStatus("Telegram доступен");
			document.getElementById("telegram_button").hidden=false;
			document.getElementById("telegram_button_auth").hidden=false;
			document.getElementById("telegram_no_proxy").hidden=true;
			document.cookie = "Telegram_blocked=no";
			return true;
		};
		img.onerror = function()
		{
			setTelegramStatus("Telegram не доступен");
			document.getElementById("telegram_button").hidden=true;
			document.getElementById("telegram_button_auth").hidden=true;
			document.getElementById("telegram_no_proxy").hidden=false;
			document.cookie = "Telegram_blocked=yes";
		};
		img.src = "https://telegram.org/img/t_logo.png";
	}
</script>
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
	
  <body  onload="checkTelegramStatus()">
  	<div class="container">
	    <header class="header">
			<h1 class="text-center">АВТОРИЗАЦИЯ</h1>
			
		</header>
		<div id="formlogin" class="jumbotron">
	    <div class="form">
		<div class="row header">
			<h1>Вход в систему</h1>
		</div>
		<?php if (has_errors($errors)): ?>
		<div class="error-msg">
		При заполнении формы возникли ошибки, пожалуйста проверьте правильность заполнения полей и нажмите "Войти"!
		</div>
		<?php endif;?>
		<form action="login.php" method="POST">
			<?php if ($_GET['type']=='vk'): ?>
			<div class="row footer">
			<!-- VK Widget -->
			<div id="vk_auth"></div>
			</div>
			<?php elseif ($_GET['type']=='telegram'): ?>
			<div id="telegram_button_auth" <?php if ($_COOKIE["Telegram_blocked"]=='yes'):?>hidden<?php endif; ?> class="row footer">
				<script async data-width="300" src="https://telegram.org/js/telegram-widget.js?4" data-telegram-login="finansist_authBot" data-size="large" data-auth-url="http://finansist3261.com/registration/login.php?log=telegram&" data-request-access="write"></script>
			</div>
			<div id="telegram_no_proxy" <?php if ($_COOKIE["Telegram_blocked"]!='yes'):?>hidden<?php endif; ?> class="row footer">
				Сожалеем, но ваш интернет-провайдер блокирует доступ к Telegram. Воспользуйтесь другими способами авторизации.
			</div>
			<?php elseif ($_GET['type']=='google'): ?>
			<div class="row footer">
			<div text-align="center" class="g-signin2" data-width="250"  data-onsuccess="onSignIn" data-theme="dark"></div>
			</div>
			<?php else: ?>
			<div class="row <?= is_error($errors, 'username') ? 'error' : '' ?>">
				<label for="username">Имя пользователя<span class="required">*</span>:</label>
				<input type="text" name="username" id="username"
					   value="<?= isset($form['username']) ? $form['username'] : '' ?>">
			</div>
			<div class="row <?= is_error($errors, 'password') ? 'error' : '' ?>">
				<label for="password">Пароль<span class="required">*</span>:</label>
				<input type="password" name="password" id="password" value="">
			</div>
			<div class="row footer">
				<input type="submit" name="login" id="login" value="Войти"/>
				<input type="reset" name="reset" id="reset" value="Очистить"/>
			</div>
			<?php endif; ?>
			<div class="row footer">
				<b text-align="center" >Вход через социальные сети:<p></b>
				<a href="login.php?type=vk"><img src="../img/VK_Logo.png" width="35" 
					height="35" alt="Vkontakte"></a>
				<a id="telegram_button" href="login.php?type=telegram" <?php if ($_COOKIE["Telegram_blocked"]=='yes'):?>hidden<?php endif; ?>><img src="../img/Telegram_Logo.png" width="35" 
					height="35" alt="Telegram"></a>
				<a href="login.php?type=google"><img src="../img/Google_Logo.png" width="35" 
					height="35" alt="Google"></a>
				<a href="login.php"><img src="../img/Email_Logo.png" width="35" 
					height="35" alt="Email"></a>
			</div>
			<div class="row footer">
				Забыли пароль? <a href="restore_password.php">Востановите пароль здесь!</a>
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