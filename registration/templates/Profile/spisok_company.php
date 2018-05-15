<!DOCTYPE html>

<html>
  <head>
	<title>Финансист онлайн</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<link href="../css/bootstrap.min.css" rel="stylesheet"/> 
	<link href="../css/style.css" rel="stylesheet"/> 
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../registration/css/main.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
	
  <body>
  
  	<div class="container">
		<div align="right" style="margin-right:5%" class="wrapper">
			<a href="#" class="btn btn-default">Мои компании</a>
			<div class="btn-group">
			  <a href="#" title="Настройки профиля" class="btn btn-default"><?= $current_user['fullname'] ?></a>
			  <a href="../registration/logout.php" title="Выход" class="btn btn-default"><img width="20" height="20" src="../img/Out.png"></a>
			</div>
		</div>
	    <header class="header">
			<h1>Ваши компании, <?= $current_user['fullname'] ?></h1>
			
		</header>
		
		<div id="formlogin" class="jumbotron">
	<?php if (empty ($company_list)): ?>
	<h3>Кажется вы не добавили еще ниодной компании. Добавить компании вы можете по кнопке ниже.</h3>
	<?php else: ?>
	<form action="users_company.php" method="POST">
	<table class="company" border="1">
		<tr>
			<th>Название компании</th>
			<th>ОПФ</th>
			<th>ИНН</th>
			<th>СНО</th>
			<th>Команды</th>
		</tr>
		<?php foreach ($company_list as $i => $company): ?>
		<tr class="<?= ($i+1)%2 == 0 ? 'even' : 'odd' ?>">
			<td><?= $company['name'] ?></td>
			<td title="<?= $company['opf_full_name'] ?>"><?= $company['opf_brief_name'] ?></td>
			<td><?= $company['inn'] ?></td>
			<td title="<?= $company['sno_full_name'] ?>"><?= $company['sno_brief_name'] ?></td>
		    <td>
                <button type="submit" name="Edit" value="<?= $company['company_id'] ?>" /> <image width="20px" height="20px" src="../img/edit.png"> </button>
                <button type="submit" name="Del" value="<?= $company['company_id'] ?>" onclick="if(confirm(\'Точно желаете удалить?\'))submit();else return false;" /> <image width="20px" height="20px" src="../img/del.png"> </button>
            </td>
		</tr>
		</form>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
    <form action="users_company.php" method="POST">
        <input type="submit" name="add_company" id="add_company" value="Добавить компанию"/>
    </form>
</div>
	</div>
  </body>
  
</html>