<?php

namespace App\Controllers;

class BaseController
{
    protected $twig;

    public function __construct()
    {
        global $twig;
        $this->twig = $twig;
    }

    /**
     * Render a template
     */
    protected function render($template, $data = [])
    {
        echo $this->twig->render($template, $data);
    }

    /**
     * Redirect to a URL
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    /**
     * Get current logged in user
     */
    protected function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'name' => $_SESSION['user_name'] ?? null,
            'email' => $_SESSION['user_email'] ?? null
        ];
    }

    /**
     * Require authentication
     */
            protected function requireAuth()
        {
            if (!$this->isLoggedIn()) {
                $this->redirect('/fresit/staff/login');
            }
        }
} 