<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/script/app_config.php');

define('MAX_LENGTH_COMPANY_NAME', 150);
define('MAX_LENGTH_GSZ_BRIEF_NAME', 30);
define('MAX_LENGTH_GSZ_FULL_NAME', 150);
define('ERROR_MESSAGE_PREFIX', 'Ошибка при выполнении последней операции: ');
define('NO_ERRORS_MESSAGE', 'NO_ERRORS');
define('NO_WARNINGS_MESSAGE', 'NO_WARNINGS');
define('HTML_PATH_GSZ_LIST_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/gsz_list.php');
define('HTML_PATH_GSZ_ADD_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/gsz_add.php');
define('HTML_PATH_GSZ_EDIT_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/gsz_edit_item.php');
define('HTML_PATH_GSZ_DELETE_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/gsz_confirm_delete.php');
define('HTML_PATH_GSZ_SAVE_ITEM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/script/gsz_save_item.php');
define('HTML_PATH_COMPANY_LIST_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/company_list.php');
define('HTML_PATH_COMPANY_ADD_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/company_add.php');
define('HTML_PATH_COMPANY_EDIT_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/company_edit.php');
define('HTML_PATH_COMPANY_DELETE_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/company_confirm_delete.php');
define('HTML_PATH_COMPANY_SAVE_ITEM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/script/company_save_item.php');
define('HTML_PATH_FINANCE_GSZ_LIST_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/finance_gsz_list.php');
define('HTML_PATH_FINANCE_COMPANY_LIST_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/finance_company_list.php');
define('HTML_PATH_DATE_CALC_LIMIT_EDIT_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/date_calc_limit_edit.php');
define('HTML_PATH_DATE_CALC_LIMIT_SAVE', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/script/date_calc_limit_save.php');
define('HTML_PATH_BALANCE_DATES', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/balance_dates.php');
define('HTML_PATH_BALANCE_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/balance_form.php');
define('HTML_PATH_FINANCE_IP_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/finance_ip.php');
define('HTML_PATH_FINANCE_CORPORATION_FORM', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/finance_corporation.php');
define('HTML_PATH_BALANCE_SAVE_VALUES', 'http://'.$_SERVER['HTTP_HOST'].'/cred_limit/script/balance_save_values.php');

// Подключение к БД
$mysqli = db_connect();
$mysqli->set_charset("utf8");


class GSZ_Item 
{
	public $Id, $Brief_Name, $Full_Name, $Date_Begin_Work, $NumberCompany, $Calc_limit_dates_Id, $Date_calc_limit;

	function __construct($id)
	{
		$query = "SELECT `Brief_Name`, `Full_Name` FROM GSZ WHERE `Id`={$id}";
		$row = getRow($query);
	
		$this->Id = $id;
		$this->Brief_Name = htmlspecialchars($row['Brief_Name']);
		$this->Full_Name = htmlspecialchars($row['Full_Name']);

		$query = "SELECT min(`Date_Begin_Work`) FROM `company` WHERE `GSZ_Id`={$id}";
		$Date_Begin_Work = getCell($query);
		$this->Date_Begin_Work = (is_null($Date_Begin_Work) ? "" : $Date_Begin_Work); 

		$query = "SELECT COUNT(*) FROM `company` WHERE `GSZ_Id`={$id}";
		$NumberCompany = getCell($query);
		$this->NumberCompany = $NumberCompany;

		$query = "SELECT `Id`, `Date_calc_limit` FROM `calc_limit_dates` WHERE `GSZ_Id`={$id}";
		$result_array = getRow($query);

		$this->Calc_limit_dates_Id = $result_array['Id'];
		$Date_calc_limit = $result_array['Date_calc_limit'];
		$this->Date_calc_limit = (is_null($Date_calc_limit) ? "" : $Date_calc_limit); 

	}
}

class Company_Item 
{
	public $Id, $Name, $INN, $GSZ_Id, $OPF_Id, $OPF, $SNO_Id, $SNO;
	
	function __construct($Company_Id)
	{
		$query = "SELECT `A`.`Name` AS `Name`, `A`.`INN` AS `INN`, `A`.`GSZ_Id` AS `GSZ_Id`, `A`.`OPF_Id` AS `OPF_Id`, `A`.`SNO_Id` AS `SNO_Id`, `B`.`Brief_Name` AS `OPF`, ";
		$query .= " `C`.`Brief_Name` AS `SNO`, `D`.`Brief_Name` AS  `GSZ_Name`, `A`.`Date_Registr`, `A`.`Date_Begin_Work`, `B`.`Is_Corporation` ";
		$query .= "FROM `Company` `A`, `OPF` `B`, `SNO` `C` , `GSZ` `D` ";
		$query .= "WHERE `A`.`Id`={$Company_Id} AND (`A`.`OPF_Id`=`B`.`Id`) AND (`A`.`SNO_Id`=`C`.`Id`) AND (`A`.`GSZ_Id`=`D`.`Id`)";
		
		$row = getRow($query);
		
		$this->Id = $Company_Id;
		$this->Name = htmlspecialchars($row['Name']);
		$this->INN = $row['INN'];
		$this->GSZ_Id = $row['GSZ_Id'];
		$this->GSZ_Name = htmlspecialchars($row['GSZ_Name']);
		$this->OPF_Id = $row['OPF_Id'];
		$this->SNO_Id = $row['SNO_Id'];
		$this->OPF = $row['OPF'];
		$this->SNO = $row['SNO'];
		$this->Date_Registr = (is_null($row['Date_Registr']) ? "" : $row['Date_Registr']); 
		$this->Date_Begin_Work = (is_null($row['Date_Begin_Work']) ? "" : $row['Date_Begin_Work']); 
		$this->Is_Corporation = $row['Is_Corporation'];
	}
}

function get_GSZ_set()
{
	$query = "SELECT * FROM `gsz` WHERE `User_Id`=".get_current_user_id()." ORDER BY `Brief_Name`";
	$GSZ_set = getTable($query);
	return $GSZ_set;
}

function get_GSZ_set_with_calc_limit_date()
{

	$query = "SELECT\n"
    . " `GSZ`.`Id` AS `GSZ_Id`, \n"
    . " `GSZ`.`Brief_Name`,\n"
    . " min(`company`.`Date_Begin_Work`) AS `Date_begin_work`,\n"
    . " count(`company`.Id) AS `Count_company`,\n"
    . " `calc_limit_dates`.`Date_calc_limit`,\n"
    . " `calc_limit_dates`.`Id` AS `Calc_limit_dates_Id`\n"
    . "FROM\n"
    . " `GSZ`, `company`,`calc_limit_dates`\n"
    . "WHERE `GSZ`.`Id`=`company`.`GSZ_Id` AND `GSZ`.`Id`=`calc_limit_dates`.`GSZ_Id`\n"
    . "GROUP BY `GSZ`.`Id`";
	
	$GSZ_set = getTable($query);
	return $GSZ_set;
}

function get_company_set($GSZ_Id)
{
	// company_set := array of {Id, Name, INN, OPF, SNO, Date_Registr, Date_Begin_Work, Is_Corporation}
	$query = "SELECT \n"
    . " `A`.`Id` AS `Id`, \n"
    . " `A`.`Name` AS `Name`, \n"
    . " `A`.`INN` AS `INN`, \n"
    . " `B`.`Brief_Name` AS `OPF`, \n"
    . " `C`.`Brief_Name` AS `SNO`, \n"
    . " `A`.`Date_Registr`, \n"
    . " `A`.`Date_Begin_Work`,\n"
    . " `B`.`Is_Corporation`\n"
    . "FROM \n"
    . " `Company` `A`, \n"
    . " `OPF` `B`, \n"
    . " `SNO` `C` \n"
    . "WHERE \n"
    . " (`A`.`GSZ_Id` = {$GSZ_Id}) \n"
    . " AND (`A`.`OPF_Id` = `B`.`Id`) \n"
    . " AND (`A`.`SNO_Id` = `C`.`Id`)";
	
	$company_set = getTable($query);
	return $company_set;
}

function get_error_message() 
{
	global $get;
	if (isset($_GET['error']))
		return (ERROR_MESSAGE_PREFIX . '<strong>'.htmlspecialchars(urldecode($_GET['error'])).'.</strong>');
	else
		return NO_ERRORS_MESSAGE;
}

function get_warning_message() 
{
	global $get;
	if (isset($_GET['warning']))
		// return ('<strong>'.htmlspecialchars(urldecode($_GET['warning'])).'.</strong>');
		return ('<strong>'.htmlspecialchars(htmlspecialchars_decode(urldecode($_GET['warning']))).'.</strong>');
	else
		return NO_WARNINGS_MESSAGE;
}

function get_OPF_Id_by_Name($OPF_Name)
{
	$query = 'SELECT `Id` FROM `OPF` WHERE `Brief_Name`="'.$OPF_Name.'"';
	$OPF_Id = getCell($query);
	return ($OPF_Id ? $OPF_Id : -1); 
}

function get_SNO_Id_by_Name($SNO_Name)
{
	$query = 'SELECT `Id` FROM `SNO` WHERE `Brief_Name`="'.$SNO_Name.'"';
	$SNO_Id = getCell($query);
	return ($SNO_Id ? $SNO_Id : -1); 
}

function get_OPF_names()
{
	// array_OPF_names := {Brief_Name => INN_Length}
	$array_OPF_names = [];
	$query = 'SELECT `Brief_Name`, `INN_Length` FROM `OPF`';
	$result_array = getTable($query);
	if (!$result_array) 
		return $array_OPF_names;
	foreach ($result_array as $row)
	{
		$array_OPF_names[$row['Brief_Name']]=$row['INN_Length'];
	}
	return $array_OPF_names;	
}

function get_SNO_names()
{
	// array_SNO_names := {Brief_Name => Cred_Limit_Affect}
	$array_SNO_names = [];
	$query = 'SELECT `Brief_Name`, `Cred_Limit_Affect` FROM `SNO`';
	$result_array = getTable($query);
	if (!$result_array) return $array_SNO_names;
	foreach ($result_array as $row)
	{
		$array_SNO_names[$row['Brief_Name']]=$row['Cred_Limit_Affect'];
	}
	return $array_SNO_names;	
}

function fill_calc_limit_dates()
{
	// Найдем все ГСЗ из таблицы GSZ, для которых не введена дата расчета кредитного лимита в таблице calc_limit_dates
	$query = 'SELECT `GSZ`.`Id` FROM `GSZ` LEFT JOIN `calc_limit_dates` ON `GSZ`.`Id`=`calc_limit_dates`.`GSZ_Id` WHERE `calc_limit_dates`.`GSZ_Id` IS NULL;';
	$GSZ_array = getCol($query);
	// Всем найденным ГСЗ в качестве даты расчета кредитного лимита проставим текущую дату 
	$data = [];
	foreach ($GSZ_array as $GSZ_Id)
	{
		$data["Date_calc_limit"] = date("Y.m.d");
		$data["GSZ_Id"] = $GSZ_Id;
		$result = addRow("calc_limit_dates", $data);
	}
}

function get_Balance_Dates($Date_calc_limit, $is_Corporation = 0)
{
	$Balance_Dates = [];

	$Date1 = new DateTime($Date_calc_limit);
	if ($is_Corporation)
	{
		//Для организации даты определения баланса: {Начало_текущего_квартала - 6 месяцев, Начало_текущего_квартала - 3 месяца, Начало_текущего_квартала }
		$month = (int)($Date1->format("n"));
		if ($month <= 3) $Date3 = $Date1->format("Y-01-01");
		if (($month >= 4) and ($month <= 6)) $Date3 = $Date1->format("Y-04-01");
		if (($month >= 7) and ($month <= 9)) $Date3 = $Date1->format("Y-07-01");
		if ($month >= 10) $Date3 = $Date1->format("Y-10-01");
	}
	else
	{
		//Для ИП даты определения баланса: {Дата_расчета_лимита - 6 месяцев, Дата_расчета_лимита - 3 месяцеа, Дата_расчета_лимита }
		$Date3 = $Date_calc_limit;
	};
	
	$Date = new DateTime($Date3);
	$Date->modify("-6 month");
	$Balance_Dates[] = $Date->format("Y-m-d");
	$Date = new DateTime($Date3);
	$Date->modify("-3 month");
	$Balance_Dates[] = $Date->format("Y-m-d");
	$Balance_Dates[] = $Date3;

	return $Balance_Dates;
}

// Проверка наличия записей о балансе компании на определенную дату в таблице Corp_Balance_Results
function is_Balance_Exists($Company_Id, $Balance_Date, $Balance_Part)
{
	$query = "SELECT count(*) FROM `Corp_Balance_Results` WHERE `Balance_Part`={$Balance_Part} AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";
	$count_rows = getCell($query);

	return ($count_rows>1);
}

function get_Corporation_Balance_Part($Company_Id, $Balance_Date, $Type_Balance = "active", $is_Corporation = 0)
{
	// $Balance_Part=1 - актив, $Balance_Part=2 - пассив
	$Balance_Part =(strtolower($Type_Balance) == "active" ? 1 : 2);

	// Таблица Corp_Balance_Articles - структура статей баланса для предприятий
	// Таблица Indived_Balance_Articles - структура статей баланса для ИП
	$Balance_Articles_table = ($is_Corporation ? 'Corp_Balance_Articles' : 'Individ_Balance_Articles');

	$Balance_Active = [];
	// Список всех разделов баланса (актив)
	$is_Balance_Exists = is_Balance_Exists($Company_Id, $Balance_Date, $Balance_Part);

	if ($is_Balance_Exists)
		$query = "SELECT `Code`, `Description`, `Value` FROM `Corp_Balance_Results` \n"
		. "WHERE `Is_Section`=1 AND `Balance_Part`={$Balance_Part} AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}' \n"
		. "ORDER BY `Code`";
	else
		$query = "SELECT `Code`, `Description`, `Value` FROM `{$Balance_Articles_table}` \n"
		. "WHERE `Is_Section`=1 AND `Balance_Part`={$Balance_Part} \n"
		. "ORDER BY `Code`";

	$Sections_Active = getTable($query);
	foreach ($Sections_Active as $Section)
	{
		// Формируем заголовок очередного раздела баланса
		$article = [];
		$article['Code'] = $Section['Code'];
		$article['Is_Section'] = 1;
		$article['Is_Sum_Section'] = 0;
		$article['Is_Sum_Part'] = 0;
		$article['Description'] = '<b>'.htmlspecialchars($Section['Description']).'</b>';
		$Balance_Active[] = $article;

		// Строим список всех статей баланса, входящих в текущую статью
		if ($is_Balance_Exists)
			$query = "SELECT `Code`, `Description`, `Value`, `Parent_Code`, `Has_children` \n"
			. "FROM `Corp_Balance_Results` \n"
			. "WHERE `Is_Section`=0 AND `Section_Code`={$Section['Code']} AND `Is_Sum_Section`=0 "
			. "AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'\n"
			. "ORDER BY `Code`";
		else
			$query = "SELECT `Code`, `Description`, `Value`, `Parent_Code`, `Has_children` \n"
			. "FROM `{$Balance_Articles_table}` \n"
			. "WHERE `Is_Section`=0 AND `Section_Code`={$Section['Code']} AND `Is_Sum_Section`=0 \n"
			. "ORDER BY `Code`";

		$Section_articles = getTable($query);
		foreach ($Section_articles as $Section_article)
		{
			$article = [];
			$article['Code'] = $Section_article['Code'];
			$article['Is_Section'] = 0;
			$article['Is_Sum_Section'] = 0;
			$article['Is_Sum_Part'] = 0;
			$article['Is_Editable_Value'] = (! $Section_article['Has_children']);

			// Если статья баланса является дочерней, то название ее смещаем враво на два пробела
			$prefix = ($Section_article['Parent_Code'] ? '&nbsp &nbsp' : ''); 
			$article['Description'] = $prefix . htmlspecialchars($Section_article['Description']);

			if ($Section_article['Has_children'])
			{
				// Если статья явлется составной, то вычисляем сумму значений всех статей, входящих в нее 
				if ($is_Balance_Exists)
					$query = "SELECT SUM(`Value`) FROM `Corp_Balance_Results` "
					. "WHERE `Parent_Code` = '{$Section_article['Code']}' AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}' ";
				else
					$query = "SELECT SUM(`Value`) FROM `{$Balance_Articles_table}` WHERE `Parent_Code` = '{$Section_article['Code']}' ";
				$Value = getCell($query);
				$article['Value'] = $Value;
			}
			else
				// Статья не является составной, ее значение можно будет менять
				$article['Value'] = $Section_article['Value'];

			$Balance_Active[] = $article;
		}
		
		// Формирование записи для суммы раздела
		// Находим код и название статьи для суммы раздела
		$article = [];
		if ($is_Balance_Exists)
			$query = "SELECT `Code`, `Description` FROM `Corp_Balance_Results` \n"
			. "WHERE `Section_Code`={$Section['Code']} AND `Is_Sum_Section`=1 "
			. "AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";
		else
			$query = "SELECT `Code`, `Description` FROM `{$Balance_Articles_table}` \n"
			. "WHERE `Section_Code`={$Section['Code']} AND `Is_Sum_Section`=1";
		$row = getRow($query);
		$article['Code'] = $row['Code'];
		$article['Description'] = "<b>".$row['Description']."</b>";
		
		// Находим сумму значений всех статей, входящих в текущий раздел
		if ($is_Balance_Exists)
			$query = "SELECT SUM(`Value`) FROM `Corp_Balance_Results` "
			. "WHERE `Section_Code`={$Section['Code']} AND `Has_Children`=0 AND `Is_Section`=0 "
			."AND `Is_Sum_Section`=0 AND `Is_Sum_Part`=0 AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";
		else
			$query = "SELECT SUM(`Value`) FROM `{$Balance_Articles_table}` WHERE `Section_Code`={$Section['Code']} "
			." AND `Has_Children`=0 AND `Is_Section`=0 AND `Is_Sum_Section`=0 AND `Is_Sum_Part`=0";
		$article['Value'] = getCell($query);
		$article['Is_Sum_Section'] = 1;
		$article['Is_Section'] = 0;
		$article['Is_Sum_Part'] = 0;
		$Balance_Active[] = $article;
	}

	// Формирование записи для суммы (баланса) всего актива/пассива
	// Находим код и название статьи для баланса актива/пассива
	$article = [];
	if ($is_Balance_Exists)
		$query = "SELECT `Code`, `Description` FROM `Corp_Balance_Results` \n"
		. "WHERE `Balance_Part`={$Balance_Part} AND `Is_Sum_Part`=1 AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";
	else
		$query = "SELECT `Code`, `Description` FROM `{$Balance_Articles_table}` WHERE `Balance_Part`={$Balance_Part} AND `Is_Sum_Part`=1";
	$row = getRow($query);
	$article['Code'] = $row['Code'];
	$article['Description'] = "<b>".$row['Description']."</b>";
	
	// Находим сумму значений всех статей, входящих в актив/пассив баланса
	if ($is_Balance_Exists)
		$query = "SELECT SUM(`Value`) FROM `Corp_Balance_Results` "
		. "WHERE `Balance_Part`={$Balance_Part} AND `Has_Children`=0 AND `Is_Section`=0 "
		."AND `Is_Sum_Section`=0 AND `Is_Sum_Part`=0 AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";
	else
		$query = "SELECT SUM(`Value`) FROM `{$Balance_Articles_table}` WHERE `Balance_Part`={$Balance_Part} "
		."AND `Has_Children`=0 AND `Is_Section`=0 AND `Is_Sum_Section`=0 AND `Is_Sum_Part`=0";
	$article['Value'] = getCell($query);
	$article['Is_Sum_Section'] = 0;
	$article['Is_Section'] = 0;
	$article['Is_Sum_Part'] = 1;
	$Balance_Active[] = $article;

	return $Balance_Active;
}

function delete_Balance_Values($Company_Id, $Balance_Date)
{
	global $mysqli;
	$query = "DELETE FROM `Corp_Balance_Results` WHERE `Date_Balance`='{$Balance_Date}' AND `Company_Id`={$Company_Id}";
    return $mysqli->query($query);	
}

function calculate_Balance($Company_Id, $Balance_Date, $Type_Balance = "active")
{
	// $Balance_Part=1 - актив, $Balance_Part=2 - пассив
	$Balance_Part =(strtolower($Type_Balance) == "active" ? 1 : 2);
	
	$query = "SELECT SUM(`Value`) FROM `Corp_Balance_Results` "
	. "WHERE `Balance_Part`={$Balance_Part} AND `Has_Children`=0 AND `Is_Section`=0 "
	."AND `Is_Sum_Section`=0 AND `Is_Sum_Part`=0 AND `Company_Id`={$Company_Id} AND `Date_Balance`='{$Balance_Date}'";	
	$Balance = getCell($query);
	return $Balance;
}
