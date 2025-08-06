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
            $sql = "INSERT INTO applications (id, class_id, student_id, student_name, student_email, student_phone, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            $this->db->execute($sql, [
                $applicationData['id'],
                $applicationData['class_id'],
                $applicationData['student_id'] ?? null,
                $applicationData['student_name'],
                $applicationData['student_email'],
                $applicationData['student_phone']
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
            $sql = "SELECT a.*, c.name as class_name, c.type as class_type, c.date as class_date 
                    FROM applications a 
                    LEFT JOIN classes c ON a.class_id = c.id 
                    ORDER BY a.id DESC";
            
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
            $sql = "SELECT a.*, c.name as class_name, c.type as class_type, c.date as class_date 
                    FROM applications a 
                    LEFT JOIN classes c ON a.class_id = c.id 
                    WHERE a.status = ? 
                    ORDER BY a.id DESC";
            
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

            if (isset($applicationData['student_name'])) {
                $updates[] = "student_name = ?";
                $params[] = $applicationData['student_name'];
            }

            if (isset($applicationData['student_email'])) {
                $updates[] = "student_email = ?";
                $params[] = $applicationData['student_email'];
            }

            if (isset($applicationData['student_phone'])) {
                $updates[] = "student_phone = ?";
                $params[] = $applicationData['student_phone'];
            }

            if (isset($applicationData['status'])) {
                $updates[] = "status = ?";
                $params[] = $applicationData['status'];
            }

            if (empty($updates)) {
                return false;
            }

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
            $sql = "UPDATE applications SET status = ? WHERE id = ?";
            return $this->db->execute($sql, [$status, $id]) > 0;
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
     * Get total count
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
     * Get pending count
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
     * Get accepted count
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
     * Get rejected count
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
            $sql = "SELECT a.*, c.name as class_name, c.type as class_type, c.date as class_date 
                    FROM applications a 
                    LEFT JOIN classes c ON a.class_id = c.id 
                    WHERE a.student_name LIKE ? OR a.student_email LIKE ? OR c.name LIKE ? 
                    ORDER BY a.id DESC";
            
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
     * Get statistics
     */
    public function getStatistics()
    {
        try {
            $stats = $this->db->fetch("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM applications
            ");
            
            return $stats;
        } catch (Exception $e) {
            error_log("Database error in getStatistics: " . $e->getMessage());
            return [
                'total' => 0,
                'pending' => 0,
                'accepted' => 0,
                'rejected' => 0
            ];
        }
    }
} 
