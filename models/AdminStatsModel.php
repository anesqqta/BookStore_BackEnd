<?php

class AdminStatsModel {

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function getProducts(){
        $products = [];

        $query = mysqli_query($this->conn,
            "SELECT id, name, genre, price FROM products ORDER BY name ASC"
        );

        while($row = mysqli_fetch_assoc($query)){
            $products[$row['name']] = $row;
        }

        return $products;
    }

    public function getGenres(){
        $genres = [];

        $query = mysqli_query($this->conn,
            "SELECT DISTINCT genre FROM products WHERE genre <> '' ORDER BY genre ASC"
        );

        while($row = mysqli_fetch_assoc($query)){
            $genres[] = $row['genre'];
        }

        return $genres;
    }

    public function getOrders($payment_status){

        $sql = "SELECT * FROM orders WHERE 1";

        if($payment_status !== ''){
            $safe = mysqli_real_escape_string($this->conn,$payment_status);
            $sql .= " AND payment_status='{$safe}'";
        }

        $sql .= " ORDER BY id DESC";

        return mysqli_query($this->conn,$sql);
    }

}