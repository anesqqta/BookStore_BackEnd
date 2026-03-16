<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/AdminLogsModel.php';

class AdminLogsController {

    private $model;

    public function __construct() {
        $db = new Database();
        $conn = $db->getConnection();

        if (!$conn) {
            die('Помилка підключення до бази даних');
        }

        $this->model = new AdminLogsModel($conn);
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $user = $_GET['user'] ?? '';
        $table = $_GET['table'] ?? '';
        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';

        $filters = [
            'search' => $search,
            'user' => $user,
            'table' => $table,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $users = $this->model->getUsers();
        $logsResult = $this->model->getLogs($filters);

        $logs = [];
        while ($row = mysqli_fetch_assoc($logsResult)) {
            $logs[] = $row;
        }

        return [
            'users' => $users,
            'logs' => $logs,
            'search' => $search,
            'user' => $user,
            'table' => $table,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
    }
}