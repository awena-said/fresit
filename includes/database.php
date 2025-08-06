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
 * Database Connection Class with Auto-Setup
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
            // First, connect without specifying database to check if it exists
            $dsn = "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET;
            $tempConnection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Check if database exists
            $stmt = $tempConnection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
            $databaseExists = $stmt->fetch();
            
            if (!$databaseExists) {
                // Database doesn't exist, create it
                $this->createDatabase($tempConnection);
            }
            
            // Now connect to the specific database
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            
            // Check if tables exist, if not create them
            $this->checkAndCreateTables();
            
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage() . ". Please ensure MySQL is running and credentials are correct.");
        }
    }
    
    /**
     * Create the database if it doesn't exist
     */
    private function createDatabase($connection) {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            $connection->exec($sql);
        } catch (PDOException $e) {
            throw new Exception("Failed to create database: " . $e->getMessage());
        }
    }
    
    /**
     * Check if tables exist and create them if they don't
     */
    private function checkAndCreateTables() {
        try {
            // Check if staff_users table exists
            $stmt = $this->connection->query("SHOW TABLES LIKE 'staff_users'");
            $tableExists = $stmt->fetch();
            
            if (!$tableExists) {
                $this->createTables();
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to check/create tables: " . $e->getMessage());
        }
    }
    
    /**
     * Create all necessary tables
     */
    private function createTables() {
        try {
            // Read and execute the SQL file
            $sqlFile = __DIR__ . '/../database/tables.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                
                // Split SQL into individual statements
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                
                foreach ($statements as $statement) {
                    if (!empty($statement)) {
                        $this->connection->exec($statement);
                    }
                }
            } else {
                // Fallback: Create tables manually if SQL file doesn't exist
                $this->createTablesManually();
            }
        } catch (PDOException $e) {
            throw new Exception("Failed to create tables: " . $e->getMessage());
        }
    }
    
    /**
     * Create tables manually as fallback
     */
    private function createTablesManually() {
        $tables = [
            "CREATE TABLE `staff_users` (
                `id` VARCHAR(255) PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `password` VARCHAR(255) NOT NULL
            )",
            
            "CREATE TABLE `students` (
                `id` VARCHAR(255) PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) UNIQUE NOT NULL,
                `phone` VARCHAR(50),
                `password` VARCHAR(255) NOT NULL
            )",
            
            "CREATE TABLE `classes` (
                `id` VARCHAR(255) PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `type` ENUM('Foundation', 'Imagination', 'Watercolour') NOT NULL,
                `date` DATE NOT NULL,
                `start_time` TIME NOT NULL,
                `end_time` TIME NOT NULL,
                `tutor_id` VARCHAR(255) NOT NULL,
                `capacity` INT NOT NULL DEFAULT 20,
                FOREIGN KEY (`tutor_id`) REFERENCES `staff_users`(`id`) ON DELETE CASCADE
            )",
            
            "CREATE TABLE `applications` (
                `id` VARCHAR(255) PRIMARY KEY,
                `class_id` VARCHAR(255) NOT NULL,
                `student_id` VARCHAR(255) NULL,
                `student_name` VARCHAR(255) NOT NULL,
                `student_email` VARCHAR(255) NOT NULL,
                `student_phone` VARCHAR(50) NOT NULL,
                `status` ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
                FOREIGN KEY (`class_id`) REFERENCES `classes`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE SET NULL
            )"
        ];
        
        foreach ($tables as $sql) {
            $this->connection->exec($sql);
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