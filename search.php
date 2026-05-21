<?php
require_once "models/Database.php";
require_once "models/Product.php";

$db = new Database();

$q = $_GET['q'] ?? '';
$sort = $_GET['sort'] ?? 'name';
$order = $_GET['order'] ?? 'asc';
$selectedOption = $sort . '-' . $order;

$products = $db->searchProducts($q, $sort, $order);

require "components/header.php";
?>

<div class="container mt-4">
<h2 class="mb-4">Search results for: "<?= htmlspecialchars($q) ?>"</h2>
    <select id="sortselect" class="form-select w-auto mb-4">
        <option value="name-asc" <?= $selectedOption === 'name-asc' ? 'selected' : '' ?>>Namn A-Ö</option>
        <option value="name-desc" <?= $selectedOption === 'name-desc' ? 'selected' : '' ?>>Namn Ö-A</option>
        <option value="price-asc" <?= $selectedOption === 'price-asc' ? 'selected' : '' ?>>Pris: lägst först</option>
        <option value="price-desc" <?= $selectedOption === 'price-desc' ? 'selected' : '' ?>>Pris: högst först</option>
    </select>

    <div class="row">
        <?php foreach ($products as $product) {
            include "components/productcard.php";
        } ?>
    </div>
</div>

<script>
const sortSelect = document.getElementById('sortselect');
if (sortSelect) {
    sortSelect.addEventListener('change', function() {
        const [sort, order] = this.value.split('-');
        const urlSearchParams = new URLSearchParams(window.location.search);
        urlSearchParams.set('sort', sort);
        urlSearchParams.set('order', order);
        window.location.search = urlSearchParams.toString();
    });
}
</script>

<?php require "components/footer.php"; ?>