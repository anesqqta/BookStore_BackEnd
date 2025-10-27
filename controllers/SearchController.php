<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ProductModel.php';

class SearchController {
    private $productModel;

    public function __construct() {
        $database = new Database();
        $conn = $database->getConnection();
        $this->productModel = new ProductModel($conn);
    }
    public function searchProducts($query) {
        return $this->productModel->searchProducts($query);
    }
}
?>