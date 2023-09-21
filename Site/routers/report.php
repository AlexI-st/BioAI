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
                
                $report_data = new stdClass();
                $report_data->cube_id = $cube_id;
                if (isset($requestData->parameters["temperature"])) $report_data->temperature = $requestData->parameters["temperature"];
                if (isset($requestData->parameters["humidity_air"])) $report_data->humidity_air = $requestData->parameters["humidity_air"];
                if (isset($requestData->parameters["moisture_soil"])) $report_data->moisture_soil = $requestData->parameters["moisture_soil"];
                if (isset($requestData->parameters["ph"])) $report_data->ph = $requestData->parameters["ph"];
                if (isset($requestData->parameters["light_status"])) $report_data->light_status = $requestData->parameters["light_status"];
                if (isset($requestData->parameters["cooler_status"])) $report_data->cooler_status = $requestData->parameters["cooler_status"];
                if (isset($requestData->parameters["water_volume"])) $report_data->water_volume = $requestData->parameters["water_volume"];
                if (isset($requestData->parameters["last_watering_datetime"])) $report_data->last_watering_datetime = $requestData->parameters["last_watering_datetime"];

                InsertRow($db_connection, "reports", $report_data);
                $query = mysqli_query($db_connection, 
                "UPDATE cubes 
                SET `last_report_datetime`=now() 
                WHERE `cube_id`='$cube_id'");
            }
            else if ($requestData->parameters["role"] == "user"){
                $cube_id = $requestData->parameters["cube_id"];
                $reports = array();

                if (!IsColumnValueExist($db_connection, "cubes", "cube_id", $cube_id)){
                    echo "Cube not found";
                    return;
                }
                else if (!IsColumnValueExist($db_connection, "reports", "cube_id", $cube_id)) {
                    echo "Reports weren't found";
                    return;
                }

                if (isset($requestData->parameters["mode"])){
                    $mode = strtolower($requestData->parameters["mode"]);
                    if ($mode == "desc"){
                        $count = isset($requestData->parameters["count"]) ? $requestData->parameters["count"] : 1;

                        $reports = GetRowByColumnValueDesc($db_connection, "reports", "cube_id", $cube_id, $count);
                    }
                }
                else{
                    $reports = GetRowByColumnValue($db_connection, "reports", "cube_id", $cube_id);
                }

                echo json_encode($reports);
            }
            break;
        case "POST":
            
            break;
        default:
            break;
    }
}