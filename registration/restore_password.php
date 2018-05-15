<?php

require('lib/common.php');


function is_postback()
{
	return isset($_POST['email']);
}
/*
 * Точка входа скрипта
 */
function main()
{
	// создаем сессию
	session_start();

	if (is_current_user()) {
		// если пользователь уже залогинен, то отправляем его на глапную
		redirect('./');
	}
	if (is_postback()) 
		{
			
				// обрабатываем отправленную форму
				$dbh = db_connect();
				$post_result = send_password_mail($dbh, $user, $errors);
				db_close($dbh);

				if ($post_result) 
				{
					// перенаправляем на главную
					
				} 
				else 
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

