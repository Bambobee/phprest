<?php

class token{
    private $id;
    private $token;
    private $expires;
    private $tableName = 'refresh_token';
    private $dbConn;

    function setId($id){
        $this->id = $id;
    }
    function getId(){
        return $this->id;
    }
    function setToken($token){
        $this->token = $token;
    }
    function getexpires($expires){
        return $this->expires = $expires;
    }

    public function getRefreshToken(){
        $stmt = $this->dbconn->prepare('SELECT * FROM ' . $this->tableName);
        $stmt->execute();
        $token = $stmt->FETCH(PDO::FETCH_ASSOC);
        return $token;
    }
}