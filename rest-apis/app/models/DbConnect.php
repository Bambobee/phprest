<?php
require __DIR__ . '/../../config/database.php';

class DbConnect {
    private $server;
    private $dbname;
    private $user;
    private $pass;

    public function __construct() {
        // Load database configuration
        $config = include __DIR__ . '/../../config/database.php';
        $this->server = $config['host'];
        $this->dbname = $config['dbname'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
    }

    public function connect() {
        try {
            $conn = new PDO(
                "mysql:host={$this->server};dbname={$this->dbname}",
                $this->user,
                $this->pass
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $conn->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (\Exception $e) {
            echo "Database Error: " . $e->getMessage();
            die();
        }
    }
}
?>