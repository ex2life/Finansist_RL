<?php
require($_SERVER['DOCUMENT_ROOT'].'/script/connection_params.php');
require('shablon_email.php');
/* ****************************************************************************
 * Общие функции
 */

/*
 * Выполняет переадресацию на указанный адрес $url
 */
function redirect($url)
{
	session_write_close();
	header('Location: '.$url);
	exit;
}

/*
 * Выполняет вывод указанного шаблона $template с данными
 */
function render($template, $data=array())
{
	extract($data);
	require('templates/'.$template.'.php');
}


/* ****************************************************************************
 * Функции работы с массивом ошибок
 */

/*
 * Инициализирует структуру массива для хранения информации об ошибках
 */
function empty_errors()
{
	return array(
		'fields' 	=> array(),
		'messages'	=> array(),
	);
}

/*
 * Проверяет, что есть ошибочные поля в описании ошибок
 */
function has_errors($errors)
{
	return isset($errors['fields']) && count($errors['fields']) > 0;
}

/*
 * Проверяет, что указанное поле есть в списке ошибочных полей
 */
function is_error($errors, $field)
{
	return isset($errors['fields']) && in_array($field, $errors['fields']);
}

/*
 * Добавляет описание ошибки в массив ошибок
 */
function add_error(&$errors, $field, $description)
{
	$errors['fields'][] = $field;
	$errors['messages'][$field] = "@$field-$description";
	return false;
}


/* ****************************************************************************
 * Валидация данных
 */

/*
 * Проверяет корректность строки в форме, если строка корректна, копирует ее в $obj
 * и возвращает true; false и заполненный массив ошибок, если нет
 */
function read_string($form, $field, &$obj, &$errors, $min, $max, $is_required, $default=null, $trim=true)
{
	$obj[$field] = $default;
	if (!isset($form[$field])) {
		return $is_required ? add_error($errors, $field, 'required') : true;
	}

	$value = $trim ? trim($form[$field]) : $form[$field];
	if ($value == '' && $is_required)
		return add_error($errors, $field, 'required');

	if (strlen($value) < $min)
		return add_error($errors, $field, 'too-short');

	if (strlen($value) > $max)
		return add_error($errors, $field, 'too-long');

	$obj[$field] = $value;
	return true;
}

/*
 * Проверяет корректность адреса электронной почты в форме, если адрес корректен,
 * копирует его в $obj и возвращает true; false и заполненный массив ошибок, если нет
 */
function read_email($form, $field, &$obj, &$errors, $min, $max, $is_required, $default=null)
{
	$obj[$field] = $default;
	if (!isset($form[$field])) {
		return $is_required ? add_error($errors, $field, 'required') : true;
	}

	$value = trim($form[$field]);
	if (strlen($value) < $min)
		return add_error($errors, $field, 'too-short');

	if (strlen($value) > $max)
		return add_error($errors, $field, 'too-long');

	// проверяем, что в строке задан адрес электронной почты
	if (!filter_var($value, FILTER_VALIDATE_EMAIL))
		return add_error($errors, $field, 'invalid');

	$obj[$field] = $value;
	return true;
}

/*
 * Проверяет корректность выбора одного из значений в форме, если выбрано значение
 * из указанного списка, копирует его в $obj и возвращает true; false и заполненный
 * массив ошибок, если нет
 */
function read_list($form, $field, &$obj, &$errors, $list, $is_required, $default=null)
{
	$obj[$field] = $default;
	if (!isset($form[$field])) {
		return $is_required ? add_error($errors, $field, 'required') : true;
	}

	$value = trim($form[$field]);
	if (!in_array($value, $list))
		return add_error($errors, $field, 'invalid');

	$obj[$field] = $value;
	return true;
}

/*
 * Проверяет корректность логического значения, если корректно, копирует его
 * в $obj и возвращает true; false и заполненный массив ошибок, если нет
 */
function read_bool($form, $field, &$obj, &$errors, $true, $is_required, $default=null)
{
	$obj[$field] = $default;
	if (!isset($form[$field])) {
		return $is_required ? add_error($errors, $field, 'required') : true;
	}

	$value = trim($form[$field]);
	$obj[$field] = $value === $true;
	return true;
}


/* ****************************************************************************
 * Текущий пользователь, для вошедшего в систему пользователя мы храним
 * в сессии его идентификатор в базе данных
 */

/*
 * Проверяет, что у нас имеется вошедший в систему пользователь
 */
function is_current_user()
{
	return isset($_SESSION['user_id']);
}

/*
 * Возвращает идентификатор пользователя, выполнившего вход в систему
 */
function get_current_user_id()
{
	return $_SESSION['user_id'];
}

/*
 * Сохраняет идентификатор пользователя, выполнившего вход
 */
function store_current_user_id($id)
{
	$_SESSION['user_id'] = $id;
}

/*
 * Сбрасывает идентификатор пользователя, выполнившего вход
 */
function reset_current_user_id()
{
	unset($_SESSION['user_id']);
}

/*
 * Выполняет вход пользователя в систему, возвращает true, если вход
 * выполнен успешно, и false и заполненный массив ошибок в противном
 * случае
 */
function login_user($dbh, &$user, &$errors)
{
	$user = array();
	$errors = empty_errors();

	// считываем строки из запроса
	read_string($_POST, 'username', $user, $errors, 2, 64, true);
	read_string($_POST, 'password', $user, $errors, 6, 20, true);

	if (has_errors($errors))
		return false;

	// форма передана правильно, ищем пользователя и проверяем пароль
	$db_user = db_user_find_by_login($dbh, $user['username']);
	// смотрим, есть ли такой пользователь и правильно ли передан пароль
	if ($db_user == null || !password_verify($user['password'], $db_user['password']))
		return add_error($errors, 'password', 'invalid');
	if ($db_user['status_active']=='0')
	{
		return add_error($errors, 'status_active', 'no_active');
	}
	// пользователь ввел правильные имя и пароль, запоминаем его в сессии
	store_current_user_id($db_user['id']);
	// проверяем, нужно ли обновить кеш пароля
	if (password_needs_rehash($db_user['password'], PASSWORD_DEFAULT)) 
	{
    // Кеш пароля нужно обновить, в связи с изменением алгоритма
    $db_user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
	// обновим кеш в базе данных
    $db_user = db_user_update($dbh, $db_user);
    // don't forget to store the new hash!
    }
	return true;
}


/*
 * Выполняет вход пользователя в систему через социальные сети, возвращает true, если вход
 * выполнен успешно, и false и заполненный массив ошибок в противном
 * случае
 */
function login_social_user($dbh, $soc, $socid, $errors)
{
	$errors = empty_errors();

	// форма передана правильно, ищем пользователя
	$id_otv = db_id_find_by_socid($dbh, $soc, $socid);
	$db_user=db_user_find_by_id($dbh, $id_otv);
	if ($db_user['status_active']=='0')
	{
		return add_error($errors, 'status_active', 'no_active');
	}
	store_current_user_id($id_otv);
	return $id_otv;
}

/*
 * Выполняет выход пользователя из системы
 */
function logout_user()
{
	reset_current_user_id();
}

/*
 * Выполняет регистрацию пользователя, возвращает true, если регистраиция
 * завершилась успешно, и false и заполненный массив ошибок в противном
 * случае
 */
function register_user($dbh, &$user, &$errors)
{
	$user = array();
	$errors = empty_errors();

	// считываем строки из запроса
	read_string($_POST, 'nickname', $user, $errors, 2, 64, true);
	read_email($_POST, 'email', $user, $errors, 2, 64, true);
	read_string($_POST, 'password', $user, $errors, 6, 24, true);
	read_string($_POST, 'password_confirmation', $user, $errors, 6, 24, true);
	read_string($_POST, 'fullname', $user, $errors, 1, 80, true);
	read_string($_POST, 'socid_soc', $user, $errors, 3, 225, false);
	read_bool($_POST, 'newsletter', $user, $errors, '1', false, false);

	// пароль и подтверждение пароля должны совпадать
	if (!is_error($errors, 'password') &&
			!is_error($errors, 'password_confirmation') &&
			$user['password'] != $user['password_confirmation']) {
		$errors['fields'][] = 'password';
		add_error($errors, 'password_confirmation', 'dont-match');
	}
	
	//проверка уникальности никнейма
	if (!db_freedom_nick_find($dbh, $user['nickname']))
	{
		add_error($errors, 'nickname', 'nick-not-freedom');
	}
	//проверка уникальности адреса электронной почты
	if (!db_freedom_email_find($dbh, $user['email']))
	{
		add_error($errors, 'email', 'email-not-freedom');
	}
	
	if (has_errors($errors))
		return false;

	// защищаем пароль пользователя
	$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
	unset($user['password_confirmation']);
	
	// форма передана правильно, сохраняем пользователя в базу данных
	$db_user = db_user_insert($dbh, $user);
	if (isset($user['socid_soc']))//если пользователь пытался изначально зайти через социальную сеть
	{
		db_user_socid_insert($dbh, db_user_social_reg_ok_insert($dbh, $user['socid_soc']), $db_user['id']);
		
	}
	else
	{	
		db_user_socid_insert_null($dbh, $db_user['id']);
	}
	send_confirm_message($db_user);
	return true;
}


/*
 * Выполняет подготовку данных для отправки письма пользователю 
 * при востановлении пароля возвращает true, если регистраиция
 * завершилась успешно, и false и заполненный массив ошибок в противном
 * случае
 */
function send_password_mail($dbh, &$user, &$errors)
{
	$user = array();
	$errors = empty_errors();

	// считываем строки из запроса
	read_email($_POST, 'email', $user, $errors, 2, 64, true);
	
	if (has_errors($errors))
		return false;
	
	// форма передана правильно, ищем пользователя
	$db_user = db_user_find_by_login($dbh, $user['email']);
	
	if (!db_freedom_email_find($dbh, $user['email']))
	{
		send_password_message($db_user);
	}
	// форма передана правильно, сохраняем пользователя в базу данных
	

	// автоматически логиним пользователя после регистрации, запоминая его в сессии
	store_current_user_id($db_user['id']);
	return true;
}


/*
 * Выполняет чтение и проверку введенных 3 паролей
 */
function update_pass($dbh, &$pass3, &$errors)
{
	$pass3 = array();
	$errors = empty_errors();

	// считываем строки из запроса
	read_string($_POST, 'old_password', $pass3, $errors, 6, 24, true);
	read_string($_POST, 'password', $pass3, $errors, 6, 24, true);
	read_string($_POST, 'password_confirmation', $pass3, $errors, 6, 24, true);

	
	// форма передана правильно, ищем пользователя и проверяем пароль
	$db_user = db_user_find_by_id($dbh, get_current_user_id());
	// смотрим, есть ли такой пользователь и правильно ли передан пароль
	if ($db_user == null || !password_verify($pass3['old_password'], $db_user['password']))
		return add_error($errors, 'old_password', 'invalid');
	// пользователь ввел правильные имя и пароль	
	unset($pass3['old_password']);
	// пароль и подтверждение пароля должны совпадать
	if (!is_error($errors, 'password') &&
			!is_error($errors, 'password_confirmation') &&
			$pass3['password'] != $pass3['password_confirmation']) {
		$errors['fields'][] = 'password';
		add_error($errors, 'password_confirmation', 'dont-match');
	}

	if (has_errors($errors))
		return false;

	// защищаем пароль пользователя
	$db_user['password'] = password_hash($pass3['password'], PASSWORD_DEFAULT);
	unset($pass3['password']);
	unset($pass3['password_confirmation']);

	// форма передана правильно, обновляем пароль пользователя в базе данных
	$db_user = db_user_update($dbh, $db_user);
	// автоматически выходим из профиля
	logout_user();
	return true;
}

/*
 * Выполняет проверку и обновление новых данных пользователя
 */
function update_infoprofile($dbh, &$new_info, &$errors)
{
	$new_info = array();
	$errors = empty_errors();

	// считываем строки из запроса
	read_string($_POST, 'nickname', $new_info, $errors, 2, 64, true);
	//read_email($_POST, 'email', $new_info, $errors, 2, 64, true);
	read_string($_POST, 'fullname', $new_info, $errors, 1, 80, true);
	read_bool($_POST, 'newsletter', $new_info, $errors, '1', false, false);
	
	// форма передана правильно, ищем пользователя
	$db_user = db_user_find_by_id($dbh, get_current_user_id());
	if (($db_user['nickname']!=$new_info['nickname']) and !db_freedom_nick_find($dbh, $new_info['nickname']))
	{
		add_error($errors, 'nickname', 'nick-not-freedom');
	}
	if (has_errors($errors))
		return false;
	$db_user['nickname']=$new_info['nickname'];
	//$db_user['email']=$new_info['email'];
	$db_user['fullname']=$new_info['fullname'];
	$db_user['newsletter']=$new_info['newsletter'];

	unset($new_info);

	// форма передана правильно, обновляем пароль пользователя в базе данных
	$db_user = db_user_update($dbh, $db_user);
	return true;
}


/* ****************************************************************************
 * Список пользователей в базе данных
 */

/*
 * Выполняет подключение к базе данных
 */
function db_connect()
{
	$dbh = mysqli_connect(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

	// проверка соединения
	if (mysqli_connect_errno())
		db_handle_error($dbh);

	mysqli_set_charset($dbh, "utf8");

	return $dbh;
}

/*
 * Закрывает подключение к базе данных
 */
function db_close($dbh)
{
	mysqli_close($dbh);
}

/*
 * Отправка письма для подтверждения почты
 */
function send_confirm_message($user)
{


$subject="Подтверждение регистрации на сайте Финансист";
$headers = "To: name <".$to.">\r\n";
$headers = "From: Финансист\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf-8";
$user['link']=$_SERVER['HTTP_HOST']."/registration/verification.php?mail=".$user['email']."&hash=".md5(SALT.$user['id']);
$message = gener_email_html($user);


// Отправляем
mail($user['email'], $subject, $message,$headers);
redirect('thankyou_regist.php');
}

/*
 * Отправка письма для изменения забытого пароля
 */
function send_password_message($user)
{


$subject="Восстановление забытого пароля на сайте Финансист";
$headers = "To: name <".$to.">\r\n";
$headers = "From: Финансист\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf-8";
$microtime=microtime();
$dbh = db_connect();
forget_password_add($dbh, $user['email'],$microtime);
db_close($dbh);
$user['link']=$_SERVER['HTTP_HOST']."/registration/verification_pass.php?mail=".$user['email']."&hash=".md5(SALT.$microtime.SALT.$user['id']);
$message = gener_email_html_pass($user);

// Отправляем
mail($user['email'], $subject, $message,$headers);
redirect('pass_mail_send.php');
}

/*
 * Процесс подтверждения почты
 */
function good_email($dbh, $array_get)
{
	$id_email=db_user_id_find_by_email($dbh, $array_get['mail']);
	if ($array_get['hash']==md5(SALT.$id_email)) {
		$status=db_user_status_update($dbh, $id_email);
		return true;
	} else {
		return false;
	}
}

/*
 * Процесс обновления пароля
 */
function update_forget_pass($dbh, $pass2, $errors)
{
	$errors = empty_errors();
	// считываем строки из запроса
	read_string($_POST, 'password', $pass2, $errors, 6, 24, true);
	read_string($_POST, 'password_confirmation', $pass2, $errors, 6, 24, true);

	if (has_errors($errors))
		return false;
	
	// форма передана правильно, проверяем hash
	
	$microtime=forget_password_find_time($dbh,$pass2['mail']);
	$id_mail=db_user_id_find_by_email($dbh, $pass2['mail']);
	if (md5(SALT.$microtime.SALT.$id_mail)!=$pass2['hash'])
	{
		forget_password_del($dbh,$pass2['mail']);
		return false;
	}

	// пароль и подтверждение пароля должны совпадать
	if (!is_error($errors, 'password') &&
			!is_error($errors, 'password_confirmation') &&
			$pass2['password'] != $pass2['password_confirmation']) {
		$errors['fields'][] = 'password';
		add_error($errors, 'password_confirmation', 'dont-match');
	}

	if (has_errors($errors))
		return false;
	$db_user = db_user_find_by_login($dbh, $pass2['mail']);
	// защищаем пароль пользователя
	$db_user['password'] = password_hash($pass2['password'], PASSWORD_DEFAULT);
	unset($pass2['password']);
	unset($pass2['password_confirmation']);

	// форма передана правильно, обновляем пароль пользователя в базе данных
	$db_user = db_user_update($dbh, $db_user);
	forget_password_del($dbh,$pass2['mail']);
	return true;
}

/*
 * Обработка ошибок подключения к базе данных
 */
function db_handle_error($dbh)
{
	$code = '@unknown-error';
	$message = '';
	if (mysqli_connect_error()) {
		$code = '@connect-error';
		$message = mysqli_connect_error();
	}

	if (mysqli_error($dbh)) {
		$code = '@query-error';
		$message =mysqli_error($dbh);
	}

	render('error', array(
		'code' => $code, 'message' => $message,
	));
	exit;
}

/*
 * Извлекает из базы компании текущего пользователя
 */
function db_company_find_all_for_current_user($dbh, $id)
{
	$query = 'SELECT company.Id, company.Date_Registr, company.Date_Begin_Work, company.Name, company.INN, OPF.Brief_Name as opf_brief_name, OPF.Full_Name as opf_full_name, SNO.Brief_Name as sno_brief_name, SNO.Full_Name as sno_full_name FROM company, OPF, SNO WHERE company.SNO_Id=SNO.Id and company.OPF_Id=OPF.Id and user_id=?';
    // подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 's', $id);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);
	// последовательно извлекаем строки
	while ($row = mysqli_fetch_assoc($qr))
		$result[] = $row;

	// освобождаем ресурсы, связанные с хранением результата
	mysqli_free_result($qr);

	return $result;
}

/*
 * Извлекает из базы данных список пользователей
 */
function db_user_find_all($dbh)
{
	$query = 'SELECT * FROM users';
	$result = array();

	// выполняем запрос к базе данных
	$qr = mysqli_query($dbh, $query, MYSQLI_STORE_RESULT);
	if ($qr === false)
		db_handle_error($dbh);

	// последовательно извлекаем строки
	while ($row = mysqli_fetch_assoc($qr))
		$result[] = $row;

	// освобождаем ресурсы, связанные с хранением результата
	mysqli_free_result($qr);

	return $result;
}

/*
 * Выполняет поиск в базе данных и загрузку пользователя с указанным id
 */
function db_user_find_by_id($dbh, $id)
{
	$query = 'SELECT * FROM users WHERE id=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 's', $id);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);

	return $result;
}

/*
 * Выполняет загрузку всех SNO
 */
function db_sno($dbh)
{
	$query = 'SELECT * FROM SNO';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);


	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// последовательно извлекаем строки
	while ($row = mysqli_fetch_assoc($qr))
		$result[] = $row;

	// освобождаем ресурсы, связанные с хранением результата
	mysqli_free_result($qr);

	return $result;
}

/*
 *  Выполняет загрузку всех OPF
 */
function db_opf($dbh)
{
	$query = 'SELECT * FROM OPF';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// последовательно извлекаем строки
	while ($row = mysqli_fetch_assoc($qr))
		$result[] = $row;

	// освобождаем ресурсы, связанные с хранением результата
	mysqli_free_result($qr);

	return $result;
}


/*
 * Выполняет поиск в базе данных и возвращает id пользователя с указанным email
 */
function db_user_id_find_by_email($dbh, $mail)
{
	$query = 'SELECT id  FROM users WHERE email=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 's', $mail);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	// извлекаем id
	$id_email=$result['id'];

	return $id_email;
}


/*
 * Выполняет поиск в базе данных и проверяет, есть ли пользователь с указанным никнеймом
 */
function db_freedom_nick_find($dbh, $nickname)
{
	$query = 'SELECT COUNT(*)  FROM users WHERE nickname=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 's', $nickname);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	if ($result['COUNT(*)']==0)
	{
		return true;
	}

	return false;
}

/*
 * Выполняет поиск в базе данных и проверяет, есть ли пользователь
 * с указанным адресом электронной почты
 */
function db_freedom_email_find($dbh, $email)
{
	$query = 'SELECT COUNT(*)  FROM users WHERE email=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 's', $email);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	if ($result['COUNT(*)']==0)
	{
		return true;
	}

	return false;
}

/*
 * Выполняет проверяет, принадлежит ли компания пользователю
 */
function db_company_have_user($dbh, $id_company, $id_user)
{
	$query = 'SELECT COUNT(*)  FROM company WHERE Id=? and User_Id=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 'ii', $id_company, $id_user);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	if ($result['COUNT(*)']==0)
	{
		return false;
	}

	return true;
}

/*
 * Выполняет поиск в базе данных и загрузку пользователя с указанным логином
 * (логином считаем адрес электронной почты и ник пользователя)
 */
function db_user_find_by_login($dbh, $login)
{
	$query = 'SELECT * FROM users WHERE email=? OR nickname=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 'ss', $login, $login);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);

	return $result;
}

/*
 * 
 */
function db_socid_user_array($dbh, $id)
{
	$query = 'SELECT * FROM auth_social WHERE id_user=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);

	mysqli_stmt_bind_param($stmt, 'i', $id);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);

	return $result;
}

/*
 * Выполняет поиск id пользователя по id социальной сети
 */
function db_id_find_by_socid($dbh, $soc, $socid)
{
	$query = 'SELECT id_user FROM `auth_social` WHERE '.$soc.'=?';
	//$query = 'SELECT id_user FROM `auth_social` WHERE vk=?';	
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 'i',$socid);
	// выполняем запрос eи получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	// извлекаем id
	$id_soc=$result['id_user'];
	return $id_soc;
}

/*
 * Выполняет удаление неактуальных данных из таблицы not_end_registration
 */
function db_del_not_reg($dbh, $hash)
{
	$query = 'DELETE FROM `not_end_registration` WHERE hash=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 'i',$hash);
	// выполняем запрос eи получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением запроса
	mysqli_stmt_close($stmt);
	
	return true;
}

/*
 * Вставляет в базу данных строку с информацией о пользователе, возвращает массив
 * с данными пользователя и его id в базе данных
 */
function db_user_insert($dbh, $user)
{
	$query = 'INSERT INTO users(nickname,email,password,fullname,newsletter,status_active) VALUES(?,?,?,?,?,?)';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	$user['status_active']=0;
	$pieces = explode("@", $user['email']);
	if($pieces[1]=='mail.ru')
	{
		$user['status_active']=1;
	}
	mysqli_stmt_bind_param($stmt, 'ssssii',
		$user['nickname'], $user['email'], $user['password'],
		$user['fullname'], $user['newsletter'], $user['status_active']);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем идентификатор вставленной записи
	$user['id'] = mysqli_insert_id($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return $user;
}

/*
 * Вставляет в базу данных временную информацию о смене пароля
 */
function forget_password_add($dbh, $email,$microtime)
{
	$query = 'REPLACE INTO forget_password(email_user,time) VALUES(?,?)';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	$user['status_active']=0;
	mysqli_stmt_bind_param($stmt, 'ss', $email, $microtime);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем идентификатор вставленной записи
	$user['id'] = mysqli_insert_id($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return $user;
}

/*
 * Выполняет удаление неактуальных данных из таблицы not_end_registration
 */
function forget_password_del($dbh, $email)
{
	$query = 'DELETE FROM `forget_password` WHERE email_user=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 's',$email);
	// выполняем запрос eи получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением запроса
	mysqli_stmt_close($stmt);
	
	return true;
}


/*
 * Выполняет удаление компании из списка компаний
 */
function db_company_del($dbh, $id)
{
	$query = 'DELETE FROM `company` WHERE id=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 'i',$id);
	// выполняем запрос eи получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением запроса
	mysqli_stmt_close($stmt);
	
	return true;
}

/*
 * Выполняет поиск данных из таблицы not_end_registration
 */
function forget_password_find_time($dbh, $email)
{
	$query = 'SELECT time FROM `forget_password` WHERE email_user=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 's',$email);
	// выполняем запрос eи получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	// извлекаем time
	$time=$result['time'];
	return $time;
}



/*
 * Вставляет в базу данных !ПУСТУЮ! строку с информацией о социальной сети пользователя
 *
 */
function db_user_socid_insert_null($dbh, $db_user_id)
{

	$query = 'INSERT auth_social(id_user) VALUES(?)';


	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	$user['status_active']=0;
	mysqli_stmt_bind_param($stmt, 'i', $db_user_id);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return true;
}



/*
 * Вставляет в базу данных строку с информацией о социальной сети пользователя
 *
 */
function db_user_socid_insert($dbh, $soc_data, $db_user_id)
{
	$soc=$soc_data["social"];
	
	$query = 'INSERT INTO auth_social('.$soc.',id_user) VALUES(?,?) ON DUPLICATE KEY UPDATE '.$soc.'=?';


	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	$user['status_active']=0;
	mysqli_stmt_bind_param($stmt, 'iii', $soc_data['id'], $db_user_id, $soc_data['id']);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return true;
}

/*
 * Вставляет в базу данных строку с информацией о id пользователя в соц сети, с помощью которой он регистрируется
 */
function db_user_not_reg_insert($dbh, $soc, $socid)
{
	$query = 'INSERT IGNORE INTO not_end_registration(id,social,hash) VALUES(?,?,?)';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	$user['status_active']=0;
	$hash=md5($socid.$soc.SALT2);
	mysqli_stmt_bind_param($stmt, 'iss',
		$socid, $soc, $hash);
	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем идентификатор вставленной записи

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return true;
}


/*
 * Вставляет в базу данных строку с информацией о id пользователя в соц сети, с помощью которой он регистрируется
 */
function db_user_social_reg_ok_insert($dbh, $socid_soc)
{
	$query = 'SELECT id,social FROM not_end_registration WHERE hash=?';

	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	
	$hash=md5($socid_soc.SALT2);
	
	mysqli_stmt_bind_param($stmt, 's', $hash);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем результирующий набор строк
	$qr = mysqli_stmt_get_result($stmt);
	if ($qr === false)
		db_handle_error($dbh);

	// извлекаем результирующую строку
	$result = mysqli_fetch_assoc($qr);
	
	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_free_result($qr);
	mysqli_stmt_close($stmt);
	
	db_del_not_reg($dbh, $hash);
	
	return $result;
}


/*
 * Обновление данных пользователя в базе данных
 */
function db_user_update($dbh, $user)
{
	$query = 'UPDATE `users` SET `nickname`=?,`email`=?,`password`=?,`fullname`=?,`newsletter`=? WHERE `id`=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 'ssssii',
		$user['nickname'], $user['email'], $user['password'],
		$user['fullname'], $user['newsletter'], $user['id']);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// получаем идентификатор вставленной записи
	$user['id'] = mysqli_insert_id($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return $user;
}



/*
 * Обновление статуса пользователя в базе данных
 */
function db_user_status_update($dbh, $id_mail)
{
	$query = 'UPDATE `users` SET `status_active`=1 WHERE `id`=?';
	// подготовливаем запрос для выполнения
	$stmt = mysqli_prepare($dbh, $query);
	if ($stmt === false)
		db_handle_error($dbh);
	mysqli_stmt_bind_param($stmt, 'i', $id_mail);

	// выполняем запрос и получаем результат
	if (mysqli_stmt_execute($stmt) === false)
		db_handle_error($dbh);

	// освобождаем ресурсы, связанные с хранением результата и запроса
	mysqli_stmt_close($stmt);

	return true;
}