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

        $check_cart = mysqli_query($this->conn, "SELECT * FROM cart WHERE name = '$book_name' AND user_id = '$user_id'");
        if (mysqli_num_rows($check_cart) > 0) {
            return "Товар вже додано до кошика!";
        }

        mysqli_query($this->conn, "INSERT INTO cart(user_id, book_id, name, price, quantity, image) 
            VALUES('$user_id', '$book_id', '$book_name', '$book_price', '$book_quantity', '$book_image')")
            or die('Помилка при додаванні до кошика!');
        return "Товар додано до кошика!";
    }
}
?>