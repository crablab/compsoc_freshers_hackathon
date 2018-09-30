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

if (strtoupper($_POST['answer']) != "SHAKESPEARE") {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage4")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 5", "
        Here's the next challenge: https://transfer.sh/nuj8j/question5.pdf

        Submit your solution here: compsoc.crablab.co/stage5/?id=" . $_POST['hash'] . "
        ");

    header("Location: /stage5/?id=" . $_POST['hash']);

} else {
    http401();
}


?>