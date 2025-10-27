<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/AdminDashboardModel.php';

class AdminDashboardController {
    private $model;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->model = new AdminDashboardModel($conn);
    }

    public function getDashboardData() {
        return [
            'pendingTotal' => $this->model->getPendingTotal(),
            'completedTotal' => $this->model->getCompletedTotal(),
            'orders' => $this->model->countOrders(),
            'products' => $this->model->countProducts(),
            'users' => $this->model->countUsers(),
            'admins' => $this->model->countAdmins(),
            'accounts' => $this->model->countAccounts(),
            'messages' => $this->model->countMessages()
        ];
    }
}
?>