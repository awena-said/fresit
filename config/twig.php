<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/views');
$twig = new \Twig\Environment($loader, [
    'debug' => true, 
]);

$twig->addFunction(new TwigFunction('path', function ($route){
    $routes = [
        'login' => '/login',
        'reservations' => '/reservations',
    ];
    return $routes[$route] ?? '/';
}));

return $twig;
?>
