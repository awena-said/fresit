<?php
// Twig initialization helper functions
function initTwig() {
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../app/views');
    $twig = new \Twig\Environment($loader, [
        'cache' => __DIR__ . '/../cache/twig',
        'debug' => true,
        'auto_reload' => true,
    ]);
    
    return $twig;
}

function renderTemplate($template, $data = []) {
    $twig = initTwig();
    return $twig->render($template, $data);
} 