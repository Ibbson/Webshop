<?php

require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$products = $db->getProducts();

echo "<pre>";
print_r($products);
echo "</pre>";