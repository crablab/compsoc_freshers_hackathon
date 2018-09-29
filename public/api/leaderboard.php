<?php

header('Content-Type: application/json');

$db = new database();
$database = $db->connect();

$query = $database->prepare("SELECT * FROM `users`");
$query->execute();

$users = $query->fetchAll(PDO::FETCH_ASSOC);

$query = $database->prepare("SELECT * FROM `stages`");
$query->execute();

$stages = $query->fetchAll(PDO::FETCH_ASSOC);

$leaders = [];

foreach ($users as $key => $value) {
    $user_stages = [];
    foreach ($stages as $key1 => $value1) {
        if($value1['uid'] == $value['id']){
            array_push($user_stages, $value1);
        }
    }

    $last = $user_stages[count($user_stages) -1];

    if(empty($last['completed'])){
        $last = $user_stages[count($user_stages) -2];
    }

    array_push($leaders, [$value['hash'], $last['stage']]);
}

echo json_encode($leaders);

?>