<?php

// Manually include the Router class to bypass autoloader issues
require_once __DIR__ . '/core/Router.php';

// Manually include controller classes
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/StaffController.php';
require_once __DIR__ . '/controllers/StudentController.php';

// Manually include model classes
require_once __DIR__ . '/models/Application.php';
require_once __DIR__ . '/models/ClassModel.php';
require_once __DIR__ . '/models/StaffUser.php';
require_once __DIR__ . '/models/Student.php';

use App\Core\Router;

$router = new Router();

// Status route
$router->get('/status', 'HomeController@status');

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/reviews', 'HomeController@reviews');
$router->get('/booking', 'HomeController@booking');
$router->get('/booking-success', 'HomeController@bookingSuccess');
$router->get('/applications', 'HomeController@applications');

// Student routes
$router->get('/student/register', 'StudentController@showRegister');
$router->post('/student/register', 'StudentController@register');
$router->get('/student/login', 'StudentController@showLogin');
$router->post('/student/login', 'StudentController@login');
$router->get('/student/dashboard', 'StudentController@dashboard');
$router->get('/student/logout', 'StudentController@logout');
$router->get('/student/change-password', 'StudentController@showChangePassword');
$router->post('/student/change-password', 'StudentController@changePassword');

// Enhanced booking routes
$router->get('/student/booking', 'StudentController@showBooking');
$router->post('/student/apply', 'StudentController@submitApplication');
$router->get('/student/application-success', 'StudentController@applicationSuccess');

// Student API routes
$router->get('/student/api/classes', 'StudentController@getAvailableClasses');

// Staff routes
$router->get('/staff/login', 'StaffController@showLogin');
$router->post('/staff/login', 'StaffController@login');
$router->get('/staff/create-account', 'StaffController@showCreateAccount');
$router->post('/staff/create-account', 'StaffController@createAccount');
$router->get('/staff/dashboard', 'StaffController@dashboard');
$router->get('/staff/logout', 'StaffController@logout');

// Application management routes
$router->get('/staff/application/{id}', 'StaffController@getApplication');
$router->post('/staff/application/{id}/accept', 'StaffController@acceptApplication');
$router->post('/staff/application/{id}/reject', 'StaffController@rejectApplication');

// Class management routes
$router->post('/staff/classes/create', 'StaffController@createClass');
$router->post('/staff/classes/{id}/delete', 'StaffController@deleteClass');
$router->get('/staff/classes/{id}/roster', 'StaffController@getClassRoster');
$router->get('/staff/classes/{id}/roster/print', 'StaffController@printRoster');

return $router; 