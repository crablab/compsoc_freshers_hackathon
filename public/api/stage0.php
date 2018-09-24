<?php

if(!$_POST['email']){
    http400();
}

$db = new database();
$db = $db->connect();

$id = uniqid();
$time = time();
$hash = sha1($id . $time . $_POST['email']);

$query = $db->prepare("INSERT INTO `users` (`id`, `email`, `timestamp`, `hash`) VALUES (:id, :em, :ts, :hs)");
$query->bindParam(":id", $id);
$query->bindParam(":em", $_POST['email']);
$query->bindParam(":ts", $time);
$query->bindParam(":hs", $hash);
$query->execute();

echo "ID: " . $hash;

//send an email


header("Location: /stage1/?id=" . $hash);
?>