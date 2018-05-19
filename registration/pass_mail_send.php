<?php
session_start();
require('lib/common.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'\registration\header_login.php');
?>
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
			<h1 class="text-center">ФИНАНСИСТ ОНЛАЙН</h1>
			
		</header>
		<div id="formlogin" class="jumbotron">
	   	<h2>Письмо с ссылкой для восстановления пароля отправлено!</h2>
	<div class="info">
		<H3><P>Для того, чтобы восстановить пароль, перейдите по ссылке в письме, которое вам сейчас придет от нас!<p>
		Если вдруг вы не видете письма на почте, подождите несколько минут, либо проверьте СПАМ ящик.</H3>
	</div>
</div>
	</div>
  </body>
</html>