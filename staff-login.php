<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/vendor/autoload.php';

// Manually include all necessary files
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StaffController.php';
require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/includes/database.php';

use App\Controllers\StaffController;

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');

// Ensure cache directory exists with proper error handling
$cacheDir = __DIR__ . '/cache/twig';
$cacheOptions = ['debug' => true, 'auto_reload' => true];

// Always disable caching by default to avoid permission issues
$cacheOptions['cache'] = false;

// Only try to enable caching if we can safely create and write to the directory
try {
    // Check if cache directory exists
    if (!is_dir($cacheDir)) {
        // Try to create parent directories first
        $parentDir = dirname($cacheDir);
        if (!is_dir($parentDir)) {
            if (@mkdir($parentDir, 0755, true)) {
                error_log("Created parent cache directory: $parentDir");
            } else {
                error_log("Warning: Could not create parent cache directory: $parentDir");
                $cacheOptions['cache'] = false;
            }
        }
        
        // Now try to create the twig cache directory
        if (@mkdir($cacheDir, 0755, true)) {
            error_log("Created cache directory: $cacheDir");
            $cacheOptions['cache'] = $cacheDir;
        } else {
            error_log("Warning: Could not create cache directory: $cacheDir");
            $cacheOptions['cache'] = false;
        }
    } else {
        // Directory exists, check if it's writable
        if (is_writable($cacheDir)) {
            $cacheOptions['cache'] = $cacheDir;
        } else {
            error_log("Warning: Cache directory exists but is not writable: $cacheDir");
            $cacheOptions['cache'] = false;
        }
    }
} catch (Exception $e) {
    error_log("Warning: Exception during cache directory setup: " . $e->getMessage());
    $cacheOptions['cache'] = false;
}

$twig = new \Twig\Environment($loader, $cacheOptions);

// Pass Twig environment to BaseController
\App\Controllers\BaseController::setTwig($twig);

// Create staff controller instance
$controller = new StaffController();

// Handle the request based on method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle login form submission
    $controller->login();
} else {
    // Show login form
    $controller->showLogin();
}
?> 