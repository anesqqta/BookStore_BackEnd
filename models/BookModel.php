<?php
class BookModel {
    private $conn;
    private $table = "products"; 
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
    public function getFilteredBooks($filters = []) {
        $query = "SELECT * FROM products";
        $conditions = [];
        if (!empty($filters['genre'])) {
            $genre = mysqli_real_escape_string($this->conn, $filters['genre']);
            $conditions[] = "genre = '$genre'";
        }
        if (!empty($filters['year'])) {
            $year = (int)$filters['year'];
            $conditions[] = "year_published = $year";
        }
        if (!empty($filters['price_min'])) {
            $min = (float)$filters['price_min'];
            $conditions[] = "price >= $min";
        }
        if (!empty($filters['price_max'])) {
            $max = (float)$filters['price_max'];
            $conditions[] = "price <= $max";
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
            $query .= " ORDER BY id DESC";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
    public function getBookById($book_id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
