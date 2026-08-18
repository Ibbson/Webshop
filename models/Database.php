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
    public function getAllProducts()
{
    $stmt = $this->pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
}

