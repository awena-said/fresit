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

// Create staff controller instance
$controller = new StaffController();

// Handle logout
$controller->logout();
?> 