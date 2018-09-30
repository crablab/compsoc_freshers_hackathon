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

if (strtoupper($_POST['answer']) != "DEADBEEF") {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage5")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 6", "
        Here's the next challenge: https://transfer.sh/IZLGZ/question6.pdf

        Submit your solution here: compsoc.crablab.co/stage6/?id=" . $_POST['hash'] . "
        ");

    header("Location: /stage6/?id=" . $_POST['hash']);

} else {
    http401();
}


?>