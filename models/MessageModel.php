<?php
class MessageModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Отримати всі повідомлення
    public function getAllMessages() {
        $query = "SELECT * FROM message ORDER BY id DESC";
        return mysqli_query($this->conn, $query);
    }

    // Видалити повідомлення
    public function deleteMessage($id) {
        $query = "DELETE FROM message WHERE id = '$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>