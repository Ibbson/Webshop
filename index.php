<?php

require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$products = $db->getProducts();

require "components/header.php";

foreach ($products as $product) {
    include "components/productcard.php";
}

require "components/footer.php";