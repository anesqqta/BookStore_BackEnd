<?php
class MessageModel {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getAllMessages() {
        $query = "SELECT * FROM message ORDER BY id DESC";
        return mysqli_query($this->conn, $query);
    }
    public function deleteMessage($id) {
        $query = "DELETE FROM message WHERE id = '$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>