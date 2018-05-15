<?php

require('lib/common.php');
require_once '../libs/google-api-php-client/vendor/autoload.php'; 

function is_postback($type)
{
	return isset($_POST[$type]);
}

function getUserFromToken($token) {
	$client = new Google_Client();
	$ticket = $client->verifyIdToken($token);
	if ($ticket) {
		return $ticket['sub']; // user ID
	}

	return false;
}
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
	else
	{
		$dbh = db_connect();
		$social=db_socid_user_array($dbh, get_current_user_id());
		db_close($dbh);
	}
	
	if ($_GET['log']=='vk')
	{
		if ($_GET['hash']==md5("6394999".$_GET['uid']."C4TLEl6y6TQuLZJj9KGG"))
		{
			$dbh = db_connect();
			$social_id = array(
				"social" => "vk",
				"id" => $_GET['uid'],
			);
			$post_result = db_user_socid_insert($dbh, $social_id, get_current_user_id());
			
			// считываем текущего пользователя
			$current_user = db_user_find_by_id($dbh, get_current_user_id());
			
			//выводим результирующую страницу
			render('profile/setting', array(
				'user' => array_merge($current_user,$social), 'errors' => $errors
			));
			// закрываем соединение с базой данных
			db_close($dbh);
		};
	}
	elseif ($_GET['log']=='telegram')
	{
		define('BOT_TOKEN', '540342856:AAF9Tg1zBhy2zwma7aoKZ4VcS7GSOC1wxLA'); // place bot token of your bot here
		function checkTelegramAuthorization($auth_data) 
		{
		  $check_hash = $auth_data['hash'];
		  unset($auth_data['hash']);
		  $data_check_arr = [];
		  foreach ($auth_data as $key => $value) {
			if ($value!='telegram') {
				$data_check_arr[] = $key . '=' . $value;
			}
		  }
		  sort($data_check_arr);
		  $data_check_string = implode("\n", $data_check_arr);
		  $secret_key = hash('sha256', BOT_TOKEN, true);
		  $hash = hash_hmac('sha256', $data_check_string, $secret_key);
		  if (strcmp($hash, $check_hash) !== 0) {
			throw new Exception('Data is NOT from Telegram');
		  }
		  if ((time() - $auth_data['auth_date']) > 86400) {
			throw new Exception('Data is outdated');
		  }
		  return true;
		}
		function saveTelegramUserData($auth_data) {
		  $auth_data_json = json_encode($auth_data);
		  setcookie('tg_user', $auth_data_json);
		}
		try {
		  $check = checkTelegramAuthorization($_GET);
		  if ($check){
			$dbh = db_connect();
			$social_id = array(
				"social" => "telegram",
				"id" => $_GET['id'],
			);
			$post_result = db_user_socid_insert($dbh, $social_id, get_current_user_id());
			
			// считываем текущего пользователя
			$current_user = db_user_find_by_id($dbh, get_current_user_id());
			
			//выводим результирующую страницу
			render('profile/setting', array(
				'user' => array_merge($current_user,$social), 'errors' => $errors
			));
			// закрываем соединение с базой данных
			db_close($dbh);
		  }
		} catch (Exception $e) {
		  die ($e->getMessage());
		}
	}
	elseif ($_POST['log']=='google')
	{	
		$idgoogleuser=getUserFromToken($_POST['idtoken']);
		if (!$idgoogleuser) {
			echo ("Что-то пошло не так.");
		}
		else
		{
			$dbh = db_connect();
			$social_id = array(
				"social" => "google",
				"id" => $idgoogleuser,
			);
			$post_result = db_user_socid_insert($dbh, $social_id, get_current_user_id());
			db_close($dbh);
			if ($post_result) 
			{
				//// перенаправляем на главную
				///redirect('./');
				echo "ok";
			} 
			else {
				// информация о пользователе заполнена неправильно, выведем страницу с ошибками
				echo "not_ok";
			}

		}
	}
	elseif (is_postback('updateinfo'))
	{
		// обрабатываем отправленную форму
		$dbh = db_connect();
		$post_result = update_infoprofile($dbh, $new_info, $errors);
		
		// считываем текущего пользователя
		$current_user = db_user_find_by_id($dbh, get_current_user_id());
			
		//выводим результирующую страницу
		render('profile/setting', array(
			'user' => array_merge($current_user,$social), 'errors' => $errors
		));
		// закрываем соединение с базой данных
		db_close($dbh);
		
	}
	elseif ($_GET['log']=='updatepassinfo')
	{
		// обрабатываем отправленную форму
		$dbh = db_connect();
		$post_result = update_pass($dbh, $pass3, $errors);
		db_close($dbh);

		if ($post_result) 
		{
			// Кидаем на страницу авторизации
			redirect('login.php');
		} 
		else 
		{
			// подключаемся к базе данных
			$dbh = db_connect();

			// считываем текущего пользователя
			$current_user = db_user_find_by_id($dbh, get_current_user_id());
			
			//выводим результирующую страницу
			render('profile/setting', array(
				'user' => array_merge($current_user,$social), 'errors' => $errors
			));
			// закрываем соединение с базой данных
			db_close($dbh);
		}
	}
	else 
	{
		// у нас есть пользователь, считываем список пользователей из БД, и отображаем его

		// подключаемся к базе данных
		$dbh = db_connect();

		// считываем текущего пользователя
		$current_user = db_user_find_by_id($dbh, get_current_user_id());
		
		//выводим результирующую страницу
		render('profile/setting', array(
			'user' => array_merge($current_user,$social), 'errors' => $errors
		));

		// закрываем соединение с базой данных
		db_close($dbh);
	}
			
}

main();
?>
