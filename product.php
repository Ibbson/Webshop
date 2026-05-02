<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);




require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Missing product id");
}

$product = $db->getProductById($id);

if (!$product) {
    die("Product not found");
}

echo "<h1>" . $product->name . "</h1>";
echo "<p>" . $product->description . "</p>";
echo "<strong>" . $product->price . " kr</strong>";