<!DOCTYPE html>
<script type="text/javascript" src="//vk.com/js/api/openapi.js?152"></script>
<script type="text/javascript">
  VK.init({apiId: 6394999});
</script>
<script type="text/javascript">
  VK.Widgets.Auth("vk_auth", {"width":300,"authUrl":"http://finansist3261.com/registration/users_setting.php?log=vk&"});
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
		xhr.open('POST', './users_setting.php');
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			
			if (xhr.responseText=="ok") 
			{	
				signOut();
				console.log('1');
			document.location.replace("./users_setting.php");
			}
			else
			{	
				signOut();
				console.log('2');
				//document.location.replace("./register.php?reg=google&full_name="+profile.getName()+"&email="+profile.getEmail()+"&nickname="+profile.getEmail().split('@',1)+"&id="+profile.getId()+"google");  
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
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
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
	    <div align="right" style="margin-right:5%" class="wrapper">
			<a href="../" class="btn btn-default">Главная</a>
			<a href="#" class="btn btn-default">Мои компании</a>
			<div class="btn-group">
			  <a href="#" title="Настройки профиля" class="btn btn-default"><?= $user['fullname'] ?></a>
			  <a href="../registration/logout.php" title="Выход" class="btn btn-default"><img width="20" height="20" src="../img/Out.png"></a>
			</div>
		</div>
		<header class="header">
			<center><h1>Настройки профиля</h1></center>
		</header>
		<div id="formlogin" class="jumbotron">
	    <div class="form">
				    <ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#setting">Основные настройки</a></li>
				<li><a data-toggle="tab" href="#password">Изменение пароля</a></li>
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">Подключение социальных сетей
					<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li ><a data-toggle="tab" href="#vk">VK</a></li>
						<li id="telegram_button"  <?php if ($_COOKIE["Telegram_blocked"]=='yes'):?>hidden<?php endif; ?>><a data-toggle="tab" href="#telegram">Telegram</a></li>
						<li ><a data-toggle="tab" href="#google">Google</a></li> 
					</ul>
				</li>
			</ul>
		 <div class="tab-content">
    <div id="setting" class="tab-pane fade in active">
		<form action="users_setting.php" method="POST">
			<div align="center" >
			<?php if (has_errors($errors)): ?>
				<div class="error-msg">
					При заполнении формы возникли ошибки, пожалуйста проверьте правильность заполнения полей и нажмите "Войти"!
				</div>
				<?php if (is_error($errors, 'nickname')): ?>
				<?php if ($errors['messages']['nickname']=='@nickname-nick-not-freedom'): ?>
				<div class="error-msg">
				Данный ник занят другим пользователем.
				</div>
				<?php endif;?>
				<?php endif;?>
			<?php endif;?>
			<div class="row <?= is_error($errors, 'nickname') ? 'error' : '' ?>">
				<label for="nickname">Имя пользователя<span class="required">*</span>:</label>
				<input type="text" name="nickname" id="nickname"
					   value="<?= isset($user['nickname']) ? $user['nickname'] : '' ?>">
			</div>
			<div class="row <?= is_error($errors, 'email') ? 'error' : '' ?>">
				<label for="email">Эл.почта<span class="required">*</span>:</label>
				<input type="text" name="email" id="email" disabled
					   value="<?= isset($user['email']) ? $user['email'] : '' ?>">
			</div>
			<div class="row <?= is_error($errors, 'fullname') ? 'error' : '' ?>">
				<label for="fullname">ФИО<span class="required">*</span>:</label>
				<input type="text" name="fullname" id="fullname"
					   value="<?= isset($user['fullname']) ? $user['fullname'] : '' ?>">
			</div>
			<div class="row">
				<label></label>
				<input type="checkbox" name="newsletter" id="newsletter" value="1"
					<?= isset($user['newsletter']) && $user['newsletter'] == '1' ? 'checked="checked"' : '' ?>
					/>
				<label for="newsletter">Я хочу получать новостную рассылку</label>
			</div>
			<div class="row footer">
				<input type="submit" name="updateinfo" id="updateinfo" value="Сохранить изменения"/>
			</div>
			</div>
		</form>
    </div>
    <div id="password" class="tab-pane fade">
      <form action="users_setting.php?log=updatepassinfo" method="POST">
			<div align="center" >
			<div class="row <?= is_error($errors, 'old_password') ? 'error' : '' ?>">
				<label for="old_password">Cтарый пароль<span class="required">*</span>:</label>
				<input type="password" name="old_password" id="old_password" value="">
			</div>
			<div class="row <?= is_error($errors, 'password') ? 'error' : '' ?>">
				<label for="password">Новый пароль<span class="required">*</span>:</label>
				<input type="password" name="password" id="password" value="">
			</div>
			<div class="row <?= is_error($errors, 'password_confirmation') ? 'error' : '' ?>">
				<label for="password_confirmation">Новый пароль еще раз<span class="required">*</span>:</label>
				<input type="password" name="password_confirmation" id="password_confirmation" value="">
			</div>
			<div class="row footer">
				<input type="submit" name="updatepassinfo" id="updatepassinfo" value="Обновить пароль"/>
			</div>
			</div>
		</form>
    </div>
	<div id="vk" class="tab-pane fade">
		<?php if ($user['vk']==NULL):?>
		<div class="row footer">
			<!-- VK Widget -->
			<div id="vk_auth"></div>
		</div>
		<?php else: ?>
		<div class="row footer">
			Авторизация "Вконтакте" уже подключена.
		</div>
		<?php endif;?>
    </div>
    <div id="telegram" class="tab-pane fade">
		<?php if ($user['telegram']==NULL):?>
		<div  id="telegram_button_auth"  <?php if ($_COOKIE["Telegram_blocked"]=='yes'):?>hidden<?php endif; ?> class="row footer">
			<script async data-width="300" src="https://telegram.org/js/telegram-widget.js?4" data-telegram-login="finansist_authBot" data-size="large" data-auth-url="http://finansist3261.com/registration/users_setting.php?log=telegram&" data-request-access="write"></script>
		</div>
		<div id="telegram_no_proxy" <?php if ($_COOKIE["Telegram_blocked"]!='yes'):?>hidden<?php endif; ?> class="row footer">
			Сожалеем, но ваш интернет-провайдер блокирует доступ к Telegram. Добавить способ авторизации не возможно.
		</div>
		<?php else: ?>
		<div class="row footer">
			Авторизация "Telegram" уже подключена.
		</div>
		<?php endif;?>
    </div>
    <div id="google" class="tab-pane fade">
		<?php if ($user['google']==NULL):?>
		<div class="row footer">
			<div text-align="center" class="g-signin2" data-width="250"  data-onsuccess="onSignIn" data-theme="dark"></div>
		</div>
		<?php else: ?>
		<div class="row footer">
			Авторизация "Google" уже подключена.
		</div>
		<?php endif;?>
		
    </div>

  </div>
  </div>
	</div>
</div>
	</div>
  </body>
</html>