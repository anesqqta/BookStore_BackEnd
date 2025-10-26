<?php
class ProductModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function searchProducts($query) {
        $stmt = $this->conn->prepare("SELECT * FROM products WHERE name LIKE ?");
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>