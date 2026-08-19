<?php
require_once "models/Database.php";
require_once "models/Product.php";
require_once "Logger.php";

$logger = new Logger('info.log', 'error.log');
$db = new Database();

try {
    $products = $db->getAllProducts();
    $logger->info("Prisjakt XML feed generated with " . count($products) . " products");
} catch (Exception $e) {
    $logger->error("Failed to generate Prisjakt XML feed: " . $e->getMessage());
    die("Error generating feed");
}

header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="utf-8"?>';
?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
  <channel>
    <title>Retro End</title>
    <link>http://localhost/webshop</link>
    <description>Authentic Retro Football Gear</description>

    <?php foreach ($products as $product): ?>
    <item>
      <g:id><?= $product->id ?></g:id>
      <g:title><?= htmlspecialchars($product->name) ?></g:title>
      <g:description><?= htmlspecialchars($product->description) ?></g:description>
      <g:link>http://localhost/webshop/product.php?id=<?= $product->id ?></g:link>
      <g:availability>in_stock</g:availability>
      <g:price><?= number_format($product->price, 2) ?> SEK</g:price>
      <g:google_product_category>Sports</g:google_product_category>
    </item>
    <?php endforeach; ?>

  </channel>
</rss>