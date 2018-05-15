<?php

require('lib/common.php');

/*
 * Проверяет, что была выполнена отправка формы регистрации
 */
function is_postback()
{
	return isset($_POST['register']);
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
	
	if ($_GET['reg']=='google')
	{	

		$data_get['socid_soc'] = $_GET['id'];
		if (isset($_GET['nickname']))
			{
				$data_get['nickname'] = $_GET['nickname'];
			}
		if (isset($_GET['full_name']))
			{
				$data_get['fullname'] = $_GET['full_name'];
			}
		if (isset($_GET['email']))
			{
				$data_get['email'] = $_GET['email'];
			}
		render('register_form', array(
				'form' => $data_get, 'errors' => $errors
			));
	}
	if (is_postback()) {
		// обрабатываем отправленную форму
		$dbh = db_connect();
		$post_result = register_user($dbh, $user, $errors);
		db_close($dbh);

		if ($post_result) {
			// перенаправляем на главную
			redirect('./');
		} else {
			// информация о пользователе заполнена неправильно, выведем страницу с ошибками
			render('register_form', array(
				'form' => $_POST, 'errors' => $errors
			));
		}
	} else {
		// отправляем пользователю чистую форму для регистрации
		render('register_form', array(
			'form' => array(), 'errors' => array()
		));
	}
}

main();

