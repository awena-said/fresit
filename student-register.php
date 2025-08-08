<?php
// Direct student registration page - bypasses routing system
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StudentController.php';
require_once __DIR__ . '/app/models/Student.php';
require_once __DIR__ . '/includes/database.php';

use App\Controllers\StudentController;

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
$twig = new \Twig\Environment($loader, ['cache' => false]);

// Pass Twig environment to BaseController
\App\Controllers\BaseController::setTwig($twig);

// Create controller and handle the request
$controller = new StudentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->register();
} else {
    $controller->showRegister();
}
?> 