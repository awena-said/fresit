<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/includes/database.php';

use App\Models\ArtClass;

try {
    $artClass = new ArtClass();
    $classes = $artClass->getUpcoming();
    
    echo "=== DEBUG: Classes from Database ===\n";
    echo "Total classes found: " . count($classes) . "\n\n";
    
    if (count($classes) > 0) {
        foreach ($classes as $class) {
            echo "Class ID: " . $class['id'] . "\n";
            echo "Name: " . $class['name'] . "\n";
            echo "Type: " . $class['type'] . "\n";
            echo "Date: " . $class['date'] . "\n";
            echo "Start Time: " . $class['start_time'] . "\n";
            echo "End Time: " . $class['end_time'] . "\n";
            echo "Tutor: " . $class['tutor_name'] . "\n";
            echo "---\n";
        }
    } else {
        echo "No classes found in database!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
