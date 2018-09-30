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
    $leaders[$value['id']] = ["id" => $value['id']];
    $total = 0;
   
    foreach ($stages as $key1 => $value1) {
        if($value1['uid'] == $value['id']){
            //iterating through user stages
            if(!empty($value1['completed']) && !empty($value1['started'])){
                $diff = $value1['completed'] - $value1['started'];
                //part of hour in seconds rounded 
                $score = round((3600 - $diff)/100);

                if($score < 0){
                    $score = 0;
                }

                $leaders[$value['id']][$value1['stage']] = $score;
                $total = $score + $total; 
            } else {
                $leaders[$value['id']][$value1['stage']] = 0;
            }
        }
    }

    $leaders[$value['id']]['total'] = $total;

}

usort($leaders, function($a, $b) {
    return $a['total'] <=> $b['total'];
});

$leaders = array_reverse($leaders);

echo json_encode($leaders);

?>