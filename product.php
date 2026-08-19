<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "models/Database.php";
require_once "models/Product.php";
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
$db = new Database();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Missing product id");
}

$product = $db->getProductById($id);

if (!$product) {
    die("Product not found");
}

// Hantera "Add to cart"
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if ($auth->isLoggedIn()) {
        $db->addToCart($auth->getUserId(), $product->id);
        $logger->info("User " . $auth->getEmail() . " added product " . $product->name . " to cart");
        $success = 'Product added to cart!';
    } else {
        $error = 'You need to login to add products to cart!';
        $logger->error("Guest tried to add product to cart");
    }
}

require "components/header.php";
?>

<div class="container mt-4">
    <a href="index.php" class="btn btn-outline-dark mb-4">← Back to shop</a>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <?php if (!empty($product->image)): ?>
            <img src="<?= $product->image ?>" class="card-img-top" alt="<?= $product->name ?>" style="max-height: 400px; object-fit: contain;">
        <?php endif; ?>
        <div class="card-body">
            <h1 class="card-title"><?= $product->name ?></h1>
            <p class="card-text text-muted"><?= $product->description ?></p>
            <h3 class="mt-3"><?= $product->price ?> kr</h3>

            <form method="POST" class="mt-3">
                <button type="submit" name="add_to_cart" class="btn btn-dark">
                    Add to cart
                </button>
            </form>
        </div>
    </div>
</div>

<?php require "components/footer.php"; ?>