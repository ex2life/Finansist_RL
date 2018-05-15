<?php
session_start();
require('lib/common.php');


function is_postback()
{
	return isset($_POST['password']);
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
				$post_result = update_forget_pass($dbh, $_POST, $errors);
				db_close($dbh);
				if ($post_result=="hash_false")
				{					
				redirect('pass_link_fail.php');
				}
				if ($post_result) 
				{
					redirect('./');
					
				} 
				else 
				{
					// информация о пользователе заполнена неправильно, выведем страницу с ошибками
					render('edit_forgot_pass_form', array(
						'form' => $_POST, 'errors' => $errors
					));
				}
			
			
		} 
		else 
		{
			// отправляем пользователю чистую форму для восстановления пароля
			render('edit_forgot_pass_form', array(
						'form' => $_GET, 'errors' => $errors
					));
	
		}
		
		
	


}

main();

