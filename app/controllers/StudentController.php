<?php

namespace App\Controllers;

use App\Models\Student;

class StudentController extends BaseController
{
    private $student = null;

    public function __construct()
    {
        parent::__construct();
    }
    
    private function getStudent()
    {
        if ($this->student === null) {
            $this->student = new Student();
        }
        return $this->student;
    }

    /**
     * Show student registration page
     */
    public function showRegister()
    {
        $this->render('student/register.html', [
            'title' => 'Student Registration',
            'errors' => [],
            'form_data' => []
        ]);
    }

    /**
     * Handle student registration
     */
    public function register()
    {
        $errors = [];
        $form_data = $_POST;

        // Validate required fields
        if (empty($_POST['name'])) {
            $errors['name'] = 'Full name is required';
        }
        if (empty($_POST['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (empty($_POST['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($_POST['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        if (empty($_POST['phone'])) {
            $errors['phone'] = 'Phone number is required';
        }

        if (empty($errors)) {
            $studentData = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'phone' => $_POST['phone']
            ];

            $studentId = $this->getStudent()->createAccount($studentData);
            if ($studentId) {
                // Send confirmation email
                $this->sendRegistrationEmail($_POST['email'], $_POST['name']);
                
                // Redirect to login with success message
                header('Location: /fresit/student/login?registered=1');
                exit;
            } else {
                $errors['general'] = 'Registration failed. Email may already be in use.';
            }
        }

        $this->render('student/register.html', [
            'title' => 'Student Registration',
            'errors' => $errors,
            'form_data' => $form_data
        ]);
    }

    /**
     * Show student login page
     */
    public function showLogin()
    {
        $this->render('student/login.html', [
            'title' => 'Student Login',
            'errors' => [],
            'form_data' => []
        ]);
    }

    /**
     * Handle student login
     */
    public function login()
    {
        $errors = [];
        $form_data = $_POST;

        if (empty($_POST['email']) || empty($_POST['password'])) {
            $errors['general'] = 'Email and password are required';
        } else {
            $student = $this->getStudent()->authenticate($_POST['email'], $_POST['password']);
            if ($student) {
                $this->getStudent()->startSession($student);
                header('Location: /fresit/student/dashboard');
                exit;
            } else {
                $errors['general'] = 'Invalid email or password';
            }
        }

        $this->render('student/login.html', [
            'title' => 'Student Login',
            'errors' => $errors,
            'form_data' => $form_data
        ]);
    }

    /**
     * Show student dashboard
     */
    public function dashboard()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /fresit/student/login');
            exit;
        }

        $student = $this->getStudent()->getById($_SESSION['student_id']);
        $applications = $this->getStudent()->getApplications($_SESSION['student_id']);

        $this->render('student/dashboard.html', [
            'title' => 'Student Dashboard',
            'student' => $student,
            'applications' => $applications
        ]);
    }

    /**
     * Show change password page
     */
    public function showChangePassword()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /fresit/student/login');
            exit;
        }

        $this->render('student/change-password.html', [
            'title' => 'Change Password',
            'errors' => [],
            'success' => false
        ]);
    }

    /**
     * Handle password change
     */
    public function changePassword()
    {
        if (!isset($_SESSION['student_id'])) {
            header('Location: /fresit/student/login');
            exit;
        }

        $errors = [];
        $success = false;

        if (empty($_POST['current_password']) || empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
            $errors['general'] = 'All fields are required';
        } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
            $errors['general'] = 'New passwords do not match';
        } elseif (strlen($_POST['new_password']) < 6) {
            $errors['general'] = 'New password must be at least 6 characters';
        } else {
            $student = $this->getStudent()->getById($_SESSION['student_id']);
            if (password_verify($_POST['current_password'], $student['password'])) {
                if ($this->getStudent()->updatePassword($_SESSION['student_id'], $_POST['new_password'])) {
                    $success = true;
                } else {
                    $errors['general'] = 'Failed to update password';
                }
            } else {
                $errors['general'] = 'Current password is incorrect';
            }
        }

        $this->render('student/change-password.html', [
            'title' => 'Change Password',
            'errors' => $errors,
            'success' => $success
        ]);
    }

    /**
     * Handle student logout
     */
    public function logout()
    {
        $this->getStudent()->logout();
        header('Location: /fresit/');
        exit;
    }

    /**
     * Show enhanced booking page with class selection
     */
    public function showBooking()
    {
        $classTypes = [
            ['id' => 'foundation', 'name' => 'Foundation'],
            ['id' => 'imagination', 'name' => 'Imagination'],
            ['id' => 'watercolour', 'name' => 'Watercolour']
        ];

        $this->render('student/booking.html', [
            'title' => 'Apply for Classes',
            'class_types' => $classTypes,
            'errors' => [],
            'form_data' => []
        ]);
    }

    /**
     * Handle class application submission
     */
    public function submitApplication()
    {
        $errors = [];
        $form_data = $_POST;

        // Validate required fields
        if (empty($_POST['class_type'])) {
            $errors['class_type'] = 'Please select a class type';
        }
        if (empty($_POST['start_date'])) {
            $errors['start_date'] = 'Please select a start date';
        }
        if (empty($_POST['class_id'])) {
            $errors['class_id'] = 'Please select a class';
        }
        if (empty($_POST['student_name'])) {
            $errors['student_name'] = 'Full name is required';
        }
        if (empty($_POST['student_email'])) {
            $errors['student_email'] = 'Email is required';
        } elseif (!filter_var($_POST['student_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['student_email'] = 'Please enter a valid email address';
        }
        if (empty($_POST['student_phone'])) {
            $errors['student_phone'] = 'Phone number is required';
        }

        if (empty($errors)) {
            // Application submission logic would go here
            $this->sendApplicationEmail($_POST['student_email'], $_POST['student_name'], 'APP-001');
            
            header('Location: /fresit/student/application-success?id=APP-001');
            exit;
        }

        // Re-render with errors
        $classTypes = [
            ['id' => 'foundation', 'name' => 'Foundation'],
            ['id' => 'imagination', 'name' => 'Imagination'],
            ['id' => 'watercolour', 'name' => 'Watercolour']
        ];

        $this->render('student/booking.html', [
            'title' => 'Apply for Classes',
            'class_types' => $classTypes,
            'errors' => $errors,
            'form_data' => $form_data
        ]);
    }

    /**
     * Show application success page
     */
    public function applicationSuccess()
    {
        $applicationId = $_GET['id'] ?? 'APP-001';

        $this->render('student/booking.html', [
            'title' => 'Application Submitted Successfully',
            'application' => null,
            'success' => true
        ]);
    }

    /**
     * API endpoint to get available classes
     */
    public function getAvailableClasses()
    {
        $classType = $_GET['type'] ?? '';
        $startDate = $_GET['start_date'] ?? '';
        
        if (empty($classType) || empty($startDate)) {
            http_response_code(400);
            echo json_encode(['error' => 'Class type and start date are required']);
            return;
        }

        // Return empty array since no classes are available
        $classes = [];
        
        header('Content-Type: application/json');
        echo json_encode($classes);
    }

    /**
     * Send registration confirmation email
     */
    private function sendRegistrationEmail($email, $name)
    {
        $subject = 'Welcome to Royal Drawing School - Registration Confirmed';
        $message = "Dear $name,\n\n";
        $message .= "Thank you for registering with Royal Drawing School!\n\n";
        $message .= "Your account has been created successfully. You can now:\n";
        $message .= "- Login to your account\n";
        $message .= "- Apply for classes\n";
        $message .= "- View your applications\n\n";
        $message .= "Best regards,\nRoyal Drawing School Team";
        
        // In a real application, you would use a proper email library
        error_log("Registration email sent to: $email");
    }

    /**
     * Send application confirmation email
     */
    private function sendApplicationEmail($email, $name, $applicationId)
    {
        $subject = 'Application Received - Royal Drawing School';
        $message = "Dear $name,\n\n";
        $message .= "Thank you for your application to Royal Drawing School!\n\n";
        $message .= "Your application (ID: $applicationId) has been received and is being reviewed.\n";
        $message .= "We will contact you within 2-3 business days with further details.\n\n";
        $message .= "Best regards,\nRoyal Drawing School Team";
        
        // In a real application, you would use a proper email library
        error_log("Application email sent to: $email");
    }
} 