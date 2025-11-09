<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/WishlistModel.php';

class WishlistController {
    private $wishlistModel;
    public function __construct() {
        $database = new Database();
        $conn = $database->getConnection();
        $this->wishlistModel = new WishlistModel($conn);
    }
    public function getWishlistCount($user_id) {
        return $this->wishlistModel->getWishlistCount($user_id);
    }
     public function addToWishlist($user_id, $book) {
        return $this->wishlistModel->addToWishlist($user_id, $book);
    }
    public function getUserWishlist($user_id) {
        return $this->wishlistModel->getUserWishlist($user_id);
    }
    public function deleteFromWishlist($wishlist_id) {
        return $this->wishlistModel->deleteFromWishlist($wishlist_id);
    }
    public function clearWishlist($user_id) {
        return $this->wishlistModel->clearWishlist($user_id);
    }
}
    
