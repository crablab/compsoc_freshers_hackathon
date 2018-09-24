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
}

?>