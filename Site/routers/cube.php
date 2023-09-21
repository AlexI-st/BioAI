<?php
include_once ABSPATH."lib/db_functions.php";

function route($method, $urlList, $requestData, $db_connection){
    switch ($method){
        case "GET":
            if ($requestData->parameters["role"] == "cube"){
                $cube_id = $requestData->parameters["cube_id"];
                $password = $requestData->parameters["password"];
                $valid_password = mysqli_fetch_assoc(mysqli_query($db_connection, 
                "SELECT password
                from cubes 
                WHERE `cube_id`='$cube_id'"))["password"];

                if (!IsColumnValueExist($db_connection, "cubes", "cube_id", $cube_id)){
                    echo "Cube not found";
                    return;
                }
                else if ($password != $valid_password){
                    echo "Invalid Password";
                    return;
                }

                $cube_data = mysqli_fetch_assoc(mysqli_query($db_connection, 
                "SELECT light_status, temperature, cooler_status, water_volume
                from cubes
                WHERE `cube_id`=$cube_id"));
                
                echo json_encode($cube_data);

                $query = mysqli_query($db_connection, 
                "UPDATE cubes SET 
                `water_volume`='0', 
                `last_watering_datetime`=now() 
                WHERE `cube_id`='$cube_id'");
            }
            else if ($requestData->parameters["role"] == "user"){
                $cube_id = $requestData->parameters["cube_id"];

                if (!IsColumnValueExist($db_connection, "cubes", "cube_id", $cube_id)){
                    echo "Cube not found";
                    return;
                }

                $cube_data = mysqli_fetch_assoc(mysqli_query($db_connection, 
                "SELECT name, planting_datetime, last_watering_datetime, last_update_datetime, last_report_datetime, description, light_status, temperature, cooler_status, water_volume 
                from cubes 
                WHERE `cube_id`='$cube_id'"));

                echo json_encode($cube_data);
            }
            break;
        case "PATCH":
            if ($requestData->parameters["role"] == "user"){
                $cube_id = $requestData->parameters["cube_id"];

                if (!IsColumnValueExist($db_connection, "cubes", "cube_id", $cube_id)){
                    echo "Cube not found";
                    return;
                }

                if (isset($requestData->parameters["column_name"]) && isset($requestData->parameters["column_value"])){
                    $col_name = $requestData->parameters["column_name"];
                    $col_value = $requestData->parameters["column_value"];

                    echo json_encode($requestData);
                    $query = mysqli_query($db_connection, 
                    "UPDATE cubes SET 
                    `$col_name`='$col_value',
                    `last_update_datetime`=now()
                    WHERE `cube_id`='$cube_id'");
                }
                else {
                    $light_status = $requestData->parameters["light_status"];
                    $temperature = $requestData->parameters["temperature"];
                    $cooler_status = $requestData->parameters["cooler_status"];
                    $water_volume = $requestData->parameters["water_volume"];

                    $query = mysqli_query($db_connection, 
                    "UPDATE cubes SET 
                    `last_update_datetime`=now(),
                    `temperature`='$temperature', 
                    `cooler_status`='$cooler_status', 
                    `water_volume`='$water_volume', 
                    `light_status`='$light_status'
                    WHERE `cube_id`='$cube_id'");
                }
            }
            break;
        default:
            break;
    }
}