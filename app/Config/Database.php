<?php
class Database {
    private $host = "db";
    private $user = "root";
    private $pass = "rootpass";      
    private $dbname = "minimart";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname.";charset=utf8", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Lỗi kết nối DB: " . $e->getMessage());
        }
    }
}
?>