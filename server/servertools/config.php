<?php

class database {
    protected $db; 

    public function connect(){
        $username = "compsoc";
        $password = "xcRdeczyEmank9Z6";

        $host='192.168.1.65';
        $db = 'compsoc';
        $dsn = "mysql:host=$host;dbname=$db";

        $this->db = new PDO($dsn, $username, $password, array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => true, 
            PDO::ERRMODE_EXCEPTION => true
        ));

        return $this->db;
    }

    public function hashLookup($hash){
        if(!$this->db){
            throw new exception("Missing DB instance");
        }

        $query = $this->db->prepare("SELECT * FROM `users` WHERE `hash` = :hs");
        $query->bindParam(":hs", $hash);
        $query->execute();

        if($query->rowCount() != 1){
            return false; 
        } else {
            return $query->fetch(PDO::FETCH_ASSOC)['id'];
        }
    }

    public function getEmail($id){
        $query = $this->db->prepare("SELECT * FROM `users` WHERE `id` = :id");
        $query->bindParam(":id", $id);
        $query->execute();

        if($query->rowCount() != 1){
            return false; 
        } else {
            return $query->fetch(PDO::FETCH_ASSOC)['email'];
        }
    }

    public function startStage($stage, $id){
        //check we don't have a stage already 
        $query = $this->db->prepare("SELECT * FROM `stages` WHERE `uid` = :uid AND `stage` = :stg");
        $query->bindParam(":uid", $id);
        $query->bindParam(":stg", $stage);
        $query->execute();

        if($query->rowCount() != 0){
            return [false, $query->fetch(PDO::FETCH_ASSOC)['id']];
        }

        $sid = uniqid();
        $time = time();

        $query = $this->db->prepare("INSERT INTO `stages` (`id`, `uid`, `stage`, `started`, `completed`) VALUES (:id, :uid, :stg, :st, null)");
        $query->bindParam(":id", $sid);
        $query->bindParam(":uid", $id);
        $query->bindParam(":stg", $stage);
        $query->bindParam(":st", $time);
        $query->execute();

        return [true, $stage, $sid];
    }

    public function endStage($sid, $uid, $stage){
        //check we don't have a completedstage already 
        if($this->stageCompleted($uid, $stage)){
            return false;
        }

        $time = time();
        $query = $this->db->prepare("UPDATE `stages` SET `completed` = :cp WHERE `id` = :sid AND `completed` IS NULL");
        $query->bindParam(":sid", $sid);
        $query->bindParam(":cp", $time);
        $query->execute();

        return true;
    }

    public function currentStage($uid){
        $query = $this->db->prepare("SELECT * FROM `stages` WHERE `uid` = :uid AND `started` IS NOT NULL AND `completed` IS NULL");
        $query->bindParam(":uid", $uid);
        $query->execute();

        if($query->rowCount() == 1){
            $stage = $query->fetch(PDO::FETCH_ASSOC);
            return [true, $stage['stage'], $stage['id']];
        } elseif ($query->rowCount() == 0) {
            return [false, null];
        } else {
            throw new exception ("In multiple stages");
        }
    }

    public function stageCompleted($uid, $stage){
        $query = $this->db->prepare("SELECT * FROM `stages` WHERE `uid` = :uid AND `stage` = :stg AND `started` IS NOT NULL AND `completed` IS NOT NULL");
        $query->bindParam(":uid", $id);
        $query->bindParam(":stg", $stage);
        $query->execute();

        if($query->rowCount() == 1){
            return true;
        } elseif ($query->rowCount() == 0) {
            return false;
        } else {
            throw new exception ("Stage completed multiple times");
        }
    }
}

function sendMail($email, $subject, $body){
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/crablab.co/messages");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_USERPWD, "api" . ":" . "key-3fbbb2b52f6f43c78cb8533b0e16c27f");

    curl_setopt($ch, CURLOPT_POSTFIELDS,
            "from=" . urlencode("compsoc@crablab.co") . "&to=" . urlencode($email) . "&subject=" . urlencode($subject) . "&text=" . urlencode($body));

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    var_dump($result);
    curl_close ($ch);
}


?>