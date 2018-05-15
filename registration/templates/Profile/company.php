<?php

require('lib/common.php');

/*
 * Проверяет, что была выполнена отправка формы входа
 */
function is_postback()
{
	return isset($_POST['login']);
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

	if (is_postback()) {
		// обрабатываем отправленную форму
		$dbh = db_connect();
		$post_result = login_user($dbh, $user, $errors);
		db_close($dbh);

		if ($post_result) {
			// перенаправляем на главную
			redirect('./');
		} else {
			// информация о пользователе заполнена неправильно, выведем страницу с ошибками
			render('login_form', array(
				'form' => $_POST, 'errors' => $errors
			));
		}
	} else {
		// отправляем пользователю чистую форму для входа
		render('login_form', array(
			'form' => array(), 'errors' => array()
		));
	}
}

main();

