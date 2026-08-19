<?php

class Database
{
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $pdo;

    public function __construct()
    {
        $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            [$key, $value] = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }

        $this->host = $_ENV['DB_HOST'];
        $this->dbName = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];

        try {
            $this->pdo = new PDO(
                "mysql:host=$this->host;dbname=$this->dbName;charset=utf8",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getProducts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE popularityFactor > 75 ORDER BY popularityFactor DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getCategory($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM category WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        return $stmt->fetchObject("Category");
    }

    public function getAllCategories()
    {
        $stmt = $this->pdo->query("SELECT * FROM category");
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Category");
    }

    public function getProductsForCategory($categoryId, $sort = 'name', $order = 'asc')
    {
        if (!in_array($sort, ['name', 'price'])) {
            $sort = 'name';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category_id = :categoryId ORDER BY $sort $order");
        $stmt->execute(['categoryId' => (int)$categoryId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Product");
    }

    public function searchProducts($q, $sort = 'name', $order = 'asc')
    {
        if (!in_array($sort, ['name', 'price'])) {
            $sort = 'name';
        }
        if (!in_array($order, ['asc', 'desc'])) {
            $order = 'asc';
        }

        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE name LIKE :q OR description LIKE :q ORDER BY $sort $order");
        $stmt->execute(['q' => '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_CLASS, "Product");
    }

    public function addToCart($userId, $productId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cart WHERE user_id = :userId AND product_id = :productId");
        $stmt->execute(['userId' => $userId, 'productId' => $productId]);
        $existing = $stmt->fetch(PDO::FETCH_OBJ);

        if ($existing) {
            $stmt = $this->pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = :userId AND product_id = :productId");
            $stmt->execute(['userId' => $userId, 'productId' => $productId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO cart (user_id, product_id, quantity, created_at) VALUES (:userId, :productId, 1, :createdAt)");
            $stmt->execute(['userId' => $userId, 'productId' => $productId, 'createdAt' => time()]);
        }
    }

    public function getCart($userId)
    {
        $stmt = $this->pdo->prepare("SELECT c.id, c.quantity, p.name, p.price, p.image, p.id as product_id FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = :userId");
        $stmt->execute(['userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function removeFromCart($cartId, $userId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM cart WHERE id = :cartId AND user_id = :userId");
        $stmt->execute(['cartId' => $cartId, 'userId' => $userId]);
    }
}