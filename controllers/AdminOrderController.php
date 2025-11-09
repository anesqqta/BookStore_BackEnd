<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/OrderModel.php';
class AdminOrderController {
    private $conn;
    private $orderModel;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->orderModel = new OrderModel($this->conn);
    }
    public function getOrders() {
        return $this->orderModel->getAllOrders();
    }
    public function updateOrderStatus($order_id, $status) {
        return $this->orderModel->updatePaymentStatus($order_id, $status);
    }
    public function deleteOrder($order_id) {
        return $this->orderModel->deleteOrder($order_id);
    }
}
?>