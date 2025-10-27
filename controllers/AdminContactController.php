<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/MessageModel.php';

class AdminContactController {
    private $conn;
    private $messageModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->messageModel = new MessageModel($this->conn);
    }

    public function getMessages() {
        return $this->messageModel->getAllMessages();
    }

    public function deleteMessage($id) {
        return $this->messageModel->deleteMessage($id);
    }
}
?>