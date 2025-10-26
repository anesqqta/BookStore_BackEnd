<?php
class Database {
    private $host = "localhost"; 
    private $user = "root";      
    private $pass = "";          
    private $dbname = "bookstore_db"; 
    public $conn;

    public function __construct() {
        $this->connectDatabase();
    }

    private function connectDatabase() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die("Помилка підключення до бази даних: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>