<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StaffController.php';
require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/app/models/ArtClass.php';
require_once __DIR__ . '/includes/database.php';

use App\Models\ArtClass;

echo "<h2>Testing Class Creation</h2>";

// Test the ArtClass model directly
$artClass = new ArtClass();

// Test data
$testData = [
    'name' => 'Test Class ' . date('Y-m-d H:i:s'),
    'class_type' => 'Foundation',
    'start_date' => '2025-01-15',
    'start_time' => '10:00:00',
    'end_time' => '12:00:00',
    'tutor_id' => 'agnes',
    'room' => 'Room A',
    'capacity' => 20,
    'description' => 'Test class description'
];

echo "<h3>Creating test class...</h3>";
echo "<pre>Test data: " . print_r($testData, true) . "</pre>";

$result = $artClass->create($testData);

echo "<h3>Create result:</h3>";
echo "<pre>" . print_r($result, true) . "</pre>";

if ($result) {
    echo "<h3>✅ Class created successfully!</h3>";
    
    // Test getting all classes
    echo "<h3>All classes:</h3>";
    $allClasses = $artClass->getAll();
    echo "<pre>" . print_r($allClasses, true) . "</pre>";
    
    // Test getting upcoming classes
    echo "<h3>Upcoming classes:</h3>";
    $upcomingClasses = $artClass->getUpcoming();
    echo "<pre>" . print_r($upcomingClasses, true) . "</pre>";
    
    // Test getting the specific class by ID
    echo "<h3>Getting class by ID:</h3>";
    $classById = $artClass->getById($result['id']);
    echo "<pre>" . print_r($classById, true) . "</pre>";
    
} else {
    echo "<h3>❌ Failed to create class</h3>";
}

echo "<h3>Database connection test:</h3>";
try {
    $db = db();
    echo "✅ Database connection successful<br>";
    
    // Check if classes table exists
    $result = $db->fetch("SHOW TABLES LIKE 'classes'");
    if ($result) {
        echo "✅ Classes table exists<br>";
        
        // Count classes
        $count = $db->fetch("SELECT COUNT(*) as count FROM classes");
        echo "Total classes in database: " . $count['count'] . "<br>";
        
        // Show recent classes
        $recentClasses = $db->fetchAll("SELECT * FROM classes ORDER BY created_at DESC LIMIT 5");
        echo "<h4>Recent classes:</h4>";
        echo "<pre>" . print_r($recentClasses, true) . "</pre>";
        
    } else {
        echo "❌ Classes table does not exist<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}
?>
