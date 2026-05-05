<?php

class Database
{
    private $host = "localhost";
    private $dbName = "webshop";
    private $username = "root";
    private $password = "root";
    private $pdo;

    public function __construct()
    {
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
        $stmt = $this->pdo->prepare("SELECT * FROM products ORDER BY popularityFactor DESC LIMIT 10");
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

    public function getProductsForCategory($categoryId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE category_id = :categoryId");
        $stmt->execute(['categoryId' => (int)$categoryId]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, "Product");
    }
}