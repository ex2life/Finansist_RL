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
	$update_status=good_email($dbh, $_GET);
	if ($update_status==true) {
		redirect('thankyou_commit_regist.php');
	} else {
		echo ('error');
	}
	
	db_close($dbh);
}

main();

