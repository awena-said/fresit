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
        
        // Get upcoming available dates
        $upcomingDates = $this->getUpcomingAvailableDates();
        
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
     * Get upcoming available dates for the next 3 months
     */
    private function getUpcomingAvailableDates()
    {
        $dates = [
            // January 2025
            ['date' => '2025-01-06', 'day_name' => 'Monday', 'formatted_date' => 'January 6, 2025'],
            ['date' => '2025-01-07', 'day_name' => 'Tuesday', 'formatted_date' => 'January 7, 2025'],
            ['date' => '2025-01-08', 'day_name' => 'Wednesday', 'formatted_date' => 'January 8, 2025'],
            ['date' => '2025-01-09', 'day_name' => 'Thursday', 'formatted_date' => 'January 9, 2025'],
            ['date' => '2025-01-10', 'day_name' => 'Friday', 'formatted_date' => 'January 10, 2025'],
            ['date' => '2025-01-13', 'day_name' => 'Monday', 'formatted_date' => 'January 13, 2025'],
            ['date' => '2025-01-14', 'day_name' => 'Tuesday', 'formatted_date' => 'January 14, 2025'],
            ['date' => '2025-01-15', 'day_name' => 'Wednesday', 'formatted_date' => 'January 15, 2025'],
            ['date' => '2025-01-16', 'day_name' => 'Thursday', 'formatted_date' => 'January 16, 2025'],
            ['date' => '2025-01-17', 'day_name' => 'Friday', 'formatted_date' => 'January 17, 2025'],
            ['date' => '2025-01-20', 'day_name' => 'Monday', 'formatted_date' => 'January 20, 2025'],
            ['date' => '2025-01-21', 'day_name' => 'Tuesday', 'formatted_date' => 'January 21, 2025'],
            ['date' => '2025-01-22', 'day_name' => 'Wednesday', 'formatted_date' => 'January 22, 2025'],
            ['date' => '2025-01-23', 'day_name' => 'Thursday', 'formatted_date' => 'January 23, 2025'],
            ['date' => '2025-01-24', 'day_name' => 'Friday', 'formatted_date' => 'January 24, 2025'],
            ['date' => '2025-01-27', 'day_name' => 'Monday', 'formatted_date' => 'January 27, 2025'],
            ['date' => '2025-01-28', 'day_name' => 'Tuesday', 'formatted_date' => 'January 28, 2025'],
            ['date' => '2025-01-29', 'day_name' => 'Wednesday', 'formatted_date' => 'January 29, 2025'],
            ['date' => '2025-01-30', 'day_name' => 'Thursday', 'formatted_date' => 'January 30, 2025'],
            ['date' => '2025-01-31', 'day_name' => 'Friday', 'formatted_date' => 'January 31, 2025'],
            
            // February 2025
            ['date' => '2025-02-03', 'day_name' => 'Monday', 'formatted_date' => 'February 3, 2025'],
            ['date' => '2025-02-04', 'day_name' => 'Tuesday', 'formatted_date' => 'February 4, 2025'],
            ['date' => '2025-02-05', 'day_name' => 'Wednesday', 'formatted_date' => 'February 5, 2025'],
            ['date' => '2025-02-06', 'day_name' => 'Thursday', 'formatted_date' => 'February 6, 2025'],
            ['date' => '2025-02-07', 'day_name' => 'Friday', 'formatted_date' => 'February 7, 2025'],
            ['date' => '2025-02-10', 'day_name' => 'Monday', 'formatted_date' => 'February 10, 2025'],
            ['date' => '2025-02-11', 'day_name' => 'Tuesday', 'formatted_date' => 'February 11, 2025'],
            ['date' => '2025-02-12', 'day_name' => 'Wednesday', 'formatted_date' => 'February 12, 2025'],
            ['date' => '2025-02-13', 'day_name' => 'Thursday', 'formatted_date' => 'February 13, 2025'],
            ['date' => '2025-02-14', 'day_name' => 'Friday', 'formatted_date' => 'February 14, 2025'],
            ['date' => '2025-02-17', 'day_name' => 'Monday', 'formatted_date' => 'February 17, 2025'],
            ['date' => '2025-02-18', 'day_name' => 'Tuesday', 'formatted_date' => 'February 18, 2025'],
            ['date' => '2025-02-19', 'day_name' => 'Wednesday', 'formatted_date' => 'February 19, 2025'],
            ['date' => '2025-02-20', 'day_name' => 'Thursday', 'formatted_date' => 'February 20, 2025'],
            ['date' => '2025-02-21', 'day_name' => 'Friday', 'formatted_date' => 'February 21, 2025'],
            ['date' => '2025-02-24', 'day_name' => 'Monday', 'formatted_date' => 'February 24, 2025'],
            ['date' => '2025-02-25', 'day_name' => 'Tuesday', 'formatted_date' => 'February 25, 2025'],
            ['date' => '2025-02-26', 'day_name' => 'Wednesday', 'formatted_date' => 'February 26, 2025'],
            ['date' => '2025-02-27', 'day_name' => 'Thursday', 'formatted_date' => 'February 27, 2025'],
            ['date' => '2025-02-28', 'day_name' => 'Friday', 'formatted_date' => 'February 28, 2025'],
            
            // March 2025
            ['date' => '2025-03-03', 'day_name' => 'Monday', 'formatted_date' => 'March 3, 2025'],
            ['date' => '2025-03-04', 'day_name' => 'Tuesday', 'formatted_date' => 'March 4, 2025'],
            ['date' => '2025-03-05', 'day_name' => 'Wednesday', 'formatted_date' => 'March 5, 2025'],
            ['date' => '2025-03-06', 'day_name' => 'Thursday', 'formatted_date' => 'March 6, 2025'],
            ['date' => '2025-03-07', 'day_name' => 'Friday', 'formatted_date' => 'March 7, 2025'],
            ['date' => '2025-03-10', 'day_name' => 'Monday', 'formatted_date' => 'March 10, 2025'],
            ['date' => '2025-03-11', 'day_name' => 'Tuesday', 'formatted_date' => 'March 11, 2025'],
            ['date' => '2025-03-12', 'day_name' => 'Wednesday', 'formatted_date' => 'March 12, 2025'],
            ['date' => '2025-03-13', 'day_name' => 'Thursday', 'formatted_date' => 'March 13, 2025'],
            ['date' => '2025-03-14', 'day_name' => 'Friday', 'formatted_date' => 'March 14, 2025'],
            ['date' => '2025-03-17', 'day_name' => 'Monday', 'formatted_date' => 'March 17, 2025'],
            ['date' => '2025-03-18', 'day_name' => 'Tuesday', 'formatted_date' => 'March 18, 2025'],
            ['date' => '2025-03-19', 'day_name' => 'Wednesday', 'formatted_date' => 'March 19, 2025'],
            ['date' => '2025-03-20', 'day_name' => 'Thursday', 'formatted_date' => 'March 20, 2025'],
            ['date' => '2025-03-21', 'day_name' => 'Friday', 'formatted_date' => 'March 21, 2025'],
            ['date' => '2025-03-24', 'day_name' => 'Monday', 'formatted_date' => 'March 24, 2025'],
            ['date' => '2025-03-25', 'day_name' => 'Tuesday', 'formatted_date' => 'March 25, 2025'],
            ['date' => '2025-03-26', 'day_name' => 'Wednesday', 'formatted_date' => 'March 26, 2025'],
            ['date' => '2025-03-27', 'day_name' => 'Thursday', 'formatted_date' => 'March 27, 2025'],
            ['date' => '2025-03-28', 'day_name' => 'Friday', 'formatted_date' => 'March 28, 2025'],
            ['date' => '2025-03-31', 'day_name' => 'Monday', 'formatted_date' => 'March 31, 2025']
        ];
        
        return $dates;
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