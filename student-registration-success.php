<?php
// Direct student registration success page - bypasses routing system
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

// Get student ID from session or URL parameter
$studentId = $_SESSION['new_student_id'] ?? $_GET['student_id'] ?? null;

if ($studentId) {
    $student = $controller->getStudent()->getById($studentId);
    if ($student) {
        $controller->render('student/registration-success.html', [
            'title' => 'Registration Successful',
            'student' => $student
        ]);
    } else {
        // Redirect to home if student not found
        header('Location: /fresit/');
        exit;
    }
} else {
    // Redirect to home if no student ID
    header('Location: /fresit/');
    exit;
}
?>
