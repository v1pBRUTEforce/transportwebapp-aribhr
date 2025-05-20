<?php

namespace App\Models;

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = 'your_mysql_password';
    private $dbname = "covoiturage";
    private $pdo;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";

        try {
            $this->pdo = new \PDO($dsn, $this->username);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt;
        } catch (\PDOException $e) {
            die("Query failed: " . $e->getMessage());      
        }
    }
}

?>
