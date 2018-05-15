<?php
require_once 'connection_params.php';

//echo MYSQL_DB;

function db_connect(){
	$mysqli = @new mysqli(MYSQL_SERVER, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
	
	if ($mysqli->connect_errno) {
		exit("Ошибка при подключении к БД: ".$mysqli->connect_error);
	}	
	
    if (!$mysqli->set_charset("utf8")){
        printf("Error: ".$mysqli->error);
    }
    
   return $mysqli; 
}
?>
