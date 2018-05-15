<?php

require('lib/common.php');

/*
 * Точка входа скрипта
 */
function main()
{
	// создаем сессию
	session_start();

	if (!is_current_user()) {
		// отправляем пользователя на страницу входа в систему
		redirect('login.php');
	}

	// у нас есть пользователь, считываем список пользователей из БД, и отображаем его

	// подключаемся к базе данных
	$dbh = db_connect();

	// считываем текущего пользователя
	$current_user = db_user_find_by_id($dbh, get_current_user_id());
	
	//Считываем список компаний пользователя
    $company_list = db_company_find_all_for_current_user($dbh, get_current_user_id());
	//выводим результирующую страницу
	render('profile/spisok_company', array(
		'company_list' => $company_list, 'current_user' => $current_user
	));

	// закрываем соединение с базой данных
	db_close($dbh);
}

main();

