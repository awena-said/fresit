<?php
// Direct API endpoint for booking page classes
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/models/ArtClass.php';
require_once __DIR__ . '/includes/database.php';

use App\Models\ArtClass;

try {
    $classType = $_GET['type'] ?? '';
    $startDate = $_GET['start_date'] ?? '';
    
    if (empty($classType) || empty($startDate)) {
        http_response_code(400);
        echo json_encode(['error' => 'Class type and start date are required']);
        exit;
    }
    
    // Validate that the start date is not more than 7 days in advance
    $today = new DateTime();
    $selectedDate = new DateTime($startDate);
    $maxDate = (new DateTime())->modify('+7 days');
    
    if ($selectedDate > $maxDate) {
        http_response_code(400);
        echo json_encode(['error' => 'You can only enroll up to 7 days in advance']);
        exit;
    }
    
    // Allow today's date (set time to start of day for comparison)
    $todayStart = (new DateTime())->setTime(0, 0, 0);
    $selectedDateStart = (new DateTime($startDate))->setTime(0, 0, 0);
    
    if ($selectedDateStart < $todayStart) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot enroll for past dates']);
        exit;
    }

    // Get available classes from the database
    $artClass = new ArtClass();
    $classes = $artClass->getAvailableClassesForBooking($classType, $startDate);
    
    echo json_encode($classes);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
?>
