<?php
class Token {
    private $id;
    private $token;
    private $expires;
    private $tableName = 'refresh_token';
    private $dbConn;

    public function __construct() {
        $db = new DbConnect;
        $this->dbConn = $db->connect();
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function getToken() {
        return $this->token;
    }

    public function setExpires($expires) {
        $this->expires = $expires;
    }

    public function getExpires() {
        return $this->expires;
    }

    public function storeRefreshToken($userId, $token, $expires) {
        $stmt = $this->dbConn->prepare("INSERT INTO $this->tableName (user_id, token, expires) VALUES (:user_id, :token, :expires)");
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":expires", $expires);
        return $stmt->execute();
    }

    public function getRefreshToken($token) {
        $stmt = $this->dbConn->prepare("SELECT * FROM $this->tableName WHERE token = :token");
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}