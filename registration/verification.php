<?php
session_start();
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
		echo ('vse ne ok');
	}

	
	// считываем список пользователей и текущего пользователя
	$user_list = db_user_find_all($dbh);
	$current_user = db_user_find_by_id($dbh, get_current_user_id());

    // закрываем соединение с базой данных
	
	db_close($dbh);
}

main();

