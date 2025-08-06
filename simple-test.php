<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Simple Test</h1>";
echo "<p>Server is working!</p>";

// Get the route from the URI
$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "<p>Original URI: " . $route . "</p>";

// Remove the base path from the URI if it exists
$basePath = '/fresit';
if (strpos($route, $basePath) === 0) {
    $route = substr($route, strlen($basePath));
}
echo "<p>After base path removal: " . $route . "</p>";

// Ensure route starts with /
if (empty($route)) {
    $route = '/';
}
echo "<p>Final route: " . $route . "</p>";

// Test some routes
switch ($route) {
    case '/':
        echo "<h2>Home Page</h2>";
        echo "<p>This is the home page route.</p>";
        break;
    case '/reviews':
        echo "<h2>Reviews Page</h2>";
        echo "<p>This is the reviews page route.</p>";
        break;
    case '/booking':
        echo "<h2>Booking Page</h2>";
        echo "<p>This is the booking page route.</p>";
        break;
    case '/staff/login':
        echo "<h2>Staff Login Page</h2>";
        echo "<p>This is the staff login page route.</p>";
        break;
    default:
        echo "<h2>404 - Page Not Found</h2>";
        echo "<p>Route not found: " . $route . "</p>";
        break;
}
?> 