<?php

namespace App\Controllers;

use App\Models\StaffUser;
use App\Models\Application;
use App\Models\ClassModel;

class StaffController extends BaseController
{
    private $staffUser = null;
    private $application = null;
    private $classModel = null;

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
    
    private function getApplicationModel()
    {
        if ($this->application === null) {
            $this->application = new Application();
        }
        return $this->application;
    }
    
    private function getClassModel()
    {
        if ($this->classModel === null) {
            $this->classModel = new ClassModel();
        }
        return $this->classModel;
    }

    public function showLogin()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('/staff/dashboard');
            return;
        }

        if (!$this->getStaffUser()->hasUsers()) {
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

        $user = $this->getStaffUser()->authenticate($email, $password);
        
        if ($user) {
            $this->getStaffUser()->startSession($user);
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

        if ($this->getStaffUser()->hasUsers()) {
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

        if ($this->getStaffUser()->create($userData)) {
            $user = $this->getStaffUser()->getByEmail($email);
            $this->getStaffUser()->startSession($user);
            $this->redirect('/staff/dashboard');
        } else {
            $this->redirect('/staff/create-account');
        }
    }

    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $user = $this->getUser();
        
        $dashboardData = [
            'user' => $user,
            'total_applications' => $this->getApplicationModel()->getTotalCount(),
            'pending_applications' => $this->getApplicationModel()->getPendingCount(),
            'total_classes' => $this->getClassModel()->getTotalCount(),
            'upcoming_classes' => $this->getClassModel()->getUpcomingClassesCount()
        ];

        $this->render('staff/dashboard.html', [
            'title' => 'Staff Dashboard',
            'dashboard' => $dashboardData
        ]);
    }

    public function applications()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $applications = $this->getApplicationModel()->getAll(20, 0);
        $classes = $this->getClassModel()->getAll();
        $upcomingClasses = $this->getClassModel()->getUpcomingClasses();

        $this->render('applications.html', [
            'title' => 'Applications',
            'applications' => $applications,
            'classes' => $classes,
            'upcoming_classes' => $upcomingClasses,
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function classes()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $classes = $this->getClassModel()->getAll();
        
        $this->render('staff/classes.html', [
            'title' => 'Manage Classes',
            'classes' => $classes,
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function schedule()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $this->render('staff/schedule.html', [
            'title' => 'Schedule Classes',
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function roster()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $classes = $this->getClassModel()->getAll();
        
        $this->render('staff/roster.html', [
            'title' => 'Class Roster',
            'classes' => $classes,
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function emails()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $this->render('staff/emails.html', [
            'title' => 'Email Files',
            'user' => $_SESSION['user'] ?? null
        ]);
    }

    public function getApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $application = $this->getApplicationModel()->getById($id);
        if (!$application) {
            $this->redirect('/staff/applications');
            return;
        }

        $this->render('staff/application-detail.html', [
            'title' => 'Application Details',
            'application' => $application
        ]);
    }

    public function acceptApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $application = $this->getApplicationModel()->getById($id);
        if (!$application) {
            $this->redirect('/staff/applications');
            return;
        }

        $user = $this->getUser();
        $success = $this->getApplicationModel()->updateStatus($id, 'accepted', $user['id']);
        
        if ($success) {
            // Send acceptance email
            $this->sendAcceptanceEmail($application);
        }

        $this->redirect('/staff/applications');
    }

    public function rejectApplication($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $application = $this->getApplicationModel()->getById($id);
        if (!$application) {
            $this->redirect('/staff/applications');
            return;
        }

        $user = $this->getUser();
        $success = $this->getApplicationModel()->updateStatus($id, 'rejected', $user['id']);
        
        if ($success) {
            // Send rejection email
            $this->sendRejectionEmail($application);
        }

        $this->redirect('/staff/applications');
    }

    public function createClass()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/staff/dashboard');
            return;
        }

        $classData = [
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'date' => $_POST['date'],
            'start_time' => $_POST['start_time'],
            'end_time' => $_POST['end_time'],
            'tutor_id' => $_POST['tutor_id'],
            'capacity' => $_POST['capacity'] ?? 20
        ];

        $success = $this->getClassModel()->create($classData);
        
        if ($success) {
            $this->redirect('/staff/dashboard?class_created=1');
        } else {
            $this->redirect('/staff/dashboard?error=1');
        }
    }

    public function deleteClass($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $success = $this->getClassModel()->delete($id);
        
        if ($success) {
            $this->redirect('/staff/dashboard?class_deleted=1');
        } else {
            $this->redirect('/staff/dashboard?error=1');
        }
    }

    public function getClassRoster($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $class = $this->getClassModel()->getById($id);
        if (!$class) {
            $this->redirect('/staff/dashboard');
            return;
        }

        $enrolledStudents = $this->getEnrolledStudents($id);

        $this->render('staff/class-roster.html', [
            'title' => 'Class Roster',
            'class' => $class,
            'students' => $enrolledStudents
        ]);
    }

    public function printRoster($id)
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/staff/login');
            return;
        }

        $class = $this->getClassModel()->getById($id);
        if (!$class) {
            $this->redirect('/staff/dashboard');
            return;
        }

        $enrolledStudents = $this->getEnrolledStudents($id);

        $this->render('staff/print-roster.html', [
            'title' => 'Class Roster - Print',
            'class' => $class,
            'students' => $enrolledStudents
        ]);
    }

    public function logout()
    {
        $this->getStaffUser()->logout();
        $this->redirect('/staff/login');
    }

    // Helper methods
    private function getUpcomingAvailableDates()
    {
        $dates = [];
        $currentDate = new DateTime();
        
        for ($i = 1; $i <= 30; $i++) {
            $date = clone $currentDate;
            $date->add(new DateInterval("P{$i}D"));
            
            // Check if there's already a class on this date
            $existingClass = $this->getClassModel()->getByDate($date->format('Y-m-d'));
            
            if (!$existingClass) {
                $dates[] = $date->format('Y-m-d');
            }
        }
        
        return $dates;
    }

    private function getTutors()
    {
        return $this->getStaffUser()->getByRole('instructor');
    }

    private function getTutorById($id)
    {
        return $this->getStaffUser()->getById($id);
    }

    private function getEnrolledStudents($classId)
    {
        return $this->getApplicationModel()->getByStatus('accepted');
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