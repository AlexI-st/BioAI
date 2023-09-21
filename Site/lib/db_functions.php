<?php

function DeleteRow($db_connection, $table_name, $primary_keys){
    $keyData = Array();
    $keyQuery = "";

    if (is_array($primary_keys)){
        foreach ($primary_keys as $value) {
            $key = key($value);
            $keyValue = $value[$key];
            array_push($keyData,"`$key`='$keyValue'");
        }
        $keyQuery = join(" AND ", $keyData);
    }
    else {
        $key = key($primary_keys);
        $value = $primary_keys[$key];
        $keyData .= "`$key`='$value'";
    }
    
    $query = "DELETE FROM $table_name WHERE $keyQuery";
    $result = mysqli_query($db_connection, $query);
    return $result;
}

function InsertRow($db_connection, $table_name, $row_data){
    $keys = "";
    $values = "";
    foreach ($row_data as $key => $value) {
        $keys .= "`$key`,";
        $values .= "'$value',";
    }
    $keys = rtrim($keys, ",");
    $values = rtrim($values, ",");
    
    $query = "INSERT INTO $table_name ($keys) VALUES ($values)";
    $result = mysqli_query($db_connection, $query);
    return $result;
}

function GetRowByColumnValue($db_connection, $table_name, $column_name, $column_value){
    $array_temp = array();

    $values = "";
    if (is_array($column_value)){
        foreach ($column_value as $value) {
            $values .= "'$value',";
        }
        $values = rtrim($values, ",");
    }
    else {
        $values = "'$column_value'";
    }

    $result = mysqli_query($db_connection, "SELECT * from $table_name WHERE `$column_name` IN ($values)");
    while ($row = mysqli_fetch_assoc($result)){
        array_push($array_temp, $row);
    }

    return $array_temp;
}

function GetRowByColumnValueDesc($db_connection, $table_name, $column_name, $column_value, $count){
    $array_temp = array();

    $values = "";
    if (is_array($column_value)){
        foreach ($column_value as $value) {
            $values .= "'$value',";
        }
        $values = rtrim($values, ",");
    }
    else {
        $values = "'$column_value'";
    }

    $id_name = mysqli_fetch_assoc(mysqli_query($db_connection, "SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'"))["Column_name"];

    $result = mysqli_query($db_connection, "SELECT * from $table_name WHERE `$column_name` IN ($values) ORDER BY $id_name DESC LIMIT $count" );
    while ($row = mysqli_fetch_assoc($result)){
        array_push($array_temp, $row);
    }

    return $array_temp;
}

function GetTableByName($db_connection, $table_name){
    $array_temp = array();
    $result = mysqli_query($db_connection, "SELECT * from $table_name");
    while ($row = mysqli_fetch_assoc($result)){
        array_push($array_temp, $row);
    }
    return $array_temp;
}

function IsColumnValueExist($db_connection, $table_name, $column_name, $column_value){
    $result = mysqli_query($db_connection, "SELECT * from $table_name WHERE `$column_name`=$column_value");
    $row = mysqli_fetch_assoc($result);

    return !is_null($row);
}

function GetMaxValueOfColumn($db_connection, $table_name, $column_name){
    $result = mysqli_query($db_connection, "SELECT MAX($column_name) AS quantity FROM $table_name");
    $row = mysqli_fetch_assoc($result);
    $quantity = $row['quantity'];
    return $quantity;
}

