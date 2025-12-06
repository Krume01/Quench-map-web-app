<?php

$DB_HOST='localhost';
$DB_USER='root';
$DB_PASS='Password1*';
$DB_NAME='ip_proekt';
$DB_PORT='3306';

$conn=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);

if($conn->connect_error){
    die('Database connection error:'. $conn->connect_error);
}

$conn->set_charset('utf8mb4');

?>