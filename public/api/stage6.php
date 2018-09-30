<?php

$db = new database();
$database = $db->connect();

if(!$_POST['hash'] || !$_POST['sid'] || !$_POST['answer']){
    http400();
}

//lookup the hash
$id = $db->hashLookup($_POST['hash']);

if(!$id){
    http401();
}

if ($_POST['answer'] != md5($_POST['hash'])) {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage6")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 7", "
        Here's the next challenge: https://transfer.sh/hHzEh/question7.pdf

        Submit your solution here: compsoc.crablab.co/stage7/?id=" . $_POST['hash'] . "
        ");

   header("Location: /stage7/?id=" . $_POST['hash']);

} else {
    http401();
}


?>