<?php

include 'db.php';

$id = $_POST['id'];
$pw = $_POST['pw'];


//$sql = "SELECT * FROM member WHERE user_id ='{$id}' AND passwd = '{$pw}'";

$sql = "SELECT * FROM member WHERE user_id =:user_id AND passwd =:pw";
$stmt = $db->prepare($sql);

$stmt->bindParam(':user_id', $id);
$stmt->bindParam(':pw', $pw);


$stmt->execute();
$row = $stmt->fetch();

var_dump($row);
?>