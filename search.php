<?php
require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$q = $_GET['q'] ?? '';

$products = $db->searchProducts($q);

require "components/header.php";
?>

<div class="container mt-4">
    <h2>Search results for: "<?= htmlspecialchars($q) ?>"</h2>
    <div class="row mt-3">
        <?php foreach ($products as $product) { ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="product.php?id=<?= $product->id ?>" class="text-decoration-none text-dark">
                                <?= $product->name ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted"><?= $product->description ?></p>
                        <p class="card-text"><?= $product->price ?> kr</p>
                    </div>
                    <div class="card-footer">
                        <a href="product.php?id=<?= $product->id ?>" class="btn btn-dark btn-sm">View product</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php require "components/footer.php"; ?>