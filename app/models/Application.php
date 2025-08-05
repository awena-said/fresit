<?php

namespace App\Models;

require_once __DIR__ . '/../../includes/database.php';

class Application
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Create a new application
     */
    public function create($applicationData)
    {
        try {
            // Generate unique ID
            $applicationData['id'] = uniqid('app_', true);

            // Insert application
            $sql = "INSERT INTO applications (id, class_id, student_id, student_name, student_email, student_phone, experience_level, additional_notes, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            $this->db->execute($sql, [
                $applicationData['id'],
                $applicationData['class_id'],
                $applicationData['student_id'],
                $applicationData['student_name'],
                $applicationData['student_email'],
                $applicationData['student_phone'],
                $applicationData['experience_level'],
                $applicationData['additional_notes']
            ]);

            return $applicationData['id'];
        } catch (Exception $e) {
            error_log("Database error in create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all applications
     */
    public function getAll($limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT a.*, CONCAT(s.name, ' (', s.role, ')') as processed_by_name 
                    FROM applications a 
                    LEFT JOIN staff_users s ON a.processed_by = s.id 
                    ORDER BY a.created_at DESC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->fetchAll($sql, [$limit, $offset]);
            }
            
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Database error in getAll: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get application by ID
     */
    public function getById($id)
    {
        try {
            return $this->db->fetch("SELECT * FROM applications WHERE id = ?", [$id]);
        } catch (Exception $e) {
            error_log("Database error in getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get applications by status
     */
    public function getByStatus($status, $limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT a.*, CONCAT(s.name, ' (', s.role, ')') as processed_by_name 
                    FROM applications a 
                    LEFT JOIN staff_users s ON a.processed_by = s.id 
                    WHERE a.status = ? 
                    ORDER BY a.created_at DESC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->fetchAll($sql, [$status, $limit, $offset]);
            }
            
            return $this->db->fetchAll($sql, [$status]);
        } catch (Exception $e) {
            error_log("Database error in getByStatus: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update application
     */
    public function update($id, $applicationData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($applicationData['name'])) {
                $updates[] = "name = ?";
                $params[] = $applicationData['name'];
            }

            if (isset($applicationData['email'])) {
                $updates[] = "email = ?";
                $params[] = $applicationData['email'];
            }

            if (isset($applicationData['phone'])) {
                $updates[] = "phone = ?";
                $params[] = $applicationData['phone'];
            }

            if (isset($applicationData['age'])) {
                $updates[] = "age = ?";
                $params[] = $applicationData['age'];
            }

            if (isset($applicationData['class_type'])) {
                $updates[] = "class_type = ?";
                $params[] = $applicationData['class_type'];
            }

            if (isset($applicationData['preferred_date'])) {
                $updates[] = "preferred_date = ?";
                $params[] = $applicationData['preferred_date'];
            }

            if (isset($applicationData['message'])) {
                $updates[] = "message = ?";
                $params[] = $applicationData['message'];
            }

            if (isset($applicationData['status'])) {
                $updates[] = "status = ?";
                $params[] = $applicationData['status'];
            }

            if (isset($applicationData['processed_by'])) {
                $updates[] = "processed_by = ?";
                $params[] = $applicationData['processed_by'];
            }

            if (empty($updates)) {
                return false;
            }

            $updates[] = "updated_at = NOW()";
            $params[] = $id;

            $sql = "UPDATE applications SET " . implode(", ", $updates) . " WHERE id = ?";
            return $this->db->execute($sql, $params) > 0;
        } catch (Exception $e) {
            error_log("Database error in update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update application status
     */
    public function updateStatus($id, $status, $processedBy = null)
    {
        try {
            $sql = "UPDATE applications SET status = ?, processed_by = ?, processed_at = NOW(), updated_at = NOW() WHERE id = ?";
            return $this->db->execute($sql, [$status, $processedBy, $id]) > 0;
        } catch (Exception $e) {
            error_log("Database error in updateStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete application
     */
    public function delete($id)
    {
        try {
            return $this->db->execute("DELETE FROM applications WHERE id = ?", [$id]) > 0;
        } catch (Exception $e) {
            error_log("Database error in delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total applications count
     */
    public function getTotalCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM applications");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getTotalCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get pending applications count
     */
    public function getPendingCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM applications WHERE status = 'pending'");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getPendingCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get accepted applications count
     */
    public function getAcceptedCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM applications WHERE status = 'accepted'");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getAcceptedCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get rejected applications count
     */
    public function getRejectedCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM applications WHERE status = 'rejected'");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getRejectedCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count by status
     */
    public function getCountByStatus($status)
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM applications WHERE status = ?", [$status]);
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getCountByStatus: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Search applications
     */
    public function search($query, $limit = null, $offset = 0)
    {
        try {
            $searchTerm = "%{$query}%";
            $sql = "SELECT a.*, CONCAT(s.name, ' (', s.role, ')') as processed_by_name 
                    FROM applications a 
                    LEFT JOIN staff_users s ON a.processed_by = s.id 
                    WHERE a.name LIKE ? OR a.email LIKE ? OR a.class_type LIKE ? 
                    ORDER BY a.created_at DESC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $limit, $offset]);
            }
            
            return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
        } catch (Exception $e) {
            error_log("Database error in search: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get application statistics
     */
    public function getStatistics()
    {
        try {
            $stats = $this->db->fetch("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                    SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) as last_7_days,
                    SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as last_30_days
                FROM applications
            ");
            
            return $stats;
        } catch (Exception $e) {
            error_log("Database error in getStatistics: " . $e->getMessage());
            return [
                'total' => 0,
                'pending' => 0,
                'accepted' => 0,
                'rejected' => 0,
                'last_7_days' => 0,
                'last_30_days' => 0
            ];
        }
    }
} 
