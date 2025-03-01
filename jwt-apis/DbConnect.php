<?php

class DbConnect{
    private $server = 'localhost';
    private $dbname = 'jwtapi';
    private $user   = 'root';
    private $pass   = '';


    public function connect(){
        try{
            $conn = new PDO('mysql:host=' . $this->server . ';dbname=' . $this->dbname, $this->user, $this->pass);
            $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        }catch (\Exception $e){
            echo "Database Error: " . $e->getMessage();
            die(); 
        }
       
    }
}
define('APP_NAME', 'PHP JWT REST API TUTORIAL');
$db = new DbConnect();
$db->connect();

?>