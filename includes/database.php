<?php

/**
 * Database Configuration for phpMyAdmin
 */

// Database connection settings
define('DB_HOST', 'localhost');
define('DB_NAME', 'royal_drawing_school');
define('DB_USER', 'webdev');
define('DB_PASS', 'W3bD£velopment');
define('DB_CHARSET', 'utf8mb4');

/**
 * Database Connection Class
 */
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Check if MySQL PDO driver is available
        if (!extension_loaded('pdo_mysql')) {
            throw new Exception("PDO MySQL extension is not installed. Please install it to use this application.");
        }
        
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage() . ". Please ensure the database exists and credentials are correct.");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Query failed: " . $e->getMessage());
        }
    }
    
    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
    
    public function execute($sql, $params = []) {
        return $this->query($sql, $params)->rowCount();
    }
    
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }
    
    public function commit() {
        return $this->connection->commit();
    }
    
    public function rollback() {
        return $this->connection->rollback();
    }
}

/**
 * Database Helper Functions
 */
function db() {
    return Database::getInstance();
}

function db_query($sql, $params = []) {
    return db()->query($sql, $params);
}

function db_fetch_all($sql, $params = []) {
    return db()->fetchAll($sql, $params);
}

function db_fetch($sql, $params = []) {
    return db()->fetch($sql, $params);
}

function db_execute($sql, $params = []) {
    return db()->execute($sql, $params);
}

function db_last_insert_id() {
    return db()->lastInsertId();
} 