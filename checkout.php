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

require "components/header.php";
?>

<div class="container mt-4">
    <h2 class="mb-4">Checkout</h2>

    <div class="card shadow-sm p-4 mb-4">
        <h4>Order Summary</h4>
        <?php foreach ($cartItems as $item): ?>
            <div class="d-flex justify-content-between mb-2">
                <span><?= $item->name ?> x<?= $item->quantity ?></span>
                <span><?= number_format($item->price * $item->quantity, 0) ?> kr</span>
            </div>
        <?php endforeach; ?>
        <hr>
        <div class="d-flex justify-content-between">
            <strong>Total</strong>
            <strong id="total-sek"><?= number_format($total, 0) ?> SEK</strong>
        </div>

        
        <div class="mt-4">
            <label class="form-label">View price in another currency:</label>
            <select id="currency-select" class="form-select w-auto">
                <option value="EUR">EUR</option>
                <option value="USD">USD</option>
                <option value="GBP">GBP</option>
            </select>
            <p class="mt-2">Converted price: <strong id="converted-price">-</strong></p>
            <p class="text-muted small">You will still be charged in SEK</p>
        </div>
    </div>

    <a href="payment.php" class="btn btn-dark btn-lg">Pay now with Stripe</a>
</div>

<script>
const totalSEK = <?= $total ?>;
const apiKey = '<?= $_ENV['EXCHANGE_API_KEY'] ?>';

async function convertCurrency(targetCurrency) {
    try {
        const response = await fetch('currency.php');
        if (!response.ok) {
            throw new Error('API request failed');
        }

        const data = await response.json();
        const sekRate = data.rates['SEK'];
        const targetRate = data.rates[targetCurrency];

        
        const converted = (targetRate / sekRate) * totalSEK;
        
        document.getElementById('converted-price').textContent = 
            converted.toFixed(2) + ' ' + targetCurrency;

    } catch (error) {
        console.error('Currency conversion failed:', error);
        document.getElementById('converted-price').textContent = 
            'Currency conversion temporarily unavailable';
    }
}


convertCurrency(document.getElementById('currency-select').value);


document.getElementById('currency-select').addEventListener('change', function() {
    convertCurrency(this.value);
});
</script>

<?php require "components/footer.php"; ?>