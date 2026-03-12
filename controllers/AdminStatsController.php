<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/AdminStatsModel.php';

class AdminStatsController {

    private $model;

    public function __construct(){

        $db = new Database();
        $conn = $db->getConnection();

        $this->model = new AdminStatsModel($conn);
    }

    private function parseOrderItems($totalProducts, $productsMap){

        $result = [];

        foreach ($productsMap as $productName => $productData) {

            $escaped = preg_quote($productName, '/');

            $patterns = [
                '/'.$escaped.'\s*\((\d+)\)/ui',
                '/'.$escaped.'\s*x\s*(\d+)/ui',
                '/'.$escaped.'\s*-\s*(\d+)/ui',
                '/'.$escaped.'.*?qty[: ](\d+)/ui',
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $totalProducts, $matches)) {

                    $qty = (int)$matches[1];

                    if ($qty > 0) {
                        $result[] = [
                            'name'=>$productName,
                            'genre'=>$productData['genre'],
                            'qty'=>$qty,
                            'price'=>(float)$productData['price']
                        ];
                    }

                    break;
                }
            }
        }

        return $result;
    }

    public function getStats(){

        $start_date = $_GET['start_date'] ?? '';
        $end_date = $_GET['end_date'] ?? '';
        $genre_filter = $_GET['genre'] ?? '';
        $search = trim($_GET['search'] ?? '');
        $payment_status = $_GET['payment_status'] ?? '';

        $products = $this->model->getProducts();
        $genres = $this->model->getGenres();
        $orders = $this->model->getOrders($payment_status);

        $latestOrders = [];

        $totalRevenue = 0;
        $totalOrders = 0;
        $totalUnitsSold = 0;

        $dailyRevenue = [];
        $dailyOrders = [];

        $bookSales = [];
        $genreSales = [];

        while ($order = mysqli_fetch_assoc($orders)) {

            $orderDateRaw = $order['placed_on'] ?? '';
            $orderTimestamp = strtotime($orderDateRaw);

            if (!$orderTimestamp) continue;

            $orderDate = date('Y-m-d', $orderTimestamp);

            if ($start_date && $orderDate < $start_date) continue;
            if ($end_date && $orderDate > $end_date) continue;

            $matchedItems = $this->parseOrderItems($order['total_products'],$products);

            $orderMatchesFilters = true;

            if ($genre_filter !== '' || $search !== '') {

                $orderMatchesFilters = false;

                foreach ($matchedItems as $item) {

                    $okGenre = ($genre_filter === '' || mb_strtolower($item['genre']) === mb_strtolower($genre_filter));
                    $okSearch = ($search === '' || mb_stripos($item['name'],$search) !== false);

                    if ($okGenre && $okSearch){
                        $orderMatchesFilters = true;
                        break;
                    }
                }
            }

            if (!$orderMatchesFilters) continue;

            $latestOrders[] = $order;

            $totalOrders++;

            $orderRevenue = (float)$order['total_price'];
            $totalRevenue += $orderRevenue;

            $dailyRevenue[$orderDate] = ($dailyRevenue[$orderDate] ?? 0) + $orderRevenue;
            $dailyOrders[$orderDate] = ($dailyOrders[$orderDate] ?? 0) + 1;

            foreach ($matchedItems as $item){

                $qty = (int)$item['qty'];
                $totalUnitsSold += $qty;

                $bookSales[$item['name']] = ($bookSales[$item['name']] ?? 0) + $qty;
                $genreSales[$item['genre']] = ($genreSales[$item['genre']] ?? 0) + $qty;
            }
        }

        arsort($bookSales);
        $topBooks = array_slice($bookSales,0,5,true);

        $leastBooks = $bookSales;
        asort($leastBooks);
        $leastBooks = array_slice($leastBooks,0,5,true);

        arsort($genreSales);
        $topGenres = array_slice($genreSales,0,5,true);

        $leastGenres = $genreSales;
        asort($leastGenres);
        $leastGenres = array_slice($leastGenres,0,5,true);

        ksort($dailyRevenue);
        ksort($dailyOrders);

        $latestOrders = array_slice($latestOrders,0,15);

        return compact(
            'products','genres','latestOrders',
            'totalRevenue','totalOrders','totalUnitsSold',
            'dailyRevenue','dailyOrders',
            'topBooks','leastBooks','topGenres','leastGenres',
            'start_date','end_date','genre_filter','search','payment_status'
        );
    }
}