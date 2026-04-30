<?php

class Database
{
    private $host = "localhost";
    private $dbName = "webshop";
    private $username = "root";
    private $password = "";
    private $pdo;

    public function connect()
    {
        $this->pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->dbName;charset=utf8",
            $this->username,
            $this->password
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $this->pdo;
    }
}