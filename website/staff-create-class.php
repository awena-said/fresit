<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StaffController.php';
require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/app/models/ArtClass.php';
require_once __DIR__ . '/includes/database.php';

use App\Controllers\StaffController;

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
$cacheDir = __DIR__ . '/cache/twig';
    $cacheOptions = ['auto_reload' => true, 'cache' => false];

try {
    if (!is_dir($cacheDir) && !@mkdir($cacheDir, 0755, true)) {
        // Could not create cache directory
    } elseif (!is_writable($cacheDir)) {
        // Cache directory not writable
    } else {
        $cacheOptions['cache'] = $cacheDir;
    }
} catch (Exception $e) {
    // Continue without caching
}

$twig = new \Twig\Environment($loader, $cacheOptions);
\App\Controllers\BaseController::setTwig($twig);

// Route request to StaffController
$controller = new StaffController();
$controller->createClass();
?>
