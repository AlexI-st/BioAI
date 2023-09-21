<?php
include "db_connection.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

$indexFileLocation = "pages/index.html";
$publisherIndexFileLocation = "publisher/index.html";

function getData(){
    $data = new stdClass();

    $data->body = json_decode(file_get_contents('php://input'));

    $data->parameters = [];
    foreach($_GET as $key => $value) {
        if ($key != "q"){
            $data->parameters[$key] = $value;
        }
    }

    return $data;
}

function getMethod(){
    return $_SERVER["REQUEST_METHOD"];
}

function getPath(){
    $path = "";
    $requestData = getData(getMethod());

    if (isset($_GET["q"])){
        $path = $_GET["q"];
    }
    else if (isset($requestData->parameters["path"])){
        $path = $requestData->parameters["path"];
    }
    else if (isset($requestData->body->path)) {
        $path = $requestData->body->path;
    }

    return $path;
}

$requestData = getData();

$path = getPath();
$path = rtrim($path, "/");
$urlList = explode("/", $path);
$domain = $urlList[0];

if (file_exists(ABSPATH."routers/".$domain.".php")){
    include_once "routers/".$domain.".php";
    route(getMethod(), $urlList, $requestData, $db_connection);
    exit();
}
else if ($domain == "publisher" && count($urlList) == 1){
    header("Location: ".$publisherIndexFileLocation);
    exit();
}
else if ($path == NULL) {
    header("Location: ".$indexFileLocation);
    exit();
}
else if (count($urlList) == 1) {
    echo "Bad Request";
    exit();
}