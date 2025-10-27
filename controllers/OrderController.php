<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../config/Database.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->orderModel = new OrderModel($db);
    }
    public function placeOrder($data) {
        return $this->orderModel->createOrder($data);
    }
    public function getUserOrders($user_id) {
        return $this->orderModel->fetchOrdersByUser($user_id);
    }
}
?>