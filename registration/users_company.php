<?php
session_start();
require('lib/common.php');
include_once ($_SERVER['DOCUMENT_ROOT'].'\registration\header_login.php');

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
	if(isset($_POST['Edit'])){//если нажата 1-ая кнопка but1
		echo "Edit";
	}
	elseif (isset($_POST['Del'])){//если нажата кнопка удаления компании
		$dbh = db_connect();
		//Проверка принадлежности компании пользователю
		$have=db_company_have_user($dbh, $_POST['Del'], get_current_user_id());
		if ($have)
		{
			db_company_del($dbh, $_POST['Del']);
		}
		// считываем текущего пользователя
		$current_user = db_user_find_by_id($dbh, get_current_user_id());
		$current_user['status']=$have;
		//Считываем список компаний пользователя
		$company_list = db_company_find_all_for_current_user($dbh, get_current_user_id());
		// закрываем соединение с базой данных
		db_close($dbh);
		//выводим результирующую страницу
		render('profile/spisok_company', array(
			'company_list' => $company_list, 'current_user' => $current_user
		));
		
	}
	elseif (isset($_POST['add_company'])){//если нажата кнопка добавления компании
		$dbh = db_connect();
		
		// считываем текущего пользователя
		$current_user = db_user_find_by_id($dbh, get_current_user_id());
		$sno=db_sno($dbh);
		$opf=db_opf($dbh);
		// закрываем соединение с базой данных
		db_close($dbh);
		//выводим результирующую страницу
		render('profile/add_company', array(
			'current_user' => $current_user, 'errors' => $errors, 'sno' => $sno, 'opf' => $opf
		));
	}
	else
	{
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
}

main();

