<?php

namespace App\Controllers;

class BaseController
{
    protected static $twig;
    protected $loginUrl = '/fresit/staff-login.php';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setTwig(\Twig\Environment $twig)
    {
        self::$twig = $twig;
    }

    /**
     * Render a template
     */
    protected function render($template, $data = [])
    {
        try {
            echo self::$twig->render($template, $data);
        } catch (\Twig\Error\Error $e) {
            http_response_code(500);
            echo "Error rendering template: " . htmlspecialchars($e->getMessage());
        }
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
        return (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) || 
               (isset($_SESSION['student_id']) && !empty($_SESSION['student_id']));
    }

    /**
     * Get current logged in user
     */
    protected function getCurrentUser()
    {
        // Check for staff user
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            return [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'type' => $_SESSION['user_role'] ?? 'staff'
            ];
        }
        
        // Check for student user
        if (isset($_SESSION['student_id']) && !empty($_SESSION['student_id'])) {
            return [
                'id' => $_SESSION['student_id'] ?? null,
                'name' => $_SESSION['student_name'] ?? null,
                'email' => $_SESSION['student_email'] ?? null,
                'type' => $_SESSION['student_role'] ?? 'student'
            ];
        }
        
        return null;
    }

    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect($this->loginUrl);
        }
    }

    /**
     * Generate CSRF token
     */
    protected function generateCsrfToken()
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}