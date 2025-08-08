<?php

namespace App\Models;

require_once __DIR__ . '/../../includes/database.php';

class StaffUser
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Check if any users exist
     */
    public function hasUsers()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM staff_users WHERE is_active = 1");
            return $result && isset($result['count']) && $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM staff_users WHERE email = ? AND is_active = 1", [$email]);
            return $result && isset($result['count']) && $result['count'] > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create a new user
     */
    public function create($userData)
    {
        try {
            // Check if email already exists
            if ($this->emailExists($userData['email'])) {
                return false;
            }

            // Hash password
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

            // Generate unique ID
            $userData['id'] = uniqid('user_', true);

            // Insert user
            $sql = "INSERT INTO staff_users (id, name, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $this->db->execute($sql, [
                $userData['id'],
                $userData['name'],
                $userData['email'],
                $userData['password'],
                $userData['role'] ?? 'staff'
            ]);

            // Return the created user data (without password)
            return [
                'id' => $userData['id'],
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'] ?? 'staff'
            ];
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Authenticate user
     */
    public function authenticate($email, $password)
    {
        try {
            $user = $this->db->fetch("SELECT * FROM staff_users WHERE email = ? AND is_active = 1", [$email]);
            
            if ($user && password_verify($password, $user['password'])) {
                // Update last login
                $this->db->execute("UPDATE staff_users SET last_login = NOW() WHERE id = ?", [$user['id']]);
                return $user;
            }
            
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get user by email
     */
    public function getByEmail($email)
    {
        try {
            return $this->db->fetch("SELECT * FROM staff_users WHERE email = ? AND is_active = 1", [$email]);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get user by ID
     */
    public function getById($id)
    {
        try {
            return $this->db->fetch("SELECT * FROM staff_users WHERE id = ? AND is_active = 1", [$id]);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get all users
     */
    public function getAll()
    {
        try {
            return $this->db->fetchAll("SELECT * FROM staff_users WHERE is_active = 1 ORDER BY created_at DESC");
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Update user
     */
    public function update($id, $userData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($userData['name'])) {
                $updates[] = "name = ?";
                $params[] = $userData['name'];
            }

            if (isset($userData['email'])) {
                // Check if email already exists for another user
                $existing = $this->db->fetch("SELECT id FROM staff_users WHERE email = ? AND id != ? AND is_active = 1", [$userData['email'], $id]);
                if ($existing) {
                    return false;
                }
                $updates[] = "email = ?";
                $params[] = $userData['email'];
            }

            if (isset($userData['password']) && !empty($userData['password'])) {
                $updates[] = "password = ?";
                $params[] = password_hash($userData['password'], PASSWORD_DEFAULT);
            }

            if (isset($userData['role'])) {
                $updates[] = "role = ?";
                $params[] = $userData['role'];
            }

            if (empty($updates)) {
                return false;
            }

            $updates[] = "updated_at = NOW()";
            $params[] = $id;

            $sql = "UPDATE staff_users SET " . implode(", ", $updates) . " WHERE id = ?";
            return $this->db->execute($sql, $params) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete user (soft delete)
     */
    public function delete($id)
    {
        try {
            return $this->db->execute("UPDATE staff_users SET is_active = 0, updated_at = NOW() WHERE id = ?", [$id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Start user session
     */
    public function startSession($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
    }

    /**
     * Logout user
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_unset();
        session_destroy();
        session_start();
    }

    /**
     * Get users by role
     */
    public function getByRole($role)
    {
        try {
            return $this->db->fetchAll("SELECT * FROM staff_users WHERE role = ? AND is_active = 1 ORDER BY name", [$role]);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get user statistics
     */
    public function getStats()
    {
        try {
            $stats = $this->db->fetch("
                SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admin_count,
                    SUM(CASE WHEN role = 'staff' THEN 1 ELSE 0 END) as staff_count,
                    SUM(CASE WHEN role = 'instructor' THEN 1 ELSE 0 END) as instructor_count
                FROM staff_users 
                WHERE is_active = 1
            ");
            
            return $stats;
        } catch (Exception $e) {
            return [
                'total_users' => 0,
                'admin_count' => 0,
                'staff_count' => 0,
                'instructor_count' => 0
            ];
        }
    }
} 