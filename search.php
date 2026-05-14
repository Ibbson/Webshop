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
        <option value="name-asc" <?= $selectedOption === 'name-asc' ? 'selected' : '' ?>>Name A-Z</option>
        <option value="name-desc" <?= $selectedOption === 'name-desc' ? 'selected' : '' ?>>Name Z-A</option>
        <option value="price-asc" <?= $selectedOption === 'price-asc' ? 'selected' : '' ?>>Price: low to high</option>
        <option value="price-desc" <?= $selectedOption === 'price-desc' ? 'selected' : '' ?>>Price: high to low</option>
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