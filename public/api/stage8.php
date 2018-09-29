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

if ($_POST['answer'] != $id) {
    echo "Incorrect";
    exit();
}

if($db->endStage($_POST['sid'], $id, "stage8")){
    // //send an email
    sendMail($db->getEmail($id), "You're done here", "
        Let someone know you're done by giving them: ". $_POST['hash'] . ". 

        Now, make something cool with open data. Seperate prize ;-) 

        Info: https://theodi.org/article/what-is-open-data-and-why-should-we-care/

        ");

    header("Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");

} else {
    http401();
}


?>