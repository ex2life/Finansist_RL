<?php
require('lib/common.php');
/*
 * Точка входа скрипта
 */
function main()
{
	// создаем сессию
	session_start();
	 
	// у нас есть пользователь, считываем список пользователей из БД, и отображаем его
	
	// подключаемся к базе данных
	$dbh = db_connect();
	//Отправляем на проверку данные из GET и если все ок, то там они и удалятся
	$update_status=good_email($dbh, $_GET);
	if ($update_status==true) {//Если удалили успешно
		redirect('thankyou_commit_regist.php');
	} else {
		echo ('error');
	}
	//Закрываем подключение
	db_close($dbh);
}

main();

