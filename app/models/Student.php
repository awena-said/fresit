<?php

namespace App\Models;

require_once __DIR__ . '/../../includes/database.php';

class Student
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Create a new student account
     */
    public function createAccount($studentData)
    {
        try {
            // Check if email already exists
            if ($this->emailExists($studentData['email'])) {
                return false;
            }

            // Hash password
            $studentData['password'] = password_hash($studentData['password'], PASSWORD_DEFAULT);

            // Generate unique ID
            $studentData['id'] = uniqid('student_', true);

            // Insert student
            $sql = "INSERT INTO students (id, name, email, password, phone, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $this->db->execute($sql, [
                $studentData['id'],
                $studentData['name'],
                $studentData['email'],
                $studentData['password'],
                $studentData['phone'] ?? null
            ]);

            return $studentData['id'];
        } catch (Exception $e) {
            error_log("Database error in createAccount: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Authenticate student
     */
    public function authenticate($email, $password)
    {
        try {
            $student = $this->db->fetch("SELECT * FROM students WHERE email = ? AND is_active = 1", [$email]);
            
            if ($student && password_verify($password, $student['password'])) {
                // Update last login
                $this->db->execute("UPDATE students SET last_login = NOW() WHERE id = ?", [$student['id']]);
                return $student;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Database error in authenticate: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM students WHERE email = ? AND is_active = 1", [$email]);
            return $result && isset($result['count']) && $result['count'] > 0;
        } catch (Exception $e) {
            error_log("Database error in emailExists: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student by email
     */
    public function getByEmail($email)
    {
        try {
            return $this->db->fetch("SELECT * FROM students WHERE email = ? AND is_active = 1", [$email]);
        } catch (Exception $e) {
            error_log("Database error in getByEmail: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get student by ID
     */
    public function getById($id)
    {
        try {
            return $this->db->fetch("SELECT * FROM students WHERE id = ? AND is_active = 1", [$id]);
        } catch (Exception $e) {
            error_log("Database error in getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update student password
     */
    public function updatePassword($id, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            return $this->db->execute("UPDATE students SET password = ?, updated_at = NOW() WHERE id = ?", [$hashedPassword, $id]) > 0;
        } catch (Exception $e) {
            error_log("Database error in updatePassword: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student applications
     */
    public function getApplications($studentId)
    {
        try {
            return $this->db->fetchAll("
                SELECT a.*, c.name as class_name, c.type as class_type, c.date, c.start_time, c.end_time, s.name as tutor_name
                FROM applications a
                LEFT JOIN classes c ON a.class_id = c.id
                LEFT JOIN staff_users s ON c.tutor_id = s.id
                WHERE a.student_id = ? AND a.is_active = 1
                ORDER BY a.created_at DESC
            ", [$studentId]);
        } catch (Exception $e) {
            error_log("Database error in getApplications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Start student session
     */
    public function startSession($student)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['student_id'] = $student['id'];
        $_SESSION['student_email'] = $student['email'];
        $_SESSION['student_name'] = $student['name'];
    }

    /**
     * Logout student
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        unset($_SESSION['student_id']);
        unset($_SESSION['student_email']);
        unset($_SESSION['student_name']);
    }

    /**
     * Save password reset token
     */
    public function saveResetToken($email, $token, $expiry)
    {
        try {
            return $this->db->execute(
                "UPDATE students SET reset_token = ?, reset_token_expiry = ?, updated_at = NOW() WHERE email = ? AND is_active = 1",
                [$token, $expiry, $email]
            ) > 0;
        } catch (Exception $e) {
            error_log("Database error in saveResetToken: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get student by reset token
     */
    public function getByResetToken($token)
    {
        try {
            return $this->db->fetch(
                "SELECT * FROM students WHERE reset_token = ? AND reset_token_expiry > NOW() AND is_active = 1",
                [$token]
            );
        } catch (Exception $e) {
            error_log("Database error in getByResetToken: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear reset token after password reset
     */
    public function clearResetToken($email)
    {
        try {
            return $this->db->execute(
                "UPDATE students SET reset_token = NULL, reset_token_expiry = NULL, updated_at = NOW() WHERE email = ?",
                [$email]
            ) > 0;
        } catch (Exception $e) {
            error_log("Database error in clearResetToken: " . $e->getMessage());
            return false;
        }
    }
} 