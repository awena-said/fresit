<?php

namespace App\Models;

use PDO;

class ArtClass
{
    private $db;

    public function __construct()
    {
        global $pdo;
        $this->db = $pdo;
    }

    /**
     * Create a new class
     */
    public function create($data)
    {
        $id = uniqid('class_');
        
        $sql = "INSERT INTO classes (id, name, type, date, start_time, end_time, tutor_id, capacity, room, description) 
                VALUES (:id, :name, :type, :date, :start_time, :end_time, :tutor_id, :capacity, :room, :description)";
        
        $stmt = $this->db->prepare($sql);
        
        $result = $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['class_type'],
            'date' => $data['start_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'tutor_id' => $data['tutor_id'],
            'capacity' => $data['capacity'],
            'room' => $data['room'],
            'description' => $data['description'] ?? ''
        ]);

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
                WHERE c.id = :id AND c.is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update a class
     */
    public function update($id, $data)
    {
        $sql = "UPDATE classes 
                SET name = :name, type = :type, date = :date, start_time = :start_time, 
                    end_time = :end_time, tutor_id = :tutor_id, capacity = :capacity, 
                    room = :room, description = :description, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'id' => $id,
            'name' => $data['name'],
            'type' => $data['class_type'],
            'date' => $data['start_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'tutor_id' => $data['tutor_id'],
            'capacity' => $data['capacity'],
            'room' => $data['room'],
            'description' => $data['description'] ?? ''
        ]);
    }

    /**
     * Delete a class (soft delete)
     */
    public function delete($id)
    {
        $sql = "UPDATE classes SET is_active = 0, updated_at = CURRENT_TIMESTAMP WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get total count of classes
     */
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as total FROM classes WHERE is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Get upcoming classes count
     */
    public function getUpcomingCount()
    {
        $sql = "SELECT COUNT(*) as total FROM classes WHERE is_active = 1 AND date >= CURDATE()";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
