<?php
require_once __DIR__ . '/../config/Database.php';

class CartModel {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Додати товар до кошика
    public function addToCart($user_id, $book) {
    $book_id = $book['product_id'];
    $book_name = mysqli_real_escape_string($this->conn, $book['product_name']);
    $book_price = $book['product_price'];
    $book_image = $book['product_image'];
    $book_quantity = $book['product_quantity'];

    $check_cart = $this->conn->prepare("SELECT * FROM cart WHERE book_id = ? AND user_id = ?");
    $check_cart->bind_param("ii", $book_id, $user_id);
    $check_cart->execute();
    $res = $check_cart->get_result();

    if ($res->num_rows > 0) {
        // Якщо товар вже є — просто збільшуємо кількість
        $update = $this->conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE book_id = ? AND user_id = ?");
        $update->bind_param("iii", $book_quantity, $book_id, $user_id);
        $update->execute();
        return "Кількість товару збільшено!";
    } else {
        $insert = $this->conn->prepare("INSERT INTO cart(user_id, book_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->bind_param("iisdss", $user_id, $book_id, $book_name, $book_price, $book_quantity, $book_image);
        $insert->execute();
        return "Товар додано до кошика!";
    }
}
}
?>