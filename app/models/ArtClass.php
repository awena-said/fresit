<?php

namespace App\Models;

class ArtClass
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Create a new class
     */
    public function create($data)
    {
        $id = uniqid('class_');
        
        $sql = "INSERT INTO classes (id, name, type, date, start_time, end_time, tutor_id, capacity, room, description) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $id,
            $data['name'],
            $data['class_type'],
            $data['start_date'],
            $data['start_time'],
            $data['end_time'],
            $data['tutor_id'],
            $data['capacity'],
            $data['room'],
            $data['description'] ?? ''
        ];

        $result = $this->db->execute($sql, $params);

        if ($result) {
            return $this->getById($id);
        }
        
        return false;
    }

    /**
     * Get class by ID
     */
    public function getById($id)
    {
        $sql = "SELECT c.*, s.name as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.id = ? AND c.is_active = 1";
        
        return $this->db->fetch($sql, [$id]);
    }

    /**
     * Get all active classes
     */
    public function getAll()
    {
        $sql = "SELECT c.*, s.name as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.is_active = 1 
                ORDER BY c.date ASC, c.start_time ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Get upcoming classes
     */
    public function getUpcoming()
    {
        $sql = "SELECT c.*, s.name as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.is_active = 1 AND c.date >= CURDATE()
                ORDER BY c.date ASC, c.start_time ASC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Update a class
     */
    public function update($id, $data)
    {
        $sql = "UPDATE classes 
                SET name = ?, type = ?, date = ?, start_time = ?, 
                    end_time = ?, tutor_id = ?, capacity = ?, 
                    room = ?, description = ?, updated_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        
        $params = [
            $data['name'],
            $data['class_type'],
            $data['start_date'],
            $data['start_time'],
            $data['end_time'],
            $data['tutor_id'],
            $data['capacity'],
            $data['room'],
            $data['description'] ?? '',
            $id
        ];
        
        return $this->db->execute($sql, $params);
    }

    /**
     * Delete a class (soft delete)
     */
    public function delete($id)
    {
        $sql = "UPDATE classes SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }

    /**
     * Get total count of classes
     */
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM classes WHERE is_active = 1";
        $result = $this->db->fetch($sql);
        return $result['total'];
    }

    /**
     * Get upcoming classes count
     */
    public function getUpcomingCount()
    {
        $sql = "SELECT COUNT(*) as total FROM classes WHERE is_active = 1 AND date >= CURDATE()";
        $result = $this->db->fetch($sql);
        return $result['total'];
    }
}
