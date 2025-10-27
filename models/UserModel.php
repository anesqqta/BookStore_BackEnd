<?php
class UserModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserByEmailAndPassword($email, $password) {
        $email = mysqli_real_escape_string($this->conn, filter_var($email, FILTER_SANITIZE_STRING));
        $passHash = md5(filter_var($password, FILTER_SANITIZE_STRING));

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $passHash);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function checkUserExists($email) {
        $email = mysqli_real_escape_string($this->conn, filter_var($email, FILTER_SANITIZE_STRING));
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    public function createUser($name, $email, $password) {
        $name = mysqli_real_escape_string($this->conn, filter_var($name, FILTER_SANITIZE_STRING));
        $email = mysqli_real_escape_string($this->conn, filter_var($email, FILTER_SANITIZE_STRING));
        $passHash = md5(filter_var($password, FILTER_SANITIZE_STRING));

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param("sss", $name, $email, $passHash);
        return $stmt->execute();
    }

    public function getUserById($user_id) {
    $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

        // Отримати всіх користувачів
    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY id DESC";
        return mysqli_query($this->conn, $query);
    }

    // Видалити користувача
    public function deleteUser($id) {
        $query = "DELETE FROM users WHERE id = '$id'";
        return mysqli_query($this->conn, $query);
    }
}
?>