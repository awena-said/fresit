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
        try {
            // First, connect without specifying database to check if it exists
            $tempConnection = new mysqli(DB_HOST, DB_USER, DB_PASS);
            
            if ($tempConnection->connect_error) {
                throw new Exception("Database connection failed: " . $tempConnection->connect_error);
            }
            
            // Check if database exists
            $result = $tempConnection->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '" . DB_NAME . "'");
            $databaseExists = $result->num_rows > 0;
            
            if (!$databaseExists) {
                // Database doesn't exist, create it
                $this->createDatabase($tempConnection);
            }
            
            $tempConnection->close();
            
            // Now connect to the specific database
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Database connection failed: " . $this->connection->connect_error);
            }
            
            // Set charset
            $this->connection->set_charset(DB_CHARSET);
            
            // Check if tables exist, if not create them
            try {
                $this->checkAndCreateTables();
            } catch (Exception $e) {
                // Log the error but don't fail the connection
                error_log("Table creation warning: " . $e->getMessage());
                // Continue without failing - tables can be created later
            }
            
        } catch (Exception $e) {
            throw new Exception("Database connection failed: " . $e->getMessage() . ". Please ensure MySQL is running and credentials are correct.");
        }
    }
    
    /**
     * Create the database if it doesn't exist
     */
    private function createDatabase($connection) {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            if (!$connection->query($sql)) {
                throw new Exception("Failed to create database: " . $connection->error);
            }
        } catch (Exception $e) {
            throw new Exception("Failed to create database: " . $e->getMessage());
        }
    }
    
    /**
     * Check if tables exist and create them if they don't
     */
    private function checkAndCreateTables() {
        try {
            // Always try to create tables - IF NOT EXISTS will handle duplicates
            $this->createTables();
            
            // Verify that all required tables exist
            $requiredTables = ['staff_users', 'students', 'classes', 'applications'];
            $result = $this->connection->query("SHOW TABLES");
            $existingTables = [];
            while ($row = $result->fetch_array()) {
                $existingTables[] = $row[0];
            }
            
            // Log which tables were created successfully
            error_log("Database tables check - Required: " . implode(", ", $requiredTables));
            error_log("Database tables check - Existing: " . implode(", ", $existingTables));
            
            $missingTables = [];
            foreach ($requiredTables as $table) {
                if (!in_array($table, $existingTables)) {
                    $missingTables[] = $table;
                }
            }
            
            if (!empty($missingTables)) {
                error_log("Database tables check - Missing: " . implode(", ", $missingTables));
                // Don't throw exception, just log the warning
                error_log("Warning: Some tables are missing: " . implode(", ", $missingTables));
            } else {
                error_log("Database tables check - All tables created successfully");
            }
        } catch (Exception $e) {
            error_log("Database tables check - Error: " . $e->getMessage());
            // Don't throw exception, just log the error
            error_log("Warning: Table creation had issues: " . $e->getMessage());
        }
    }
    
    /**
     * Create tables from SQL file
     */
    private function createTables() {
        try {
            // Read and execute the SQL file
            $sqlFile = __DIR__ . '/../database/tables.sql';
            if (file_exists($sqlFile)) {
                error_log("Database: Reading SQL file: " . $sqlFile);
                $sql = file_get_contents($sqlFile);
                
                // Disable foreign key checks temporarily
                $this->connection->query("SET FOREIGN_KEY_CHECKS = 0");
                error_log("Database: Foreign key checks disabled");
                
                // Split SQL into individual statements
                $statements = array_filter(array_map('trim', explode(';', $sql)));
                error_log("Database: Found " . count($statements) . " SQL statements to execute");
                
                $errors = [];
                $successCount = 0;
                foreach ($statements as $index => $statement) {
                    if (!empty($statement)) {
                        try {
                            error_log("Database: Executing statement " . ($index + 1) . ": " . substr($statement, 0, 50) . "...");
                            if (!$this->connection->query($statement)) {
                                $errors[] = $this->connection->error;
                                error_log("Database: Table creation error: " . $this->connection->error);
                            } else {
                                $successCount++;
                                error_log("Database: Statement " . ($index + 1) . " executed successfully");
                            }
                        } catch (Exception $e) {
                            $errors[] = $e->getMessage();
                            error_log("Database: Table creation exception: " . $e->getMessage());
                        }
                    }
                }
                
                // Re-enable foreign key checks
                $this->connection->query("SET FOREIGN_KEY_CHECKS = 1");
                error_log("Database: Foreign key checks re-enabled");
                error_log("Database: Successfully executed " . $successCount . " statements");
                
                // If there were errors, log them but don't fail
                if (!empty($errors)) {
                    error_log("Database: Errors occurred: " . implode(", ", $errors));
                    error_log("Database: Warning - Some tables may not have been created properly");
                }
            } else {
                error_log("Database: SQL file not found: " . $sqlFile);
                throw new Exception("SQL file not found: " . $sqlFile);
            }
        } catch (Exception $e) {
            error_log("Database: createTables exception: " . $e->getMessage());
            throw new Exception("Failed to create tables: " . $e->getMessage());
        }
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Get database connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute a query and return result
     */
    public function query($sql, $params = []) {
        if (!empty($params)) {
            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new Exception("Query preparation failed: " . $this->connection->error);
            }
            
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $result = $this->connection->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $this->connection->error);
            }
            return $result;
        }
    }
    
    /**
     * Fetch all rows
     */
    public function fetchAll($sql, $params = []) {
        $result = $this->query($sql, $params);
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
    
    /**
     * Fetch single row
     */
    public function fetch($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result->fetch_assoc();
    }
    
    /**
     * Execute query without returning results
     */
    public function execute($sql, $params = []) {
        return $this->query($sql, $params);
    }
    
    /**
     * Get last insert ID
     */
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback() {
        $this->connection->rollback();
    }
}

/**
 * Global database helper functions
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