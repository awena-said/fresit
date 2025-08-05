<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database connection
require_once '../includes/db_connect.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!$input || !isset($input['name']) || !isset($input['email']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

$name = trim($input['name']);
$email = trim($input['email']);
$password = $input['password'];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

// Validate password length
if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 6 characters long']);
    exit;
}

// Validate name
if (strlen($name) < 2) {
    http_response_code(400);
    echo json_encode(['error' => 'Name must be at least 2 characters long']);
    exit;
}

try {
    // Get database connection
    $connection = db_connect();
    
    if (!$connection) {
        throw new Exception('Database connection failed');
    }

    // Check if email already exists
    $stmt = $connection->prepare('SELECT id FROM staff_users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        http_response_code(409);
        echo json_encode(['error' => 'Email address already registered']);
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new staff user
    $stmt = $connection->prepare('INSERT INTO staff_users (name, email, password) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $hashedPassword);
    
    if ($stmt->execute()) {
        $userId = $connection->insert_id;
        
        echo json_encode([
            'success' => true,
            'message' => 'Staff account created successfully',
            'user_id' => $userId
        ]);
    } else {
        throw new Exception('Failed to insert user');
    }

} catch (Exception $e) {
    error_log('Database error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
}
?> 