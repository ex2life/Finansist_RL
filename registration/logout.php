<?php

require('lib/common.php');

function main()
{
	// создаем сессию
	session_start();

	// выполняем выход из системы и перенаправляем пользователя на главную страницу
	logout_user();
	redirect('../index.php');
}

main();