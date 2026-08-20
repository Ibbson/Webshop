<?php
require_once "vendor/autoload.php";
require_once "models/Database.php";
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

if ($auth->isLoggedIn()) {
    $db->clearCart($auth->getUserId());
    $logger->info("Successful payment by " . $auth->getEmail());
}

require "components/header.php";
?>

<div class="container mt-5 text-center">
    <h1 style="color: var(--gold);">Thank you for your order! 🎉</h1>
    <p class="mt-3">Your payment was successful. We'll get your retro gear shipped to you soon!</p>
    <a href="index.php" class="btn btn-dark mt-3">Continue Shopping</a>
</div>

<?php require "components/footer.php"; ?>