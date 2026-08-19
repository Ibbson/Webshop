<?php
require_once "models/Database.php";
require_once "vendor/autoload.php";
require_once "Logger.php";

$logger = new Logger('info.log', 'error.log');

$lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    [$key, $value] = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($value);
}

$pdo = new PDO(
    "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

$auth = new \Delight\Auth\Auth($pdo);

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$db = new Database();

// Ta bort produkt från varukorg
if (isset($_GET['remove'])) {
    $db->removeFromCart($_GET['remove'], $auth->getUserId());
    header('Location: cart.php');
    exit;
}

$cartItems = $db->getCart($auth->getUserId());

$total = 0;
foreach ($cartItems as $item) {
    $total += $item->price * $item->quantity;
}

require "components/header.php";
?>

<div class="container mt-4">
    <h2 class="mb-4">Your Cart</h2>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="index.php">Continue shopping</a></p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <?php if (!empty($item->image)): ?>
                                    <img src="<?= $item->image ?>" style="height: 50px; object-fit: cover;" class="me-2">
                                <?php endif; ?>
                                <?= $item->name ?>
                            </td>
                            <td><?= number_format($item->price, 0) ?> kr</td>
                            <td><?= $item->quantity ?></td>
                            <td><?= number_format($item->price * $item->quantity, 0) ?> kr</td>
                            <td>
                                <a href="cart.php?remove=<?= $item->id ?>" class="btn btn-sm btn-outline-danger">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-end mt-3">
            <h4>Total: <?= number_format($total, 0) ?> kr</h4>
            <a href="checkout.php" class="btn btn-dark mt-2">Proceed to checkout</a>
        </div>
    <?php endif; ?>
</div>

<?php require "components/footer.php"; ?>