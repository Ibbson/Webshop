<div class="col-md-4 mb-4">
    <div class="card h-100 shadow-sm">
        <?php if (!empty($product->image)): ?>
            <img src="<?= $product->image ?>" class="card-img-top" alt="<?= $product->name ?>" style="height: 250px; object-fit: cover;">
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title">
                <a href="product.php?id=<?= $product->id ?>" class="text-decoration-none text-dark">
                    <?= $product->name ?>
                </a>
            </h5>
            <p class="card-text text-muted"><?= $product->price ?> kr</p>
        </div>
        <div class="card-footer">
            <a href="product.php?id=<?= $product->id ?>" class="btn btn-dark btn-sm">View product</a>
        </div>
    </div>
</div>