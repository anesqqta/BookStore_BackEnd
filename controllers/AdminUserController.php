<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/UserModel.php';
class AdminUserController {
    private $conn;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->userModel = new UserModel($this->conn);
    }
    public function getUsers() {
        return $this->userModel->getAllUsers();
    }
    public function deleteUser($id) {
        return $this->userModel->deleteUser($id);
    }
}
?>