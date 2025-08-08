# Student Registration System

## Overview
This is a comprehensive student registration and management system built with PHP. The system allows students to register, apply for classes, and manage their accounts, while staff members can manage applications, create classes, and oversee the registration process.

## Features
- Student registration and login system
- Staff dashboard for managing applications
- Class creation and management
- Email notifications for applications
- Password reset functionality
- Secure authentication system

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (for dependency management)

## Installation

### 1. Database Setup
1. Create a MySQL database for the application
2. Import the database schema from `database/tables.sql`
3. Update database connection settings in `website/includes/database.php`

### 2. Web Server Configuration
1. Ensure your web server is configured to serve PHP files
2. Set the document root to the `website` folder
3. Ensure the `cache` directory is writable by the web server

### 3. Dependencies
1. Navigate to the `website` directory
2. Run `composer install` to install PHP dependencies

### 4. Configuration
1. Update email settings in the relevant PHP files for email notifications
2. Configure your web server's rewrite rules (Apache .htaccess is included)
3. Set appropriate file permissions for the cache and upload directories

## File Structure
```
website/
├── index.php                 # Main entry point
├── app/                      # MVC application structure
│   ├── controllers/          # Application controllers
│   ├── models/              # Data models
│   └── views/               # View templates
├── includes/                 # Core includes and utilities
├── vendor/                   # Composer dependencies
├── cache/                    # Cache directory
├── emails/                   # Email templates
├── public/                   # Public assets (if any)
├── .htaccess                 # Apache configuration
├── composer.json             # PHP dependencies
└── composer.lock             # Locked dependency versions
```

## Usage
1. Access the application through your web browser
2. Students can register and apply for classes
3. Staff can log in to manage applications and create classes
4. Email notifications are sent for application status changes

## Security Notes
- Ensure all sensitive configuration is properly secured
- Regularly update dependencies
- Use HTTPS in production
- Implement proper input validation and sanitization

## Support
For technical support or questions about the application, please refer to the documentation or contact the development team.
