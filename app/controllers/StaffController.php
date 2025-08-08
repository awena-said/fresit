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
        if (!$this->checkAccessAndCsrf('staff-login.php')) {
            return;
        }

        $errors = [];
        $formData = ['email' => trim($_POST['email'] ?? '')];

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

        $this->setFlashData($errors, $formData);
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
        if (!$this->checkAccessAndCsrf('staff-create-account.php')) {
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $name = trim($_POST['name'] ?? '');

        list($success, $errors, $formData) = $this->validateAndCreateUser($email, $password, $confirmPassword, $name);

        if ($success) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        $this->setFlashData($errors, $formData);
        $this->redirect("{$this->baseUrl}/staff-create-account.php");
    }

    /**
     * Handle account creation from login page
     */
    public function createAccountFromLogin()
    {
        if (!$this->checkAccessAndCsrf('staff-login.php')) {
            return;
        }

        $email = trim($_POST['create_email'] ?? '');
        $password = $_POST['create_password'] ?? '';
        $confirmPassword = $_POST['create_confirm_password'] ?? '';
        $name = trim($_POST['create_name'] ?? '');

        list($success, $errors, $formData) = $this->validateAndCreateUser($email, $password, $confirmPassword, $name, true);

        if ($success) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return;
        }

        $this->setFlashData($errors, $formData);
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }

    /**
     * Show staff dashboard
     */
    public function dashboard()
    {
        $this->requireAuth();
        
        // Generate random upcoming dates for the next month
        $upcomingDates = $this->generateUpcomingDates();
        
        $this->render('staff-dashboard.html', [
            'title' => 'Staff Dashboard',
            'user' => $this->getCurrentUser(),
            'upcoming_dates' => $upcomingDates,
            'stats' => [
                'total_applications' => 0,
                'pending_applications' => 0,
                'total_classes' => 0,
                'upcoming_classes' => 0
            ],
            'applications' => [],
            'classes' => [],
            'upcoming_classes' => []
        ]);
    }
    
    /**
     * Generate random upcoming dates for the next month
     */
    private function generateUpcomingDates()
    {
        $dates = [];
        $currentDate = new DateTime();
        $nextMonth = clone $currentDate;
        $nextMonth->modify('+1 month');
        
        // Generate 8-12 random dates in the next month
        $numDates = rand(8, 12);
        
        for ($i = 0; $i < $numDates; $i++) {
            // Random day in the next month (1-28 to avoid month boundary issues)
            $randomDay = rand(1, 28);
            $date = new DateTime();
            $date->setDate($nextMonth->format('Y'), $nextMonth->format('m'), $randomDay);
            
            // Only add weekdays (Monday = 1, Friday = 5)
            if ($date->format('N') >= 1 && $date->format('N') <= 5) {
                $dates[] = [
                    'date' => $date->format('Y-m-d'),
                    'day_name' => $date->format('l'),
                    'formatted_date' => $date->format('F d, Y')
                ];
            }
        }
        
        // Sort dates chronologically
        usort($dates, function($a, $b) {
            return strcmp($a['date'], $b['date']);
        });
        
        // Remove duplicates and limit to 10 dates
        $uniqueDates = [];
        $seen = [];
        foreach ($dates as $date) {
            if (!in_array($date['date'], $seen)) {
                $uniqueDates[] = $date;
                $seen[] = $date['date'];
            }
            if (count($uniqueDates) >= 10) {
                break;
            }
        }
        
        return $uniqueDates;
    }

    /**
     * Handle staff logout
     */
    public function logout()
    {
        $this->staffUser->logout();
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }

    /**
     * Check if user is logged in and validate CSRF token, redirecting if either fails
     *
     * @param string $redirectPath
     * @return bool True if checks pass, false if redirect occurs
     */
    private function checkAccessAndCsrf($redirectPath)
    {
        if ($this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-dashboard.php");
            return false;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->validateCsrfToken($_POST['csrf_token'] ?? '')) {
            $this->redirect("{$this->baseUrl}/{$redirectPath}");
            return false;
        }

        return true;
    }

    /**
     * Set flash errors and form data in session
     *
     * @param array $errors
     * @param array $formData
     */
    private function setFlashData(array $errors, array $formData)
    {
        $_SESSION['flash_errors'] = $errors;
        $_SESSION['flash_form_data'] = $formData;
    }

    /**
     * Ensure user is authenticated and exists in database
     */
    protected function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect("{$this->baseUrl}/staff-login.php");
            return;
        }

        $user = $this->getCurrentUser();
        if (!$user || !$this->staffUser->emailExists($user['email'])) {
            error_log("Invalid session: User {$user['email']} not found in database");
            $this->staffUser->logout();
            $this->redirect("{$this->baseUrl}/staff-login.php");
        }
    }

    /**
     * Validate and create a user account
     *
     * @param string $email
     * @param string $password
     * @param string $confirmPassword
     * @param string $name
     * @param bool $checkEmailExists
     * @return array [bool, array, array] Returns [success, errors, formData]
     */
    private function validateAndCreateUser($email, $password, $confirmPassword, $name, $checkEmailExists = false)
    {
        $errors = [];
        $formData = ['email' => $email, 'name' => $name];

        if (empty($email) || empty($password) || empty($name)) {
            $errors[$checkEmailExists ? 'create_general' : 'general'] = 'All fields are required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[$checkEmailExists ? 'create_email' : 'email'] = 'Invalid email format';
        } elseif (strlen($password) < 8) {
            $errors[$checkEmailExists ? 'create_password' : 'password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $confirmPassword) {
            $errors[$checkEmailExists ? 'create_confirm_password' : 'confirm_password'] = 'Passwords do not match';
        } elseif ($checkEmailExists && $this->staffUser->emailExists($email)) {
            $errors['create_email'] = 'An account with this email already exists';
        } else {
            $userData = ['name' => $name, 'email' => $email, 'password' => $password];
            $user = $this->staffUser->create($userData);
            if ($user) {
                $this->staffUser->startSession($user);
                return [true, [], []];
            } else {
                $errors[$checkEmailExists ? 'create_general' : 'general'] = 'Failed to create account';
                error_log("Failed to create account for email: $email");
            }
        }

        return [false, $errors, $formData];
    }
}