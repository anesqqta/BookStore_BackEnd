<?php
class AdminDashboardModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getPendingTotal() {
        $total = 0;
        $result = $this->conn->query("SELECT total_price FROM orders WHERE payment_status = 'pending'");
        while ($row = $result->fetch_assoc()) {
            $total += $row['total_price'];
        }
        return $total;
    }
    public function getCompletedTotal() {
        $total = 0;
        $result = $this->conn->query("SELECT total_price FROM orders WHERE payment_status = 'completed'");
        while ($row = $result->fetch_assoc()) {
            $total += $row['total_price'];
        }
        return $total;
    }
    public function countOrders() {
        return $this->conn->query("SELECT id FROM orders")->num_rows;
    }
    public function countProducts() {
        return $this->conn->query("SELECT id FROM products")->num_rows;
    }
    public function countUsers() {
        return $this->conn->query("SELECT id FROM users WHERE user_type = 'user'")->num_rows;
    }
    public function countAdmins() {
        return $this->conn->query("SELECT id FROM users WHERE user_type = 'admin'")->num_rows;
    }
    public function countAccounts() {
        return $this->conn->query("SELECT id FROM users")->num_rows;
    }
    public function countMessages() {
        return $this->conn->query("SELECT id FROM message")->num_rows;
    }
}
?>