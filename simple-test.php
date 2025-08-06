<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Step 1: Basic PHP works<br>";

// Test autoloader
try {
    require_once __DIR__ . '/vendor/autoload.php';
    echo "Step 2: Autoloader works<br>";
} catch (Exception $e) {
    echo "Step 2: Autoloader failed - " . $e->getMessage() . "<br>";
    exit;
}

// Test Twig
try {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/app/views');
    $twig = new \Twig\Environment($loader, ['debug' => true]);
    echo "Step 3: Twig works<br>";
} catch (Exception $e) {
    echo "Step 3: Twig failed - " . $e->getMessage() . "<br>";
    exit;
}

// Test BaseController
try {
    require_once __DIR__ . '/app/controllers/BaseController.php';
    echo "Step 4: BaseController loads<br>";
} catch (Exception $e) {
    echo "Step 4: BaseController failed - " . $e->getMessage() . "<br>";
    exit;
}

// Test HomeController
try {
    require_once __DIR__ . '/app/controllers/HomeController.php';
    echo "Step 5: HomeController loads<br>";
} catch (Exception $e) {
    echo "Step 5: HomeController failed - " . $e->getMessage() . "<br>";
    exit;
}

// Test creating HomeController instance
try {
    $controller = new \App\Controllers\HomeController();
    echo "Step 6: HomeController instance created<br>";
} catch (Exception $e) {
    echo "Step 6: HomeController instance failed - " . $e->getMessage() . "<br>";
    exit;
}

echo "All tests passed! The issue might be in the routing or template rendering.<br>";
?> 