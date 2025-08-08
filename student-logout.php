<?php
// Direct student logout page - bypasses routing system
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/BaseController.php';
require_once __DIR__ . '/app/controllers/StudentController.php';
require_once __DIR__ . '/app/models/Student.php';
require_once __DIR__ . '/includes/database.php';

use App\Controllers\StudentController;

// Create controller and handle the request
$controller = new StudentController();
$controller->logout();
?>
