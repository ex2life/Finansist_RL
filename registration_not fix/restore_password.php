<?php
require('lib/common.php');
session_start();

//Проверка что прислана форма
function is_postback()
{
	return isset($_POST['email']);
}
/*
 * Точка входа скрипта
 */
function main()
{

	if (is_current_user()) {
		// если пользователь уже залогинен, то отправляем его на глапную
		redirect('./');
	}
	if (is_postback()) //если форма прислана
		{
			
				// обрабатываем отправленную форму
				// подключаемся к базе данных
				$dbh = db_connect();
				//отправляем письмо с ссылкой для восстановления пароля
				$post_result = send_password_mail($dbh, $user, $errors);
				db_close($dbh);

				if (!$post_result) 
				{
					// информация о пользователе заполнена неправильно, выведем страницу с ошибками
					render('restore_password_form', array(
						'form' => $_POST, 'errors' => $errors
					));
				}
			
			
		} 
		else 
		{
			// отправляем пользователю чистую форму для восстановления пароля
			render('restore_password_form', array(
			'form' => array(), 'errors' => array()
			));
	
		}	
	
}

main();

