<?php
class WishlistModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Отримати кількість товарів у списку бажаного
    public function getWishlistCount($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }
}