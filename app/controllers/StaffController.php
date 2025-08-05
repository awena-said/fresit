<?php

namespace App\Controllers;

use App\Models\StaffUser;
use App\Models\Application;
use App\Models\ClassModel;

class StaffController extends BaseController
{
    private $staffUser;
    private $application;
    private $classModel;

    public function __construct()
    {
        parent::__construct();
        $this->staffUser = new StaffUser();
        $this->application = new Application();
        $this->classModel = new ClassModel();
    }

    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/staff/dashboard');
            return;
        }

        if (!$this->staffUser->hasUsers()) {
            $this->redirect('/staff/create-account');
            return;
        }

        $this->render('login.html', [
            'title' => 'Staff Login'
        ]);
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->redirect('/staff/login');
            return;
        }

        $user = $this->staffUser->authenticate($email, $password);
        
        if ($user) {
            $this->staffUser->startSession($user);
            $this->redirect('/staff/dashboard');
        } else {
            $this->redirect('/staff/login');
        }
    }

    public function showCreateAccount()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/staff/dashboard');
            return;
        }

        if ($this->staffUser->hasUsers()) {
            $this->redirect('/staff/login');
            return;
        }

        $this->render('create-account.html', [
            'title' => 'Create Admin Account'
        ]);
    }

    public function createAccount()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/create-account');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->redirect('/staff/create-account');
            return;
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'admin'
        ];

        if ($this->staffUser->create($userData)) {
            $user = $this->staffUser->getByEmail($email);
            $this->staffUser->startSession($user);
            $this->redirect('/staff/dashboard');
        } else {
            $this->redirect('/staff/create-account');
        }
    }

    public function dashboard()
    {
        $this->requireAuth();

        $user = $this->getCurrentUser();
        
        // Get statistics
        $stats = [
            'total_applications' => $this->application->getTotalCount(),
            'pending_applications' => $this->application->getPendingCount(),
            'total_classes' => $this->classModel->getTotalCount(),
            'upcoming_classes' => $this->classModel->getUpcomingClassesCount()
        ];

        // Get applications for display
        $applications = $this->application->getAll(20, 0);

        // Get upcoming dates (next 30 days without classes)
        $upcomingDates = $this->getUpcomingAvailableDates();

        // Get all classes
        $classes = $this->classModel->getAll();

        // Get upcoming classes for roster
        $upcomingClasses = $this->classModel->getUpcomingClasses();

        // Get tutors for class creation
        $tutors = $this->getTutors();

        $this->render('staff-dashboard.html', [
            'title' => 'Staff Dashboard',
            'user' => $user,
            'stats' => $stats,
            'applications' => $applications,
            'upcoming_dates' => $upcomingDates,
            'classes' => $classes,
            'upcoming_classes' => $upcomingClasses,
            'tutors' => $tutors
        ]);
    }

    public function getApplication($id)
    {
        $this->requireAuth();
        
        $application = $this->application->getById($id);
        
        if (!$application) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Application not found']);
            return;
        }

        echo json_encode(['success' => true, 'data' => $application]);
    }

    public function acceptApplication($id)
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $application = $this->application->getById($id);
        
        if (!$application) {
            echo json_encode(['success' => false, 'message' => 'Application not found']);
            return;
        }

        // Update application status
        $success = $this->application->updateStatus($id, 'accepted', $user['id']);
        
        if ($success) {
            // Send acceptance email with calendar link
            $this->sendAcceptanceEmail($application);
            echo json_encode(['success' => true, 'message' => 'Application accepted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to accept application']);
        }
    }

    public function rejectApplication($id)
    {
        $this->requireAuth();
        
        $user = $this->getCurrentUser();
        $application = $this->application->getById($id);
        
        if (!$application) {
            echo json_encode(['success' => false, 'message' => 'Application not found']);
            return;
        }

        // Update application status
        $success = $this->application->updateStatus($id, 'rejected', $user['id']);
        
        if ($success) {
            // Send rejection email
            $this->sendRejectionEmail($application);
            echo json_encode(['success' => true, 'message' => 'Application rejected']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reject application']);
        }
    }

    public function createClass()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $classData = [
            'name' => $input['name'] ?? '',
            'class_type' => $input['class_type'] ?? '',
            'start_date' => $input['start_date'] ?? '',
            'end_date' => $input['start_date'] ?? '', // Same as start date for single session
            'start_time' => $input['start_time'] ?? '',
            'end_time' => $input['end_time'] ?? '',
            'tutor_id' => $input['tutor_id'] ?? '',
            'room' => $input['room'] ?? '',
            'capacity' => $input['capacity'] ?? 20,
            'description' => $input['description'] ?? '',
            'created_by' => $this->getCurrentUser()['id']
        ];

        // Validate required fields
        if (empty($classData['name']) || empty($classData['class_type']) || 
            empty($classData['start_date']) || empty($classData['start_time'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        $success = $this->classModel->create($classData);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Class created successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create class']);
        }
    }

    public function deleteClass($id)
    {
        $this->requireAuth();
        
        $success = $this->classModel->delete($id);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Class deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete class']);
        }
    }

    public function getClassRoster($id)
    {
        $this->requireAuth();
        
        $class = $this->classModel->getById($id);
        
        if (!$class) {
            echo json_encode(['success' => false, 'message' => 'Class not found']);
            return;
        }

        // Get enrolled students
        $students = $this->getEnrolledStudents($id);
        
        // Get tutor name
        $tutor = $this->getTutorById($class['created_by']);
        
        $rosterData = [
            'class' => [
                'name' => $class['name'],
                'class_type' => $class['class_type'],
                'start_date' => $class['start_date'],
                'start_time' => $class['start_time'],
                'tutor_name' => $tutor ? $tutor['name'] : 'Unknown',
                'room' => $class['room'] ?? 'TBD'
            ],
            'students' => $students
        ];

        echo json_encode(['success' => true, 'data' => $rosterData]);
    }

    public function printRoster($id)
    {
        $this->requireAuth();
        
        $class = $this->classModel->getById($id);
        
        if (!$class) {
            echo 'Class not found';
            return;
        }

        $students = $this->getEnrolledStudents($id);
        $tutor = $this->getTutorById($class['created_by']);

        $this->render('roster-print.html', [
            'title' => 'Class Roster - ' . $class['name'],
            'class' => $class,
            'students' => $students,
            'tutor' => $tutor
        ]);
    }

    public function logout()
    {
        $this->staffUser->logout();
        $this->redirect('/staff/login');
    }

    // Helper methods
    private function getUpcomingAvailableDates()
    {
        $dates = [];
        $startDate = date('Y-m-d');
        
        for ($i = 1; $i <= 30; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
            
            // Check if there's already a class on this date
            $existingClass = $this->classModel->getByDate($date);
            
            if (!$existingClass) {
                $dates[] = [
                    'date' => $date,
                    'day_name' => date('l', strtotime($date))
                ];
            }
        }
        
        return $dates;
    }

    private function getTutors()
    {
        // Get all staff users with instructor role
        return $this->staffUser->getByRole('instructor');
    }

    private function getTutorById($id)
    {
        return $this->staffUser->getById($id);
    }

    private function getEnrolledStudents($classId)
    {
        // This would typically come from a class_enrollments table
        // For now, we'll return applications that have been accepted for this class type
        return $this->application->getByStatus('accepted');
    }

    private function sendAcceptanceEmail($application)
    {
        $subject = "Your Art Class Application Has Been Accepted!";
        
        // Generate calendar link
        $calendarLink = $this->generateCalendarLink($application);
        
        $message = "
        Dear {$application['name']},

        Congratulations! Your application for the {$application['class_type']} class has been accepted.

        Class Details:
        - Type: {$application['class_type']}
        - Date: {$application['preferred_date']}
        - Location: Fresit Art School

        Please add this class to your calendar using the link below:
        {$calendarLink}

        We look forward to seeing you in class!

        Best regards,
        The Fresit Art School Team
        ";

        // In a real application, you would use a proper email library
        // For now, we'll just log the email
        error_log("Sending acceptance email to: {$application['email']}");
        error_log("Subject: $subject");
        error_log("Message: $message");
    }

    private function sendRejectionEmail($application)
    {
        $subject = "Update on Your Art Class Application";
        
        $message = "
        Dear {$application['name']},

        Thank you for your interest in our {$application['class_type']} class.

        Unfortunately, we are unable to accommodate your application at this time. 
        This may be due to class capacity, scheduling conflicts, or other factors.

        We encourage you to apply again in the future, and we may have other class options available.

        Thank you for your understanding.

        Best regards,
        The Fresit Art School Team
        ";

        // In a real application, you would use a proper email library
        error_log("Sending rejection email to: {$application['email']}");
        error_log("Subject: $subject");
        error_log("Message: $message");
    }

    private function generateCalendarLink($application)
    {
        // Generate a Google Calendar link
        $eventTitle = urlencode("Fresit Art School - {$application['class_type']} Class");
        $eventDate = $application['preferred_date'];
        $eventTime = "10:00"; // Default time
        $eventLocation = urlencode("Fresit Art School");
        $eventDescription = urlencode("Art class session for {$application['class_type']}");
        
        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$eventTitle}&dates={$eventDate}T{$eventTime}00Z/{$eventDate}T{$eventTime}00Z&location={$eventLocation}&details={$eventDescription}";
    }
} 