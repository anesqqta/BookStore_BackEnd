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
}