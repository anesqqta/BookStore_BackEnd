<?php
require_once __DIR__ . '/../config/Logging.php';

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

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            logAction(
                $this->conn,
                $user['id'],
                'login',
                'users',
                ($user['user_type'] === 'admin' ? 'Адміністратор' : 'Користувач') . ' увійшов у систему'
            );

            return $user;
        }

        return null;
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

        if ($stmt->execute()) {
            $newUserId = $this->conn->insert_id;

            logAction(
                $this->conn,
                $newUserId,
                'register',
                'users',
                'Зареєстровано нового користувача: ' . $name
            );

            return true;
        }

        return false;
    }

    public function getUserById($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
    }

    public function updateUser($id, $data) {
        $id = (int)$id;
        $name = mysqli_real_escape_string($this->conn, $data['name']);
        $email = mysqli_real_escape_string($this->conn, $data['email']);

        $stmt = $this->conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        $stmt->bind_param("ssi", $name, $email, $id);

        if ($stmt->execute()) {
            logAction(
                $this->conn,
                $id,
                'update_profile',
                'users',
                'Оновлено профіль користувача: ' . $name
            );

            return "Профіль успішно оновлено!";
        } else {
            return "Помилка при оновленні профілю!";
        }
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users ORDER BY id DESC";
        return mysqli_query($this->conn, $query);
    }

    public function deleteUser($id) {
        $id = (int)$id;

        $userRes = mysqli_query($this->conn, "SELECT name FROM users WHERE id = '$id'");
        $user = mysqli_fetch_assoc($userRes);

        $query = "DELETE FROM users WHERE id = '$id'";
        $deleted = mysqli_query($this->conn, $query);

        if ($deleted) {
            $adminId = $_SESSION['admin_id'] ?? null;

            logAction(
                $this->conn,
                $adminId,
                'delete_user',
                'users',
                'Видалено користувача: ' . ($user['name'] ?? ('ID ' . $id))
            );
        }

        return $deleted;
    }
}
?>