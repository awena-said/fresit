<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Auto-Setup Test</h1>";

try {
    // Test database connection and auto-setup
    require_once __DIR__ . '/includes/database.php';
    
    echo "<p>✅ Database connection successful!</p>";
    
    // Test if we can query the database
    $db = db();
    $result = $db->fetch("SELECT COUNT(*) as count FROM applications");
    
    echo "<p>✅ Database query successful! Applications count: " . $result['count'] . "</p>";
    
    // Test if tables exist
    $tables = $db->fetchAll("SHOW TABLES");
    echo "<p>✅ Tables found:</p><ul>";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<li>$tableName</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>🎉 Database auto-setup is working perfectly!</strong></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?> 