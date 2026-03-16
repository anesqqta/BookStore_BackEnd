<?php

function logAction($conn, $user_id, $action, $table_name = null, $description = null) {
    $user_id = $user_id !== null ? (int)$user_id : 'NULL';
    $action = mysqli_real_escape_string($conn, $action);
    $table_name = $table_name ? "'" . mysqli_real_escape_string($conn, $table_name) . "'" : "NULL";
    $description = $description ? "'" . mysqli_real_escape_string($conn, $description) . "'" : "NULL";

    $sql = "
        INSERT INTO logs (user_id, action, table_name, description, created_at)
        VALUES ($user_id, '$action', $table_name, $description, NOW())
    ";

    mysqli_query($conn, $sql);
}