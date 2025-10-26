<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/CartModel.php';

class CartController {
    private $conn;
    private $cartModel;

    public function __construct() {
        // Підключення до бази
        $database = new Database();
        $this->conn = $database->getConnection();

        // Ініціалізація моделі кошика
        $this->cartModel = new CartModel();
    }

    // Порахувати кількість товарів у кошику користувача
    public function getCartCount($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    // Додати товар до кошика
    public function addToCart($user_id, $book) {
        return $this->cartModel->addToCart($user_id, $book);
    }

    public function getUserCart($user_id) {
    $stmt = $this->conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result();
}

public function clearCart($user_id) {
    $stmt = $this->conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}
}
?>