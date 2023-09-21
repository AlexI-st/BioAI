<?php
include_once ABSPATH."lib/db_functions.php";

function route($method, $urlList, $requestData, $db_connection){
    switch ($method){ 
        case "GET":
            if ($requestData->parameters["role"] == "cube"){
                
            }
            else if ($requestData->parameters["role"] == "user"){
                $cube_id = $requestData->parameters["cube_id"];

                if (!IsColumnValueExist($db_connection, "cubes", "cube_id", $cube_id)){
                    echo "Cube not found";
                    return;
                }
                else if (!IsColumnValueExist($db_connection, "cameras", "cube_id", $cube_id)) {
                    echo "Cameras weren't found";
                    return;
                }

                $cameras = GetRowByColumnValue($db_connection, "cameras", "cube_id", $cube_id);

                echo json_encode($cameras);
            }
            break;
        case "POST":
            
            break;
        default:
            break;
    }
}