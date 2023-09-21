<?php
    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "spacepot";

    $db_connection = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

    if (!$db_connection){
        die("Connection Failed: " . mysqli_connect_error());
    }
?>