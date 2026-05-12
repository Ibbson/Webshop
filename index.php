<?php

require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$products = $db->getProducts();

require "components/header.php";

echo '<div class="container mt-4">';
echo '<h2 class="mb-4">Popular Products</h2>';
echo '<div class="row">';

foreach ($products as $product) {
    include "components/productcard.php";
}

echo '</div></div>';

require "components/footer.php";