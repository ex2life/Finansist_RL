<?php

// ===========================================================================================
// Служебные функции
// ===========================================================================================

require_once $_SERVER['DOCUMENT_ROOT'].'/registration/lib/common.php';

function is_Date($str)
{
    return is_numeric(strtotime($str));
}


function xss($data)
{
    if (is_array($data)) 
    {
        $escaped = array();
        foreach ($data as $key => $value)
        {
            $escaped[$key] = xss($value);
        }
        return $escaped;
    }
    return trim(htmlspecialchars($data));
}


function dump($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function getCell($query) 
{
    global $mysqli;
    $result_set = $mysqli->query($query);
    if (is_null($result_set) || !$result_set->num_rows) return false;
    $arr = array_values($result_set->fetch_assoc());
    $result_set->close();
    return $arr[0]; 
}

function getRow($query)
{
    global $mysqli;
    $result_set = $mysqli->query($query);
    if (is_null($result_set)) return false;
    $row = $result_set->fetch_assoc();
    $result_set->close();
    return $row;    
}

function getCol($query)
{
    global $mysqli;
    $result_set = $mysqli->query($query);
    $arr = [];
    if (is_null($result_set)) return false;

    while (($row = $result_set->fetch_assoc()) !=false)    
    {
        $arr[]=reset($row);
    }
    $result_set->close();
    return $arr;
}

function getTable($query)
{
    global $mysqli;
    $result_set = $mysqli->query($query);
    if (is_null($result_set)) return false;
    $result = array();
    while (($row = $result_set->fetch_assoc()) !=false)    
    {
        $result[]=$row;
    }
    $result_set->close();
    return $result;
}

function addRow($table, $data)
{
    global $mysqli;
    
    $query = "INSERT INTO `$table` (";
    foreach ($data as $key => $value) $query .= "`$key`,";
    $query = substr($query, 0, -1);
    $query .=") VALUES (";
    foreach ($data as $key=>$value) 
    {
        if (is_null($value)) $query .= "null,";
        else $query .= "'". $mysqli->real_escape_string($value). "',";
    }
    $query = substr($query, 0, -1);
    $query .= ")";
    
    $result_set = $mysqli->query($query);
    if (!$result_set) return false;
    return $mysqli->insert_id;
}

function setRow($table, $id, $data)
{
    if (!is_numeric($id)) exit;
    
    global $mysqli;
    $query = "UPDATE `$table` SET ";
    foreach ($data as $key=>$value) 
    {
        $query .= "`$key` = ";
        if (is_null($value)) $query .= "null, ";
        else $query .= "'". $mysqli->real_escape_string($value). "',";
        
    }
    $query = substr($query, 0, -1);
    $query .= " WHERE `id`='$id'";
    return $mysqli->query($query);
}

function deleteRow($table, $id)
{
    if (!is_numeric($id)) exit;
    global $mysqli;
    $query = "DELETE FROM `$table` WHERE `id`='$id'";
    return $mysqli->query($query);
}

?>
