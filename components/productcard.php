<div class="col-6 col-md-4 col-lg-3 mb-3">
    <div class="card h-100 shadow-sm">
        <?php if (!empty($product->image)): ?>
            <img src="<?= $product->image ?>" class="card-img-top" alt="<?= $product->name ?>" style="height: 180px; object-fit: cover;">
        <?php endif; ?>
        <div class="card-body p-2">
            <h6 class="card-title mb-1">
                <a href="product.php?id=<?= $product->id ?>" class="text-decoration-none text-dark">
                    <?= $product->name ?>
                </a>
            </h6>
            <p class="card-text text-muted small mb-0"><?= number_format($product->price, 0, ',', ' ') ?> kr</p>
        </div>
        <div class="card-footer p-2">
            <a href="product.php?id=<?= $product->id ?>" class="btn btn-dark btn-sm w-100">View product</a>
        </div>
    </div>
</div>