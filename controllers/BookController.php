<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/BookModel.php';

class BookController {
    private $bookModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->bookModel = new BookModel($db);
    }

    public function getLatestBooks($limit = 6) {
        return $this->bookModel->fetchLatestBooks($limit);
    }


    public function getBooks($filters = []) {
        return $this->bookModel->getFilteredBooks($filters);
    }


    public function getBookById($book_id) {
        return $this->bookModel->getBookById($book_id);
    }
}
?>
