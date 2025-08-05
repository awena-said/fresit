<?php
require_once 'vendor/autoload.php';
require_once 'includes/twig_init.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

try {
    // Initialize Twig
    $loader = new FilesystemLoader('app/views');
    $twig = new Environment($loader, [
        'cache' => 'cache/twig',
        'debug' => true,
        'auto_reload' => true,
    ]);

    // Test data for reviews
    $reviews = [
        [
            'name' => 'Test User',
            'rating' => 5,
            'date' => 'Today',
            'text' => 'This is a test review to verify Twig is working.'
        ]
    ];

    // Render the reviews template
    echo $twig->render('reviews.html', ['reviews' => $reviews]);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 