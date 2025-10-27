<?php
class WishlistModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function getWishlistCount($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM wishlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }
    public function addToWishlist($user_id, $book) {
        $product_id = $book['product_id'];
        $name = mysqli_real_escape_string($this->conn, $book['product_name']);
        $price = $book['product_price'];
        $image = $book['product_image'];

        $check = $this->conn->prepare("SELECT * FROM wishlist WHERE book_id = ? AND user_id = ?");
        $check->bind_param("ii", $product_id, $user_id);
        $check->execute();
        $res = $check->get_result();
        if ($res->num_rows > 0) {
            return "Товар вже у списку бажаного";
        }
        $stmt = $this->conn->prepare("INSERT INTO wishlist (user_id, book_id, name, price, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $product_id, $name, $price, $image);
        if ($stmt->execute()) {
            return "Товар додано до списку бажаного";
        } else {
            return "Помилка при додаванні";
        }
    }
    public function getUserWishlist($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM wishlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }
    public function deleteFromWishlist($wishlist_id) {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE id = ?");
        $stmt->bind_param("i", $wishlist_id);
        return $stmt->execute();
    }
    public function clearWishlist($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM wishlist WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
}
?>