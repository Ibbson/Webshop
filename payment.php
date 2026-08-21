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
$cartItems = $db->getCart($auth->getUserId());

if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item->price * $item->quantity;
}

\Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET']);

$lineItems = [];
foreach ($cartItems as $item) {
    array_push($lineItems, [
        'quantity' => $item->quantity,
        'price_data' => [
            'currency' => 'sek',
            'unit_amount' => $item->price * 100,
            'product_data' => [
                'name' => $item->name
            ]
        ]
    ]);
}

$checkout_session = \Stripe\Checkout\Session::create([
    'mode' => 'payment',
    'success_url' => 'http://localhost/webshop/checkoutSuccess.php',
    'cancel_url' => 'http://localhost/webshop/cart.php',
    'locale' => 'auto',
    'line_items' => $lineItems
]);

$logger->info("Payment initiated by " . $auth->getEmail() . " for " . number_format($total, 0) . " SEK");

http_response_code(303);
header("Location: " . $checkout_session->url);
exit;