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

if (strtoupper($_POST['answer']) != "TERMINAL") {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage2")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 3", "
        Here's the next challenge: https://transfer.sh/P03jR/question3.pdf

        Submit your solution here: compsoc.crablab.co/stage3/?id=" . $_POST['hash'] . "
        ");

    header("Location: /stage3/?id=" . $_POST['hash']);

} else {
    http401();
}


?>