<?php
// fetch_events.php

// Database connection
$host = 'localhost';
$db = 'ceylon_happens';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Fetch events
$sql = "SELECT product_name, event_date FROM product";
$stmt = $pdo->query($sql);

$events = [];
while ($row = $stmt->fetch()) {
    $events[] = [
        'title' => $row['product_name'],
        'start' => $row['event_date'],
    ];
}

echo json_encode($events);
?>
