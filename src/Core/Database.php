<?php
namespace App\Core;

class Database {
    private static $instance = null;
    private $connection;
    private $statement;
    
    private function __construct() {
        $config = require_once __DIR__ . '/../../config/database.php';
        
        try {
            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};";
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function query($sql, $params = []) {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($params);
        return $this;
    }
    
    public function fetchAll($sql, $params = []) {
        // Check if connection exists
        if (!$this->connection) {
            die("Database connection not established");
        }
        
        // Prepare and execute the statement directly
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        
        // Return the results
        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }

    public function fetchAllPaginated($sql, $page = 1, $perPage = 10, $params = []) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Add LIMIT and OFFSET to the query
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        return $this->fetchAll($sql, $params);
    }

    public function countRows($sql, $params = []) {
        // Convert the SQL to a COUNT query
        $countSql = "SELECT COUNT(*) FROM (" . $sql . ") AS count_query";
        return (int)$this->fetchColumn($countSql, $params);
    }

    
    
    public function fetch($sql, $params = []) {
        // Debug the SQL query
        if (!is_string($sql)) {
            die("Invalid SQL query in fetch: " . print_r($sql, true));
        }
        
        // Check if connection exists
        if (!$this->connection) {
            die("Database connection not established");
        }
        
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        return $statement->fetch(\PDO::FETCH_OBJ);
    }
    
    public function fetchColumn($sql, $params = []) {
        // Check if connection exists
        if (!$this->connection) {
            die("Database connection not established");
        }
        
        // Execute the query first
        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
        
        // Then fetch the column
        return $statement->fetchColumn();
    }
    
    public function rowCount() {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        return $this->statement->rowCount();
    }
    
    public function lastInsertId() {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        return $this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        return $this->connection->commit();
    }
    
    public function rollBack() {
         // Check if connection exists
         if (!$this->connection) {
            die("Database connection not established");
        }
        return $this->connection->rollBack();
    }

    public function insert($sql, $params = []) {
        try {
            // Check if connection exists
            if (!$this->connection) {
                die("Database connection not established");
            }
            
            // Prepare and execute the statement
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            
            // Return the last inserted ID
            return $this->connection->lastInsertId();
        } catch (\PDOException $e) {
            // Log the error and return false
            error_log("Database insert error: " . $e->getMessage());
            return false;
        }
    }

    public function execute($sql, $params = []) {
        try {
            // Check if connection exists
            if (!$this->connection) {
                die("Database connection not established");
            }
            
            // Prepare and execute the statement
            $statement = $this->connection->prepare($sql);
            return $statement->execute($params);
        } catch (\PDOException $e) {
            // Log the error and return false
            error_log("Database execute error: " . $e->getMessage());
            return false;
        }
    }
}