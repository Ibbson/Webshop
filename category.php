<?php

require_once "models/Database.php";
require_once "models/Product.php";
require_once "models/Category.php";

$db = new Database();

$categoryId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($categoryId === 0) {
    die("No category selected");
}

$currentCategory = $db->getCategory($categoryId);

$products = $db->getProductsForCategory($categoryId);

require "components/header.php";

echo '<div class="container mt-4">';
echo '<h2 class="mb-4">' . $currentCategory->name . '</h2>';
echo '<div class="row">';

foreach ($products as $product) {
    include "components/productcard.php";
}

echo '</div></div>';

require "components/footer.php";