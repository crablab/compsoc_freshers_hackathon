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

if (strtoupper($_POST['answer']) != "1101111") {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage1")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 2", "
        Here's the next challenge: 

        Submit your solution here: compsoc.crablab.co/stage2/?id=" . $_POST['hash'] . "
        ");

    header("Location: /stage2/?id=" . $_POST['hash']);

} else {
    http401();
}


?>