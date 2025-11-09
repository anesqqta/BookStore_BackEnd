<?php
require_once __DIR__ . '/../models/ContactModel.php';

class ContactController {
    private $model;
    public function __construct() {
        $this->model = new ContactModel();
    }
    public function sendMessage($user_id, $formData) {
        $name = trim($formData['name']);
        $email = trim($formData['email']);
        $number = trim($formData['number']);
        $msg = trim($formData['message']);
        if (empty($name) || empty($email) || empty($number) || empty($msg)) {
            return "Будь ласка, заповніть усі поля!";
        }
        return $this->model->saveMessage($user_id, $name, $email, $number, $msg);
    }
}