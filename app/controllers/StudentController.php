<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Application;

class StudentController extends BaseController
{
    private $student = null;
    private $application = null;

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

    private function getApplication()
    {
        if ($this->application === null) {
            $this->application = new Application();
        }
        return $this->application;
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
     * Show forgot password page
     */
    public function showForgotPassword()
    {
        $this->render('student/forgot-password.html', [
            'title' => 'Forgot Password',
            'errors' => [],
            'success' => false
        ]);
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword()
    {
        $errors = [];
        $success = false;

        if (empty($_POST['email'])) {
            $errors['email'] = 'Email address is required';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        } else {
            // Check if email exists in database
            $student = $this->getStudent()->getByEmail($_POST['email']);
            if ($student) {
                // Generate reset token and send email
                $resetToken = bin2hex(random_bytes(32));
                $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                if ($this->getStudent()->saveResetToken($_POST['email'], $resetToken, $resetExpiry)) {
                    $this->sendPasswordResetEmail($_POST['email'], $student['name'], $resetToken);
                    $success = true;
                } else {
                    $errors['general'] = 'Failed to process reset request. Please try again.';
                }
            } else {
                // Don't reveal if email exists or not for security
                $success = true;
            }
        }

        $this->render('student/forgot-password.html', [
            'title' => 'Forgot Password',
            'errors' => $errors,
            'success' => $success
        ]);
    }

    /**
     * Show reset password page
     */
    public function showResetPassword()
    {
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            header('Location: /fresit/student/login');
            exit;
        }

        // Verify token is valid
        $student = $this->getStudent()->getByResetToken($token);
        if (!$student) {
            $this->render('student/reset-password.html', [
                'title' => 'Invalid Reset Link',
                'errors' => ['general' => 'This password reset link is invalid or has expired.'],
                'success' => false,
                'token' => $token
            ]);
            return;
        }

        $this->render('student/reset-password.html', [
            'title' => 'Reset Password',
            'errors' => [],
            'success' => false,
            'token' => $token
        ]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword()
    {
        $token = $_POST['token'] ?? '';
        $errors = [];
        $success = false;

        if (empty($token)) {
            header('Location: /fresit/student/login');
            exit;
        }

        // Verify token is valid
        $student = $this->getStudent()->getByResetToken($token);
        if (!$student) {
            $errors['general'] = 'This password reset link is invalid or has expired.';
        } else {
            // Validate new password
            if (empty($_POST['new_password'])) {
                $errors['new_password'] = 'New password is required';
            } elseif (strlen($_POST['new_password']) < 6) {
                $errors['new_password'] = 'Password must be at least 6 characters';
            }

            if (empty($_POST['confirm_password'])) {
                $errors['confirm_password'] = 'Please confirm your password';
            } elseif ($_POST['new_password'] !== $_POST['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match';
            }

            if (empty($errors)) {
                // Update password and clear reset token
                if ($this->getStudent()->updatePassword($student['id'], $_POST['new_password'])) {
                    $this->getStudent()->clearResetToken($student['email']);
                    $success = true;
                } else {
                    $errors['general'] = 'Failed to reset password. Please try again.';
                }
            }
        }

        $this->render('student/reset-password.html', [
            'title' => 'Reset Password',
            'errors' => $errors,
            'success' => $success,
            'token' => $token
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
     * Handle application submission
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
            // Prepare application data
            $applicationData = [
                'class_id' => $_POST['class_id'],
                'student_id' => $_SESSION['student_id'] ?? null,
                'student_name' => $_POST['student_name'],
                'student_email' => $_POST['student_email'],
                'student_phone' => $_POST['student_phone'],
                'experience_level' => $_POST['experience_level'] ?? 'beginner',
                'additional_notes' => $_POST['additional_notes'] ?? ''
            ];
            
            // Save application to database
            $applicationId = $this->getApplication()->create($applicationData);
            
            if ($applicationId) {
                // Send confirmation email
                $this->sendApplicationEmail($_POST['student_email'], $_POST['student_name'], $applicationId);
                
                header('Location: /fresit/booking-success?id=' . $applicationId);
                exit;
            } else {
                $errors['general'] = 'Failed to submit application. Please try again.';
            }
        }

        // Re-render with errors
        $classTypes = [
            ['id' => 'foundation', 'name' => 'Foundation'],
            ['id' => 'imagination', 'name' => 'Imagination'],
            ['id' => 'watercolour', 'name' => 'Watercolour']
        ];

        $this->render('booking.html', [
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

        // Get day of week for the selected date
        $dayOfWeek = date('l', strtotime($startDate));
        
        // Debug logging
        error_log("API Debug: classType=$classType, startDate=$startDate, dayOfWeek=$dayOfWeek");
        
        // Define sample classes with realistic data
        $sampleClasses = [
            'foundation' => [
                [
                    'id' => 'foundation_001',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Agnes',
                    'day_of_week' => 'Monday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 5,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_002',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Marcus',
                    'day_of_week' => 'Tuesday',
                    'start_time' => '19:00',
                    'end_time' => '21:00',
                    'available_slots' => 3,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_003',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Nikki',
                    'day_of_week' => 'Wednesday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 7,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_004',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Rossen',
                    'day_of_week' => 'Thursday',
                    'start_time' => '21:00',
                    'end_time' => '23:00',
                    'available_slots' => 4,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_005',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Tara',
                    'day_of_week' => 'Friday',
                    'start_time' => '19:00',
                    'end_time' => '21:00',
                    'available_slots' => 6,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_006',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Alexis',
                    'day_of_week' => 'Saturday',
                    'start_time' => '09:00',
                    'end_time' => '11:00',
                    'available_slots' => 8,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_007',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Akilah',
                    'day_of_week' => 'Saturday',
                    'start_time' => '13:00',
                    'end_time' => '15:00',
                    'available_slots' => 5,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ],
                [
                    'id' => 'foundation_008',
                    'name' => 'Foundation Drawing Fundamentals',
                    'tutor_name' => 'Agnes',
                    'day_of_week' => 'Sunday',
                    'start_time' => '11:00',
                    'end_time' => '13:00',
                    'available_slots' => 4,
                    'description' => 'Learn the basics of drawing with pencil and charcoal'
                ]
            ],
            'imagination' => [
                [
                    'id' => 'imagination_001',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Akilah',
                    'day_of_week' => 'Monday',
                    'start_time' => '19:00',
                    'end_time' => '21:00',
                    'available_slots' => 6,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_002',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Alexis',
                    'day_of_week' => 'Tuesday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 4,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_003',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Marcus',
                    'day_of_week' => 'Wednesday',
                    'start_time' => '21:00',
                    'end_time' => '23:00',
                    'available_slots' => 3,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_004',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Nikki',
                    'day_of_week' => 'Thursday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 7,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_005',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Rossen',
                    'day_of_week' => 'Friday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 5,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_006',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Tara',
                    'day_of_week' => 'Saturday',
                    'start_time' => '11:00',
                    'end_time' => '13:00',
                    'available_slots' => 6,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_007',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Agnes',
                    'day_of_week' => 'Saturday',
                    'start_time' => '15:00',
                    'end_time' => '17:00',
                    'available_slots' => 4,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ],
                [
                    'id' => 'imagination_008',
                    'name' => 'Creative Imagination Workshop',
                    'tutor_name' => 'Akilah',
                    'day_of_week' => 'Sunday',
                    'start_time' => '13:00',
                    'end_time' => '15:00',
                    'available_slots' => 5,
                    'description' => 'Explore creative thinking and imaginative drawing techniques'
                ]
            ],
            'watercolour' => [
                [
                    'id' => 'watercolour_001',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Alexis',
                    'day_of_week' => 'Monday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 4,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_002',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Tara',
                    'day_of_week' => 'Tuesday',
                    'start_time' => '19:00',
                    'end_time' => '21:00',
                    'available_slots' => 6,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_003',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Akilah',
                    'day_of_week' => 'Wednesday',
                    'start_time' => '17:00',
                    'end_time' => '19:00',
                    'available_slots' => 5,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_004',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Marcus',
                    'day_of_week' => 'Thursday',
                    'start_time' => '19:00',
                    'end_time' => '21:00',
                    'available_slots' => 3,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_005',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Nikki',
                    'day_of_week' => 'Friday',
                    'start_time' => '21:00',
                    'end_time' => '23:00',
                    'available_slots' => 4,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_006',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Rossen',
                    'day_of_week' => 'Saturday',
                    'start_time' => '09:00',
                    'end_time' => '11:00',
                    'available_slots' => 7,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_007',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Alexis',
                    'day_of_week' => 'Saturday',
                    'start_time' => '15:00',
                    'end_time' => '17:00',
                    'available_slots' => 5,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_008',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Tara',
                    'day_of_week' => 'Sunday',
                    'start_time' => '11:00',
                    'end_time' => '13:00',
                    'available_slots' => 6,
                    'description' => 'Master watercolour techniques and color theory'
                ],
                [
                    'id' => 'watercolour_009',
                    'name' => 'Watercolour Painting Masterclass',
                    'tutor_name' => 'Marcus',
                    'day_of_week' => 'Sunday',
                    'start_time' => '15:00',
                    'end_time' => '17:00',
                    'available_slots' => 4,
                    'description' => 'Master watercolour techniques and color theory'
                ]
            ]
        ];
        
        // Filter classes by the selected day and class type
        $availableClasses = [];
        
        if (isset($sampleClasses[$classType])) {
            foreach ($sampleClasses[$classType] as $class) {
                if ($class['day_of_week'] === $dayOfWeek) {
                    // Show classes for any date within the allowed range (1 month from today)
                    $availableClasses[] = [
                        'id' => $class['id'],
                        'name' => $class['name'],
                        'tutor_name' => $class['tutor_name'],
                        'day_of_week' => $class['day_of_week'],
                        'start_time' => $class['start_time'],
                        'end_time' => $class['end_time'],
                        'available_slots' => $class['available_slots'],
                        'class_type' => $classType,
                        'date' => $startDate,
                        'description' => $class['description']
                    ];
                }
            }
        }
        
        // Debug logging
        error_log("API Debug: Found " . count($availableClasses) . " classes for $classType on $dayOfWeek");
        
        header('Content-Type: application/json');
        echo json_encode($availableClasses);
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

    /**
     * Send password reset email
     */
    private function sendPasswordResetEmail($email, $name, $resetToken)
    {
        $subject = 'Password Reset Request - Royal Drawing School';
        $resetLink = "http://localhost/fresit/student/reset-password?token=" . $resetToken;
        
        $message = "Dear $name,\n\n";
        $message .= "You have requested to reset your password for your Royal Drawing School account.\n\n";
        $message .= "To reset your password, please click the following link:\n";
        $message .= "$resetLink\n\n";
        $message .= "This link will expire in 1 hour for security reasons.\n\n";
        $message .= "If you did not request this password reset, please ignore this email.\n\n";
        $message .= "Best regards,\nRoyal Drawing School Team";
        
        // In a real application, you would use a proper email library
        error_log("Password reset email sent to: $email with token: $resetToken");
    }
} 