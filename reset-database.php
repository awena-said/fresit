<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Database Reset Script</h1>";

try {
    // Connect to MySQL without specifying database
    $dsn = "mysql:host=localhost;charset=utf8mb4";
    $pdo = new PDO($dsn, 'webdev', 'W3bD£velopment', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "<p>✅ Connected to MySQL successfully</p>";
    
    // Drop database if it exists
    $pdo->exec("DROP DATABASE IF EXISTS `royal_drawing_school`");
    echo "<p>✅ Dropped existing database</p>";
    
    // Create database
    $pdo->exec("CREATE DATABASE `royal_drawing_school` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "<p>✅ Created new database</p>";
    
    // Connect to the new database
    $pdo = new PDO("mysql:host=localhost;dbname=royal_drawing_school;charset=utf8mb4", 'webdev', 'W3bD£velopment', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "<p>✅ Connected to new database</p>";
    
    // Create tables
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
        $pdo->exec($sql);
    }
    
    echo "<p>✅ Created all tables</p>";
    
    echo "<p>✅ Database created successfully (no sample data added)</p>";
    
    // Verify tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll();
    echo "<p>✅ Tables in database:</p><ul>";
    foreach ($tables as $table) {
        $tableName = array_values($table)[0];
        echo "<li>$tableName</li>";
    }
    echo "</ul>";
    
    // Show table counts
    $staffCount = $pdo->query("SELECT COUNT(*) as count FROM staff_users")->fetch()['count'];
    $classesCount = $pdo->query("SELECT COUNT(*) as count FROM classes")->fetch()['count'];
    
    echo "<p>✅ Table row counts:</p>";
    echo "<ul>";
    echo "<li>Staff users: $staffCount</li>";
    echo "<li>Classes: $classesCount</li>";
    echo "</ul>";
    
    echo "<p><strong>🎉 Database reset completed successfully!</strong></p>";
    echo "<p>You can now test the application at: <a href='/fresit/'>localhost/fresit/</a></p>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?> 