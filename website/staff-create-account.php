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

/**
 * Initialize Twig environment with cache handling
 * @return \Twig\Environment
 */
function initializeTwig()
{
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

    return new \Twig\Environment($loader, $cacheOptions);
}

// Initialize Twig and set in BaseController
$twig = initializeTwig();
\App\Controllers\BaseController::setTwig($twig);

// Route request to StaffController
$controller = new StaffController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->createAccount();
} else {
    $controller->showCreateAccount();
}
?>