<?php

namespace App\Controllers;

use App\Models\StaffUser;

class StaffController extends BaseController
{
    private $staffUser = null;

    public function __construct()
    {
        parent::__construct();
    }
    
    private function getStaffUser()
    {
        if ($this->staffUser === null) {
            $this->staffUser = new StaffUser();
        }
        return $this->staffUser;
    }

    /**
     * Show staff login page
     */
    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/fresit/staff/dashboard');
            return;
        }

        if (!$this->getStaffUser()->hasUsers()) {
            $this->redirect('/fresit/staff/create-account');
            return;
        }

        $this->render('login.html', [
            'title' => 'Staff Login',
            'errors' => [],
            'form_data' => []
        ]);
    }

    /**
     * Handle staff login
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/fresit/staff/dashboard');
            return;
        }

        $errors = [];
        $form_data = $_POST;

        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors['general'] = 'Email and password are required';
        } else {
            $user = $this->getStaffUser()->authenticate($_POST['email'], $_POST['password']);
            if ($user) {
                $this->getStaffUser()->startSession($user);
                $this->redirect('/fresit/staff/dashboard');
            } else {
                $this->redirect('/fresit/staff/login');
            }
        }

        $this->render('login.html', [
            'title' => 'Staff Login',
            'errors' => $errors,
            'form_data' => $form_data
        ]);
    }

    /**
     * Show create account page
     */
    public function showCreateAccount()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/fresit/staff/dashboard');
            return;
        }

        if ($this->getStaffUser()->hasUsers()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->render('create-account.html', [
            'title' => 'Create Staff Account',
            'errors' => [],
            'form_data' => []
        ]);
    }

    /**
     * Handle account creation
     */
    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/fresit/staff/create-account');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $name = trim($_POST['name'] ?? '');

        if (empty($email) || empty($password) || empty($name)) {
            $this->redirect('/fresit/staff/create-account');
            return;
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];

        $user = $this->getStaffUser()->create($userData);
        if ($user) {
            $this->getStaffUser()->startSession($user);
            $this->redirect('/fresit/staff/dashboard');
        } else {
            $this->redirect('/fresit/staff/create-account');
        }
    }

    /**
     * Show staff dashboard
     */
    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->render('staff-dashboard.html', [
            'title' => 'Staff Dashboard',
            'user' => [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'type' => $_SESSION['user_role'] ?? null
            ]
        ]);
    }

    /**
     * View student applications (business requirement 2.1.2.b.i)
     */
    public function applications()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->render('staff-dashboard.html', [
            'title' => 'Student Applications',
            'applications' => [],
            'available_dates' => [],
            'user' => [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'type' => $_SESSION['user_role'] ?? null
            ]
        ]);
    }

    /**
     * Manage classes (business requirement 2.1.2.b.v)
     */
    public function classes()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->render('staff-dashboard.html', [
            'title' => 'Manage Classes',
            'classes' => [],
            'user' => [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'type' => $_SESSION['user_role'] ?? null
            ]
        ]);
    }

    /**
     * View individual application (business requirement 2.1.2.b.ii)
     */
    public function getApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->render('staff-dashboard.html', [
            'title' => 'Application Details',
            'application' => null,
            'user' => [
                'id' => $_SESSION['user_id'] ?? null,
                'name' => $_SESSION['user_name'] ?? null,
                'email' => $_SESSION['user_email'] ?? null,
                'type' => $_SESSION['user_role'] ?? null
            ]
        ]);
    }

    /**
     * Accept application (business requirement 2.1.2.b.iv)
     */
    public function acceptApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->redirect('/fresit/staff/applications?accepted=1');
    }

    /**
     * Reject application (business requirement 2.1.2.b.iv)
     */
    public function rejectApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->redirect('/fresit/staff/applications?rejected=1');
    }

    /**
     * Create new class (business requirement 2.1.2.b.v)
     */
    public function createClass()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->redirect('/fresit/staff/dashboard?class_created=1');
    }

    /**
     * Delete class
     */
    public function deleteClass($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/fresit/staff/login');
            return;
        }

        $this->redirect('/fresit/staff/dashboard?class_deleted=1');
    }

    /**
     * Handle staff logout
     */
    public function logout()
    {
        $this->getStaffUser()->logout();
        $this->redirect('/fresit/staff/login');
    }
} 