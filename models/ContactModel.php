<?php
require_once __DIR__ . '/../config/Database.php';

class ContactModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Зберегти повідомлення користувача
    public function saveMessage($user_id, $name, $email, $number, $msg) {
        $stmt = $this->conn->prepare("SELECT * FROM message WHERE name = ? AND email = ? AND number = ? AND message = ?");
        $stmt->bind_param("ssss", $name, $email, $number, $msg);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Повідомлення вже надіслано!";
        }

        $insert = $this->conn->prepare("INSERT INTO message(user_id, name, email, number, message) VALUES(?, ?, ?, ?, ?)");
        $insert->bind_param("issss", $user_id, $name, $email, $number, $msg);
        $insert->execute();

        return "Повідомлення успішно надіслано!";
    }
}