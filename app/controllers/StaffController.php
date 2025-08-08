<?php

namespace App\Controllers;

use App\Models\StaffUser;

class StaffController extends BaseController
{
    private $staffUser;
    private $baseUrl = '/fresit';

    public function __construct()
    {
        parent::__construct();
        $this->staffUser = new StaffUser();
    }

    /**
     * Show staff login page
     */
    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        if (!$this->staffUser->hasUsers()) {
            $this->redirect("{$this->baseUrl}/staff-create-account.php");
            return;
        }

        $errors = $_SESSION['flash_errors'] ?? [];
        $formData = $_SESSION['flash_form_data'] ?? [];
        unset($_SESSION['flash_errors'], $_SESSION['flash_form_data']);

        $this->render('login.html', [
            'title' => 'Staff Login',
            'errors' => $errors,
            'form_data' => $formData,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Handle staff login
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect("{$this->baseUrl}/staff-login.php");
            return;
        }

        $errors = [];
        $formData = [
            'email' => trim($_POST['email'] ?? '')
        ];

        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors['general'] = 'Email and password are required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } else {
            $user = $this->staffUser->authenticate($_POST['email'], $_POST['password']);
            if ($user) {
                $this->staffUser->startSession($user);
                $this->redirect("{$this->baseUrl}/staff-dashboard.php");
                return;
            } else {
                if ($this->staffUser->emailExists($_POST['email'])) {
                    $errors['general'] = 'Invalid password for this email address';
                } else {
                    $errors['general'] = 'No account found with this email address. You can create a new account below';
                    $errors['show_create_account'] = true;
                }
            }
        }

        $_SESSION['flash_errors'] = $errors;
        $_SESSION['flash_form_data'] = $formData;
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }

    /**
     * Show create account page
     */
    public function showCreateAccount()
    {
        if ($this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        if ($this->staffUser->hasUsers()) {
            $this->redirect("{$this->baseUrl}/staff-login.php");
            return;
        }

        $errors = $_SESSION['flash_errors'] ?? [];
        $formData = $_SESSION['flash_form_data'] ?? [];
        unset($_SESSION['flash_errors'], $_SESSION['flash_form_data']);

        $this->render('create-account.html', [
            'title' => 'Create Staff Account',
            'errors' => $errors,
            'form_data' => $formData,
            'csrf_token' => $this->generateCsrfToken()
        ]);
    }

    /**
     * Handle account creation
     */
    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect("{$this->baseUrl}/staff-create-account.php");
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $formData = ['email' => $email, 'name' => $name];
        $errors = [];

        if (empty($email) || empty($password) || empty($name)) {
            $errors['general'] = 'All fields are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match';
        } else {
            $userData = ['name' => $name, 'email' => $email, 'password' => $password];
            $user = $this->staffUser->create($userData);
            if ($user) {
                $this->staffUser->startSession($user);
                $this->redirect("{$this->baseUrl}/staff-dashboard.php");
                return;
            } else {
                $errors['general'] = 'Failed to create account';
            }
        }

        $_SESSION['flash_errors'] = $errors;
        $_SESSION['flash_form_data'] = $formData;
        $this->redirect("{$this->baseUrl}/staff-create-account.php");
    }

    public function createAccountFromLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect("{$this->baseUrl}/staff-login.php");
            return;
        }

        $email = trim($_POST['create_email'] ?? '');
        $password = $_POST['create_password'] ?? '';
        $confirmPassword = $_POST['create_confirm_password'] ?? '';
        $name = trim($_POST['create_name'] ?? '');
        $formData = ['email' => $email, 'name' => $name];
        $errors = [];

        if (empty($email) || empty($password) || empty($name)) {
            $errors['create_general'] = 'All fields are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['create_email'] = 'Invalid email format';
        } elseif (strlen($password) < 8) {
            $errors['create_password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $confirmPassword) {
            $errors['create_confirm_password'] = 'Passwords do not match';
        } elseif ($this->staffUser->emailExists($email)) {
            $errors['create_email'] = 'An account with this email already exists';
        } else {
            $userData = ['name' => $name, 'email' => $email, 'password' => $password];
            $user = $this->staffUser->create($userData);
            if ($user) {
                $this->staffUser->startSession($user);
                $this->redirect("{$this->baseUrl}/staff-dashboard.php");
                return;
            } else {
                $errors['create_general'] = 'Failed to create account';
            }
        }

        $_SESSION['flash_errors'] = $errors;
        $_SESSION['flash_form_data'] = $formData;
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }

    /**
     * Show staff dashboard
     */
    public function dashboard()
    {
        $this->requireAuth();
        $this->render('staff-dashboard.html', [
            'title' => 'Staff Dashboard',
            'user' => $this->getCurrentUser()
        ]);
    }

    /**
     * Handle staff logout
     */
    public function logout()
    {
        $this->staffUser->logout();
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }
}