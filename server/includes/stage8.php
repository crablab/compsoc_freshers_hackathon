<?php

$db = new database();
$database = $db->connect();

if(!$_GET['id']){
    http404();
} 

//lookup the hash
$id = $db->hashLookup($_GET['id']);
//check we haven't done this stage already
$stcheck = $db->stageCompleted($id, "stage8");


if(!$id || $stcheck){
    http401();
}

//check we haven't started the stage before 
$st_start_check = $db->currentStage($id);

if($st_start_check[1] == "stage8"){
    $stage = $st_start_check;
} else {
    //start the stage
    $stage = $db->startStage("stage8", $id);
}


echo "<!-- \n" .  shell_exec("toilet '" . $id . "'") . "\n -->";
?>

<h1>Stage 8 Answer</h1>

<form action="/api/stage8" method="post">
    <label>Answer:</label>
    <input type="text" name="answer">
    <label>User ID:</label>
    <input type="text" name="hash" value="<?php echo $_GET['id']; ?>" readonly>
    <label>Stage ID:</label>
    <input type="text" name="sid" value="<?php echo $stage[2]; ?>" readonly>
    <input type="submit" value="Submit">
</form>
