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

require_once __DIR__ . '/app/models/Application.php';
require_once __DIR__ . '/app/models/ClassModel.php';
require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/app/models/Student.php';

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

// Ensure route starts with /
if (empty($route)) {
    $route = '/';
}

// Debug output
echo "<h2>Debug Information:</h2>";
echo "<p><strong>Original URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Processed Route:</strong> " . $route . "</p>";
echo "<p><strong>Base Path:</strong> " . $basePath . "</p>";
echo "<hr>";

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
$twig = new \Twig\Environment($loader, ['debug' => true, 'auto_reload' => true]);

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

    case '/student/booking':
        $controller = new StudentController();
        $controller->showBooking();
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

    case '/staff/schedule':
        $controller = new StaffController();
        $controller->schedule();
        break;

    case '/staff/roster':
        $controller = new StaffController();
        $controller->roster();
        break;

    case '/staff/emails':
        $controller = new StaffController();
        $controller->emails();
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

    case '/staff/classes/roster':
        $controller = new StaffController();
        $controller->getClassRoster($_GET['id'] ?? null);
        break;

    case '/staff/classes/roster/print':
        $controller = new StaffController();
        $controller->printRoster($_GET['id'] ?? null);
        break;

    case '/status':
        $controller = new HomeController();
        $controller->status();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Page Not Found</h1>";
        echo "<p>Route not found: " . $route . "</p>";
        echo "<p><a href='/fresit/'>Go to Home</a></p>";
        break;
}
?> 