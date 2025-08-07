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
                // Check if user exists but password is wrong
                if ($this->staffUser->emailExists($_POST['email'])) {
                    $errors['general'] = 'Invalid password for this email address';
                } else {
                    $errors['general'] = 'No account found with this email address. You can create a new account below.';
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

    /**
     * Show staff dashboard
     */
    public function dashboard()
    {
        $this->requireAuth();
        
        // Sample data for demonstration - in a real app, this would come from the database
        $stats = [
            'total_applications' => 42,
            'pending_applications' => 15,
            'total_classes' => 8,
            'upcoming_classes' => 3
        ];
        
        $applications = [
            [
                'id' => 1,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@email.com',
                'phone' => '07123456789',
                'age' => 28,
                'class_type' => 'Watercolour',
                'preferred_date' => '2024-02-15',
                'status' => 'pending',
                'created_at' => '2024-01-20 10:30:00',
                'message' => 'I have some experience with drawing and would love to learn watercolor techniques.'
            ],
            [
                'id' => 2,
                'name' => 'Michael Brown',
                'email' => 'michael.brown@email.com',
                'phone' => '07987654321',
                'age' => 35,
                'class_type' => 'Foundation',
                'preferred_date' => '2024-02-20',
                'status' => 'accepted',
                'created_at' => '2024-01-18 14:15:00',
                'message' => 'Complete beginner looking to start my artistic journey.'
            ],
            [
                'id' => 3,
                'name' => 'Emma Wilson',
                'email' => 'emma.wilson@email.com',
                'phone' => '07555123456',
                'age' => 22,
                'class_type' => 'Imagination',
                'preferred_date' => '2024-02-25',
                'status' => 'pending',
                'created_at' => '2024-01-22 09:45:00',
                'message' => null
            ]
        ];
        
        $classes = [
            [
                'id' => 1,
                'name' => 'Introduction to Watercolour',
                'class_type' => 'Watercolour',
                'start_date' => '2024-02-15',
                'start_time' => '10:00',
                'tutor_name' => 'Agnes Mitchell',
                'room' => 'Studio A',
                'enrolled_count' => 12,
                'capacity' => 15,
                'status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Foundation Drawing Skills',
                'class_type' => 'Foundation',
                'start_date' => '2024-02-20',
                'start_time' => '14:00',
                'tutor_name' => 'Marcus Thompson',
                'room' => 'Studio B',
                'enrolled_count' => 8,
                'capacity' => 12,
                'status' => 'active'
            ]
        ];
        
        $upcoming_dates = [
            ['date' => '2024-02-15'],
            ['date' => '2024-02-20'],
            ['date' => '2024-02-25'],
            ['date' => '2024-03-01']
        ];
        
        $upcoming_classes = [
            [
                'id' => 1,
                'name' => 'Introduction to Watercolour',
                'start_date' => '2024-02-15',
                'start_time' => '10:00'
            ],
            [
                'id' => 2,
                'name' => 'Foundation Drawing Skills',
                'start_date' => '2024-02-20',
                'start_time' => '14:00'
            ]
        ];
        
        $tutors = [
            ['id' => 1, 'name' => 'Agnes Mitchell'],
            ['id' => 2, 'name' => 'Marcus Thompson'],
            ['id' => 3, 'name' => 'Akilah Johnson'],
            ['id' => 4, 'name' => 'Alexis Parker']
        ];
        
        $this->render('staff-dashboard.html', [
            'title' => 'Staff Dashboard',
            'user' => $this->getCurrentUser(),
            'stats' => $stats,
            'applications' => $applications,
            'classes' => $classes,
            'upcoming_dates' => $upcoming_dates,
            'upcoming_classes' => $upcoming_classes,
            'tutors' => $tutors
        ]);
    }

    /**
     * Handle account creation from login page
     */
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
     * Handle staff logout
     */
    public function logout()
    {
        $this->staffUser->logout();
        $this->redirect("{$this->baseUrl}/staff-login.php");
    }
}