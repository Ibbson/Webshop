<?php

require_once "models/Database.php";
require_once "models/Product.php";
require_once "models/Category.php";

$db = new Database();

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($categoryId === 0) {
    die("No category selected");
}

$category = $db->getCategory($categoryId);
$products = $db->getProductsForCategory($categoryId);

require "components/header.php";

echo "<h2>" . $category->name . "</h2>";

foreach ($products as $product) {
    include "components/productcard.php";
}

require "components/footer.php";