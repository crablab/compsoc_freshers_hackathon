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

if (strtoupper($_POST['answer']) != strtoupper("oeu25snotx1")) {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage7")){
    // //send an email
    sendMail($db->getEmail($id), "Challenge 8", "
        Here's the next challenge: 

        Submit your solution here: compsoc.crablab.co/stage8/?id=" . $_POST['hash'] . "
        ");
    header("Location: /stage8/?id=" . $_POST['hash']);

} else {
    
    http401();
}


?>