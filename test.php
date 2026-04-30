<?php

require_once "models/Database.php";

$db = new Database();
$pdo = $db->connect();

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

echo "<pre>";
print_r($products);
echo "</pre>";