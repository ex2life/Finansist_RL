<?php
header("HTTP/1.1 301 Moved Permanently");
header('Refresh: 10; url=login.php');
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
	   	<h2>Спасибо за подтверждение регистрации!</h2>
	<div class="info">
	<?php
echo 'Через 10 сек. вы будете перенаправлены на страницу авторизации.';
exit();
?>
	</div>
</div>
	</div>
  </body>
</html>