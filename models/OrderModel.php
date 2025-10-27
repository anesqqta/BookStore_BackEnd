<?php
class OrderModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($data) {
        $stmt = $this->conn->prepare("
            INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "issssssds",
            $data['user_id'],
            $data['name'],
            $data['number'],
            $data['email'],
            $data['method'],
            $data['address'],
            $data['total_products'],
            $data['total_price'],
            $data['placed_on']
        );

        if ($stmt->execute()) {
            return true;
        } else {
            return "Помилка при створенні замовлення: " . $stmt->error;
        }
    }
    public function fetchOrdersByUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

        // Отримати всі замовлення
    public function getAllOrders() {
        $query = "SELECT * FROM orders ORDER BY placed_on DESC";
        return mysqli_query($this->conn, $query);
    }

    // Оновити статус оплати
    public function updatePaymentStatus($order_id, $status) {
        $query = "UPDATE orders SET payment_status = '$status' WHERE id = '$order_id'";
        return mysqli_query($this->conn, $query);
    }

    // Видалити замовлення
    public function deleteOrder($order_id) {
        $query = "DELETE FROM orders WHERE id = '$order_id'";
        return mysqli_query($this->conn, $query);
    }
}
?>