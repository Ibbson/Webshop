<?php

require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$products = $db->getProducts();

require "components/header.php";
?>

<div class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Retro End</h1>
        <p class="hero-subtitle">Authentic Retro Football Gear</p>
        <a href="#products" class="btn-gold">Shop Now</a>
    </div>
</div>

<div class="container mt-5" id="products">
    <h2 class="mb-4 text-center" style="color: #6B4F10; letter-spacing: 3px; text-transform: uppercase;">Popular Products</h2>
    <div class="row">
        <?php foreach ($products as $product) {
            include "components/productcard.php";
        } ?>
    </div>
</div>

<?php require "components/footer.php"; ?>