<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/vendor/autoload.php';

// Manually include all necessary files
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/HomeController.php';
require_once __DIR__ . '/app/controllers/StaffController.php';
require_once __DIR__ . '/app/controllers/StudentController.php';

require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/app/models/Student.php';
require_once __DIR__ . '/app/models/ArtClass.php';

require_once __DIR__ . '/includes/database.php';

use App\Controllers\HomeController;
use App\Controllers\StaffController;
use App\Controllers\StudentController;

// Get the route from the URI
$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path from the URI if it exists
$basePath = '/fresit';
if (strpos($route, $basePath) === 0) {
    $route = substr($route, strlen($basePath));
}

// Handle static assets and direct file access
if (strpos($route, '/public/') === 0) {
    // Serve static assets from public directory
    // Decode URL-encoded characters for proper file path handling
    $decodedRoute = urldecode($route);
    $filePath = __DIR__ . $decodedRoute;
    
    if (file_exists($filePath)) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'webp' => 'image/webp'
        ];
        
        if (isset($mimeTypes[$extension])) {
            header('Content-Type: ' . $mimeTypes[$extension]);
        }
        
        readfile($filePath);
        exit;
    }
}

// Handle direct PHP file access (like router.php, test.php, etc.)
if (strpos($route, '.php') !== false) {
    // If it's a direct PHP file access, serve it directly
    $filePath = __DIR__ . $route;
    if (file_exists($filePath)) {
        include $filePath;
        exit;
    }
}

// Ensure route starts with /
if (empty($route)) {
    $route = '/';
}

// Debug logging
error_log("Original URI: " . $_SERVER['REQUEST_URI']);
error_log("Processed route: " . $route);

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

// Route the request
switch ($route) {
    case '/':
    case '/home':
        $controller = new HomeController();
        $controller->index();
        break;

    case '/reviews':
        $controller = new HomeController();
        $controller->reviews();
        break;

    case '/booking':
        $controller = new HomeController();
        $controller->booking();
        break;

    case '/booking-success':
        $controller = new HomeController();
        $controller->bookingSuccess();
        break;

    case '/applications':
        $controller = new HomeController();
        $controller->applications();
        break;

    // Student routes
    case '/student/register':
        $controller = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case '/student/login':
        $controller = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case '/student/dashboard':
        $controller = new StudentController();
        $controller->dashboard();
        break;

    case '/student/logout':
        $controller = new StudentController();
        $controller->logout();
        break;

    case '/student/change-password':
        $controller = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->changePassword();
        } else {
            $controller->showChangePassword();
        }
        break;

    case '/student/forgot-password':
        $controller = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->forgotPassword();
        } else {
            $controller->showForgotPassword();
        }
        break;

    case '/student/reset-password':
        $controller = new StudentController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->resetPassword();
        } else {
            $controller->showResetPassword();
        }
        break;



    case '/student/apply':
        $controller = new StudentController();
        $controller->submitApplication();
        break;

    case '/student/application-success':
        $controller = new StudentController();
        $controller->applicationSuccess();
        break;

    case '/student/api/classes':
        $controller = new StudentController();
        $controller->getAvailableClasses();
        break;

    // Staff routes
    case '/staff/login':
        $controller = new StaffController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case '/staff/create-account':
        $controller = new StaffController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createAccount();
        } else {
            $controller->showCreateAccount();
        }
        break;

    case '/staff/dashboard':
        $controller = new StaffController();
        $controller->dashboard();
        break;

    case '/staff/logout':
        $controller = new StaffController();
        $controller->logout();
        break;

    case '/staff/applications':
        $controller = new StaffController();
        $controller->applications();
        break;

    case '/staff/classes':
        $controller = new StaffController();
        $controller->classes();
        break;



    case '/staff/application':
        $controller = new StaffController();
        $controller->getApplication($_GET['id'] ?? null);
        break;

    case '/staff/application/accept':
        $controller = new StaffController();
        $controller->acceptApplication($_POST['id'] ?? null);
        break;

    case '/staff/application/reject':
        $controller = new StaffController();
        $controller->rejectApplication($_POST['id'] ?? null);
        break;

    case '/staff/classes/create':
        $controller = new StaffController();
        $controller->createClass();
        break;

    case '/staff/classes/delete':
        $controller = new StaffController();
        $controller->deleteClass($_POST['id'] ?? null);
        break;



    case '/status':
        $controller = new HomeController();
        $controller->status();
        break;

    case '/login':
        // Redirect to staff login
        header('Location: /fresit/staff/login');
        exit;
        break;
        
    case '/staff-dashboard':
        // Redirect to staff dashboard
        header('Location: /fresit/staff/dashboard');
        exit;
        break;

    case '/debug':
        echo "Debug Info:<br>";
        echo "Original URI: " . $_SERVER['REQUEST_URI'] . "<br>";
        echo "Processed Route: " . $route . "<br>";
        echo "Base Path: " . $basePath . "<br>";
        echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
        echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "<br>";
        exit;

    default:
        http_response_code(404);
        echo $twig->render('404.html', ['title' => 'Page Not Found']);
        break;
}
?> 