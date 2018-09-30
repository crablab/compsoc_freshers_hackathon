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

$sid = uniqid();
$stage = "stage0";

$query = $db->prepare("INSERT INTO `stages` (`id`, `uid`, `stage`, `started`, `completed`) VALUES (:id, :uid, :stg, :st, :cp)");
$query->bindParam(":id", $sid);
$query->bindParam(":uid", $id);
$query->bindParam(":stg", $stage);
$query->bindParam(":st", $time);
$query->bindParam(":cp", $time);
$query->execute();


echo "ID: " . $hash;

// //send an email
sendMail($_POST['email'], "Challenge 1", "
    Thanks for signing up!
    
    In case you forget your ID is " . $hash . ". 

    You can find instructions for the first challenge here: https://transfer.sh/15yoYl/question1.pdf

    Submit your solution here: compsoc.crablab.co/stage1/?id=" . $hash . "

    Good luck!
    ");

header("Location: /stage1/?id=" . $hash);

?>