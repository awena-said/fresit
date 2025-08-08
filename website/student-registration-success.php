<?php
// Direct student registration success page - bypasses routing system
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StudentController.php';
require_once __DIR__ . '/app/models/Student.php';
require_once __DIR__ . '/includes/database.php';

use App\Controllers\StudentController;

try {

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
            // Show error page if student not found
            echo "<!DOCTYPE html>
            <html>
            <head>
                <title>Student Not Found</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                    .error { color: #d32f2f; background: #ffebee; padding: 20px; border-radius: 5px; margin: 20px; }
                </style>
            </head>
            <body>
                <h1>Student Not Found</h1>
                <div class='error'>
                    <p>Student with ID: {$studentId} was not found in the database.</p>
                    <p>This might be because:</p>
                    <ul style='text-align: left; display: inline-block;'>
                        <li>The registration process didn't complete properly</li>
                        <li>The student ID is incorrect</li>
                        <li>There was a database error</li>
                    </ul>
                </div>
                <p><a href='/fresit/student-register.php'>Try Registering Again</a></p>
                <p><a href='/fresit/'>Return to Home</a></p>
            </body>
            </html>";
        }
    } else {
        // Show error page if no student ID
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>No Student ID</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                .error { color: #d32f2f; background: #ffebee; padding: 20px; border-radius: 5px; margin: 20px; }
            </style>
        </head>
        <body>
            <h1>No Student ID Found</h1>
            <div class='error'>
                <p>No student ID was found in the session or URL parameters.</p>
                <p>This might be because:</p>
                <ul style='text-align: left; display: inline-block;'>
                    <li>You accessed this page directly without registering first</li>
                    <li>The session was lost</li>
                    <li>There was an error during registration</li>
                </ul>
            </div>
            <p><a href='/fresit/student-register.php'>Register a New Account</a></p>
            <p><a href='/fresit/'>Return to Home</a></p>
        </body>
        </html>";
    }

} catch (Exception $e) {
    // Show detailed error for debugging
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error - Registration Success Page</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .error { color: #d32f2f; background: #ffebee; padding: 20px; border-radius: 5px; margin: 20px; text-align: left; }
            .debug { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px; font-family: monospace; font-size: 12px; }
        </style>
    </head>
    <body>
        <h1>Error Loading Registration Success Page</h1>
        <div class='error'>
            <h3>Error Details:</h3>
            <div class='debug'>
                <strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>
                <strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>
                <strong>Line:</strong> " . $e->getLine() . "<br>
                <strong>Trace:</strong><br>
                <pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>
            </div>
        </div>
        <p><a href='/fresit/student-register.php'>Try Registering Again</a></p>
        <p><a href='/fresit/'>Return to Home</a></p>
    </body>
    </html>";
}
?>
