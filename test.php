<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP is working!<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "PHP version: " . phpversion() . "<br>";

// Test if files exist
echo "<h3>File Check:</h3>";
$files = [
    'vendor/autoload.php',
    'app/controllers/BaseController.php',
    'app/controllers/HomeController.php',
    'app/views/index.html'
];

foreach ($files as $file) {
    echo "$file: " . (file_exists($file) ? "✅ Exists" : "❌ Missing") . "<br>";
}

// Test autoloader
echo "<h3>Autoloader Test:</h3>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✅ Autoloader loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Autoloader error: " . $e->getMessage() . "<br>";
}

// Test Twig
echo "<h3>Twig Test:</h3>";
try {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
    $twig = new \Twig\Environment($loader, ['debug' => true]);
    echo "✅ Twig loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Twig error: " . $e->getMessage() . "<br>";
}
?> 