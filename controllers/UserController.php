<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../config/Database.php';

class UserController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new UserModel($db);
    }

    public function loginUser($email, $password) {
        $user = $this->userModel->getUserByEmailAndPassword($email, $password);

        if ($user) {
            return [
                'status' => 'success',
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'user_type' => $user['user_type']
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Невірний email або пароль!'
            ];
        }
    }

    public function registerUser($name, $email, $pass, $cpass) {
        if ($pass !== $cpass) {
            return ['status' => 'error', 'message' => 'Паролі не співпадають!'];
        }

        $exists = $this->userModel->checkUserExists($email);
        if ($exists) {
            return ['status' => 'error', 'message' => 'Користувач вже існує!'];
        }

        $result = $this->userModel->createUser($name, $email, $pass);
        if ($result) {
            return ['status' => 'success', 'message' => 'Реєстрація успішна!'];
        } else {
            return ['status' => 'error', 'message' => 'Помилка при реєстрації!'];
        }
    }
}
?>