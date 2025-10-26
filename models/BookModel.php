<?php
class BookModel {
    private $conn;
    private $table = "products"; // таблиця у базі даних

    public function __construct($db) {
        $this->conn = $db;
    }

    public function fetchLatestBooks($limit) {
        $query = "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}
?>