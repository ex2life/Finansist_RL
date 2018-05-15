<?php
session_start();
require('lib/common.php');

/*
 * Точка входа скрипта
 */
function main()
{


	// у нас есть пользователь, считываем список пользователей из БД, и отображаем его

	// подключаемся к базе данных
	$dbh = db_connect();
	$arr=db_socid_user_array($dbh, "65");
	var_export($arr);
	if ($arr['vk']==NULL){
		echo "true";
	}
	else
	{
		echo "false";
	}
	db_close($dbh);
}

main();

