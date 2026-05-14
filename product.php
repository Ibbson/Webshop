<?php
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

require "components/header.php";
?>

<div class="container mt-4">
    <a href="index.php" class="btn btn-outline-dark mb-4">← Back to shop</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title"><?= $product->name ?></h1>
            <p class="card-text text-muted"><?= $product->description ?></p>
            <h3 class="mt-3"><?= $product->price ?> kr</h3>
        </div>
    </div>
</div>

<?php require "components/footer.php"; ?>