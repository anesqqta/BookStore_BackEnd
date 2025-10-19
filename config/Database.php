<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'bookstore_db';
    private $username = 'root';
    private $password = '';    
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            // створюємо нове підключення через PDO
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );

            // увімкнути обробку помилок
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8mb4");

        } catch (PDOException $e) {
            echo 'Помилка підключення: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
?>