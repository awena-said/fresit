<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\ArtClass;

class StudentController extends BaseController
{
    private $student = null;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getStudent()
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
                $this->sendRegistrationEmail($_POST['email'], $_POST['name'], $studentId);
                
                // Store student ID in session for success page
                $_SESSION['new_student_id'] = $studentId;
                
                // Redirect to success page
                header('Location: /royaldrawingschool/simple-registration-success.php');
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
                header('Location: /royaldrawingschool/student-dashboard.php');
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
            header('Location: /royaldrawingschool/student-login.php');
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
            header('Location: /royaldrawingschool/student-login.php');
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
            header('Location: /royaldrawingschool/student-login.php');
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
        header('Location: /royaldrawingschool/');
        exit;
    }

    /**
     * Show enhanced booking page with class selection
     */
    public function showBooking()
    {
        $classTypes = [
            ['id' => 'Foundation', 'name' => 'Foundation'],
            ['id' => 'Imagination', 'name' => 'Imagination'],
            ['id' => 'Watercolour', 'name' => 'Watercolour']
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
        if (empty($_POST['selected_time_slot'])) {
            $errors['selected_time_slot'] = 'Please select a time slot';
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
            // Save application to database
            $student = new \App\Models\Student();
            $applicationData = [
                'student_name' => $_POST['student_name'],
                'student_email' => $_POST['student_email'],
                'student_phone' => $_POST['student_phone'],
                'class_id' => $_POST['class_id'],
                'class_type' => $_POST['class_type'],
                'start_date' => $_POST['start_date'],
                'experience_level' => $_POST['experience_level'],
                'additional_notes' => $_POST['additional_notes'] ?? null
            ];
            
            $applicationId = $student->createApplication($applicationData);
            
            if ($applicationId) {
                // Send confirmation email
                $this->sendApplicationEmail($_POST['student_email'], $_POST['student_name'], $applicationId);
                
                // Redirect to success page
                header('Location: /royaldrawingschool/student-application-success.php?id=' . $applicationId);
                exit;
            } else {
                $errors['general'] = 'Failed to submit application. Please try again.';
            }
        }

        // Re-render with errors
        $classTypes = [
            ['id' => 'Foundation', 'name' => 'Foundation'],
            ['id' => 'Imagination', 'name' => 'Imagination'],
            ['id' => 'Watercolour', 'name' => 'Watercolour']
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

        $this->render('booking-success.html', [
            'title' => 'Application Submitted Successfully',
            'application_id' => $applicationId
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

        // Get available classes from the database
        $artClass = new ArtClass();
        $classes = $artClass->getAvailableClassesForBooking($classType, $startDate);
        
        header('Content-Type: application/json');
        echo json_encode($classes);
    }

    /**
     * Send registration confirmation email
     */
    private function sendRegistrationEmail($email, $name, $studentId)
    {
        $subject = "Welcome to royaldrawingschool Art School - Account Confirmation";
        $verificationLink = "http://localhost/royaldrawingschool/verify-email.php?token=" . $studentId;
        
        $emailContent = $this->generateRegistrationEmailContent($name, $verificationLink);
        
        // Save email as .eml file
        $this->saveEmailAsFile($email, $subject, $emailContent, $studentId);
        
        return true;
    }

    /**
     * Send application confirmation email
     */
    private function sendApplicationEmail($email, $name, $applicationId)
    {
        $subject = "Application Received - royaldrawingschool Art School";
        
        $emailContent = $this->generateApplicationEmailContent($name, $applicationId);
        
        // Save email as .eml file
        $this->saveEmailAsFile($email, $subject, $emailContent, $applicationId);
        
        return true;
    }

    /**
     * Generate registration email content
     */
    private function generateRegistrationEmailContent($name, $verificationLink)
    {
        return "
From: royaldrawingschool Art School <noreply@royaldrawingschool.com>
To: {$name} <{$name}@example.com>
Subject: Welcome to royaldrawingschool Art School - Account Confirmation
Date: " . date('r') . "
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Welcome to royaldrawingschool Art School</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .button { display: inline-block; padding: 12px 24px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>🎨 Welcome to royaldrawingschool Art School!</h1>
        </div>
        
        <div class='content'>
            <h2>Hello {$name},</h2>
            
            <p>Thank you for registering with royaldrawingschool Art School! We're excited to have you join our creative community.</p>
            
            <p>Your account has been successfully created. To complete your registration and start exploring our art classes, please verify your email address by clicking the button below:</p>
            
            <p style='text-align: center; margin: 30px 0;'>
                <a href='{$verificationLink}' class='button'>Verify Email Address</a>
            </p>
            
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <p style='word-break: break-all; color: #007bff;'>{$verificationLink}</p>
            
            <h3>What's Next?</h3>
            <ul>
                <li>Verify your email address</li>
                <li>Login to your account</li>
                <li>Browse our available art classes</li>
                <li>Apply for your first session!</li>
            </ul>
            
            <p>If you have any questions, please don't hesitate to contact us.</p>
            
            <p>Best regards,<br>
            The royaldrawingschool Art School Team</p>
        </div>
        
        <div class='footer'>
            <p>This email was sent to you because you registered for an account at royaldrawingschool Art School.</p>
            <p>If you didn't create this account, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
        ";
    }

    /**
     * Generate application email content
     */
    private function generateApplicationEmailContent($name, $applicationId)
    {
        return "
From: royaldrawingschool Art School <noreply@royaldrawingschool.com>
To: {$name} <{$name}@example.com>
Subject: Application Received - royaldrawingschool Art School
Date: " . date('r') . "
MIME-Version: 1.0
Content-Type: text/html; charset=UTF-8

<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Application Received</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .footer { padding: 20px; text-align: center; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>📝 Application Received</h1>
        </div>
        
        <div class='content'>
            <h2>Hello {$name},</h2>
            
            <p>Thank you for your application to royaldrawingschool Art School!</p>
            
            <p>We have received your application and it is currently being reviewed by our team.</p>
            
            <p><strong>Application ID:</strong> {$applicationId}</p>
            
            <h3>What happens next?</h3>
            <ul>
                <li>Our team will review your application within 2-3 business days</li>
                <li>You will receive an email with the status of your application</li>
                <li>If approved, you'll receive details about your class schedule</li>
            </ul>
            
            <p>If you have any questions about your application, please contact us.</p>
            
            <p>Best regards,<br>
            The royaldrawingschool Art School Team</p>
        </div>
        
        <div class='footer'>
            <p>This email confirms that we have received your application to royaldrawingschool Art School.</p>
        </div>
    </div>
</body>
</html>
        ";
    }

    /**
     * Save email as .eml file
     */
    private function saveEmailAsFile($email, $subject, $content, $id)
    {
        $emailsDir = __DIR__ . '/../../emails/';
        
        // Create emails directory if it doesn't exist
        if (!is_dir($emailsDir)) {
            mkdir($emailsDir, 0755, true);
        }
        
        // Determine if this is a registration or application email based on the subject
        $prefix = (strpos($subject, 'Application') !== false) ? 'application' : 'registration';
        $filename = $emailsDir . $prefix . '-' . $id . '.eml';
        file_put_contents($filename, $content);
        
        return $filename;
    }
} 