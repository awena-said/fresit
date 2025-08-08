<?php
// Simple registration success page - direct database access
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Include necessary files
    require_once __DIR__ . '/includes/database.php';
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Get student ID from session
    $studentId = $_SESSION['new_student_id'] ?? null;
    
    if (!$studentId) {
        throw new Exception("No student ID found in session");
    }
    
    // Get database connection
    $db = db();
    
    // Get student data directly
    $student = $db->fetch("SELECT * FROM students WHERE id = ? AND is_active = 1", [$studentId]);
    
    if (!$student) {
        throw new Exception("Student not found with ID: $studentId");
    }
    
    // Initialize Twig
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
    $twig = new \Twig\Environment($loader, ['cache' => false]);
    
    // Render the template
    echo $twig->render('student/registration-success.html', [
        'title' => 'Registration Successful',
        'student' => $student
    ]);
    
} catch (Exception $e) {
    // Show error page
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Registration Error</title>
        <style>
            body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
            .error { color: #d32f2f; background: #ffebee; padding: 20px; border-radius: 5px; margin: 20px; }

        </style>
    </head>
    <body>
        <h1>Registration Error</h1>
        <div class='error'>
            <h3>Error Details:</h3>
            <p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        </div>
        <p><a href='/royaldrawingschool/student-register.php'>Try Registering Again</a></p>
        <p><a href='/royaldrawingschool/'>Return to Home</a></p>
    </body>
    </html>";
}
?>
