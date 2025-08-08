<?php

namespace App\Models;

use Exception;

class Application
{
    private $db;

    public function __construct()
    {
        $this->db = \db();
    }

    /**
     * Create a new application
     */
    public function create($data)
    {
        try {
            $id = 'APP-' . uniqid();
            
            $sql = "INSERT INTO applications (
                id, class_id, student_id, student_name, student_email, 
                student_phone, experience_level, additional_notes, status, 
                created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            
            $params = [
                $id,
                $data['class_id'] ?? null,
                $data['student_id'] ?? null,
                $data['student_name'],
                $data['student_email'],
                $data['student_phone'],
                $data['experience_level'] ?? 'beginner',
                $data['additional_notes'] ?? '',
                'pending'
            ];
            
            $this->db->execute($sql, $params);
            return $id;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get application by ID
     */
    public function getById($id)
    {
        try {
            return $this->db->fetch(
                "SELECT * FROM applications WHERE id = ? AND is_active = 1",
                [$id]
            );
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get all applications
     */
    public function getAll()
    {
        try {
            return $this->db->fetchAll(
                "SELECT a.*, c.name as class_name, c.type as class_type, c.date, c.start_time, c.end_time, s.name as tutor_name
                 FROM applications a
                 LEFT JOIN classes c ON a.class_id = c.id
                 LEFT JOIN staff_users s ON c.tutor_id = s.id
                 WHERE a.is_active = 1
                 ORDER BY a.created_at DESC"
            );
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Update application status
     */
    public function updateStatus($id, $status, $reviewedBy = null)
    {
        try {
            $sql = "UPDATE applications SET status = ?, reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW() WHERE id = ?";
            $params = [$status, $reviewedBy, $id];
            
            return $this->db->execute($sql, $params) > 0;
        } catch (Exception $e) {
            return false;
        }
    }
} 