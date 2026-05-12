<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">
                <a href="product.php?id=<?= $product->id ?>" class="text-decoration-none text-dark">
                    <?= $product->name ?>
                </a>
            </h5>
            <p class="card-text text-muted"><?= $product->price ?> kr</p>
        </div>
        <div class="card-footer">
            <a href="product.php?id=<?= $product->id ?>" class="btn btn-dark btn-sm">Visa produkt</a>
        </div>
    </div>
</div>