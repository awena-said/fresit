<?php
// Test staff login functionality
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/models/StaffUser.php';
require_once __DIR__ . '/includes/database.php';

use App\Models\StaffUser;

echo "<h1>Staff Login Test</h1>";

$staffUser = new StaffUser();

echo "<h2>Checking if users exist:</h2>";
$hasUsers = $staffUser->hasUsers();
echo "Has users: " . ($hasUsers ? 'Yes' : 'No') . "<br>";

if (!$hasUsers) {
    echo "<h2>Creating test user:</h2>";
    $userData = [
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => 'password123',
        'role' => 'admin'
    ];
    
    $result = $staffUser->create($userData);
    if ($result) {
        echo "User created successfully!<br>";
        echo "User ID: " . $result['id'] . "<br>";
        echo "User Name: " . $result['name'] . "<br>";
        echo "User Email: " . $result['email'] . "<br>";
    } else {
        echo "Failed to create user<br>";
    }
}

echo "<h2>Testing authentication:</h2>";
$user = $staffUser->authenticate('admin@test.com', 'password123');
if ($user) {
    echo "Authentication successful!<br>";
    echo "User: " . $user['name'] . " (" . $user['email'] . ")<br>";
} else {
    echo "Authentication failed<br>";
}

echo "<h2>Session test:</h2>";
if (isset($_SESSION['user_id'])) {
    echo "User is logged in: " . $_SESSION['user_name'] . "<br>";
} else {
    echo "No user logged in<br>";
}
?> 