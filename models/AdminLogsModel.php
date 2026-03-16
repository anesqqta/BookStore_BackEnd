<?php

class AdminLogsModel {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUsers() {
        $users = [];

        $query = mysqli_query($this->conn, "SELECT id, name FROM users ORDER BY name ASC");

        while ($row = mysqli_fetch_assoc($query)) {
            $users[] = $row;
        }

        return $users;
    }

    public function getLogs($filters = []) {
        $search = trim($filters['search'] ?? '');
        $user = $filters['user'] ?? '';
        $table = trim($filters['table'] ?? '');
        $start_date = $filters['start_date'] ?? '';
        $end_date = $filters['end_date'] ?? '';

        $where = "1";

        if ($search !== '') {
            $safe_search = mysqli_real_escape_string($this->conn, $search);
            $where .= " AND l.action LIKE '%{$safe_search}%'";
        }

        if ($user !== '') {
            $safe_user = (int)$user;
            $where .= " AND l.user_id = {$safe_user}";
        }

        if ($table !== '') {
            $safe_table = mysqli_real_escape_string($this->conn, $table);
            $where .= " AND l.table_name LIKE '%{$safe_table}%'";
        }

        if ($start_date !== '') {
            $safe_start = mysqli_real_escape_string($this->conn, $start_date);
            $where .= " AND DATE(l.created_at) >= '{$safe_start}'";
        }

        if ($end_date !== '') {
            $safe_end = mysqli_real_escape_string($this->conn, $end_date);
            $where .= " AND DATE(l.created_at) <= '{$safe_end}'";
        }

        $sql = "
            SELECT l.*, u.name AS user_name
            FROM logs l
            LEFT JOIN users u ON u.id = l.user_id
            WHERE {$where}
            ORDER BY l.created_at DESC
        ";

        return mysqli_query($this->conn, $sql);
    }
}