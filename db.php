<?php

$servername = 'localhost';
$username = 'root';
$password = '1357';
$db_name = 'my_database';

try {
$db = new PDO("mysql:host=".$servername.";dbname=".$db_name, $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//echo"DB 연결 성공";
} catch (PDOException $e) {
    echo "Connection failed: ". $e->getMessage();
}

?>