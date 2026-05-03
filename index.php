<?php

require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$products = $db->getProducts();

foreach ($products as $product) {
    echo "<h2>";
    echo "<a href='product.php?id=" . $product->id . "'>";
    echo $product->name;
    echo "</a>";
    echo "</h2>";

    echo "<p>" . $product->price . " kr</p>";

    echo "<hr>";
}