<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/script/app_config.php');
	require_once('cred_limit_scripts.php');

	if (!isset($request["action"])) exit;

	switch ($request["action"])
	{
		case 'add':
			if ((!isset($_POST['GSZ_Id'])) || (!ctype_digit($_POST['GSZ_Id'])))
			{
				$error_message = urlencode("Указан некорректный код ГСЗ");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}
			$GSZ_Id = $_POST['GSZ_Id'];
			
			$data = [];
			$data["Name"] = $_POST['Company_Name'];
			$data["INN"] = $_POST['INN'];
			$data["GSZ_Id"] = $_POST['GSZ_Id'];
			$data["OPF_Id"] = get_OPF_Id_by_Name($_POST['OPF']);
			$data["SNO_Id"] = get_SNO_Id_by_Name($_POST['SNO']);
			$data["Date_Registr"] = $_POST['Date_Registr'];
			$data["Date_Begin_Work"] = $_POST['Date_Begin_Work'];
			
			$result = addRow("Company", $data);
			if (!$result) $error_message = urlencode("Ошибка при добавлении компании в ГСЗ ({$mysqli->error})");
			break;
		
		case 'update':
			if ((!isset($_POST['GSZ_Id'])) || (!ctype_digit($_POST['GSZ_Id'])))
			{
				$error_message = urlencode("Указан некорректный код ГСЗ");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}
			$GSZ_Id = $_POST['GSZ_Id'];

			if ((!isset($_POST['Company_Id'])) || (!ctype_digit($_POST['Company_Id'])))
			{
				$error_message = urlencode("Указан некорректный код обновляемой компании");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}
			$data = [];
			$data["Id"] = $_POST['Company_Id'];
			$data["GSZ_Id"] = $_POST['GSZ_Id'];
			$data["Name"] = $_POST['Company_Name'];
			$data["INN"] = $_POST['INN'];
			$data["Date_Registr"] = $_POST['Date_Registr'];
			$data["Date_Begin_Work"] = $_POST['Date_Begin_Work'];
			
			$data["OPF_Id"] = get_OPF_Id_by_Name($_POST['OPF']);
			$data["SNO_Id"] = get_SNO_Id_by_Name($_POST['SNO']);
			if ($data["OPF_Id"] == -1) 
			{
				$error_message = urlencode("Не найден код ОПФ");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}
			elseif ($data["SNO_Id"] == -1)
			{
				$error_message = urlencode("Не найден код СНО");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}

			$result = setRow("Company", $_POST['Company_Id'], $data);
			if (!$result) $error_message = urlencode("Ошибка при сохранении данных о компании ({$mysqli->error})");
			// $query = 'UPDATE `Company` SET `Name`="'.$Name.'", `INN`='.$INN.', `OPF_Id`='.$OPF_Id.', `SNO_Id`='.$SNO_Id.' WHERE `Id`='.$Id;
			break;
		
		case 'delete':
			if ((!isset($_GET['GSZ_Id'])) || (!ctype_digit($_GET['GSZ_Id'])))
			{
				$error_message = urlencode("Указан некорректный код ГСЗ удаляемой компании");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}
			$GSZ_Id = $_GET['GSZ_Id'];
			
			if ((!isset($_GET['Company_Id'])) || (!ctype_digit($_GET['Company_Id'])))
			{
				$error_message = urlencode("Указан некорректный код удаляемой компании");
				redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
			}

			$result = deleteRow("Company", $_GET['Company_Id']);
			if (!$result) $error_message = urlencode("Ошибка при удалении компании из ГСЗ ({$mysqli->error})");
			// $query = 'DELETE FROM `Company` WHERE `Id`='.$get['Company_Id'];
			break;
		
		default:
			$error_message = urlencode("Указан неверный код операции с компанией из ГСЗ");
			redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");
	}
	$result ? redirect(HTML_PATH_COMPANY_LIST_FORM.'?GSZ_Id='.$GSZ_Id) : redirect(HTML_PATH_GSZ_LIST_FORM."?error={$error_message}");	
