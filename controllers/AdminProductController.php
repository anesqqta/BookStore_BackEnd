<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/ProductModel.php';

class AdminProductController {
    private $model;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();
        $this->model = new ProductModel($conn);
    }

    public function addProduct($postData, $fileData) {
        return $this->model->addProduct($postData, $fileData);
    }

    public function deleteProduct($id) {
        $this->model->deleteProduct($id);
    }

    public function getProducts() {
        return $this->model->getAllProducts();
    }

    public function getProductById($id) {
        return $this->model->getProductById($id);
    }

    public function updateProduct($data, $files) {
        return $this->model->updateProduct($data, $files);
    }
}
?>
