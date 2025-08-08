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
        error_log("ArtClass::create() - SQL executed, result: " . ($result ? 'true' : 'false'));

        if ($result) {
            // Try to get the created class
            $createdClass = $this->getById($id);
            error_log("ArtClass::create() - getById result: " . ($createdClass ? 'found' : 'not found'));
            
            if ($createdClass) {
                return $createdClass;
            } else {
                // If getById fails, return a basic success response
                error_log("ArtClass::create() - returning fallback data for class ID: " . $id);
                return [
                    'id' => $id,
                    'name' => $data['name'],
                    'type' => $data['class_type'],
                    'date' => $data['start_date'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'tutor_id' => $data['tutor_id'],
                    'tutor_name' => $data['tutor_id'], // Use tutor_id as name if staff_user doesn't exist
                    'room' => $data['room'],
                    'capacity' => $data['capacity'],
                    'description' => $data['description'] ?? '',
                    'is_active' => 1
                ];
            }
        }
        
        error_log("ArtClass::create() - SQL execution failed");
        return false;
    }

    /**
     * Get class by ID
     */
    public function getById($id)
    {
        $sql = "SELECT c.*, COALESCE(s.name, c.tutor_id) as tutor_name 
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
        $sql = "SELECT c.*, COALESCE(s.name, c.tutor_id) as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.is_active = 1 
                ORDER BY c.date ASC, c.start_time ASC";
        
        $result = $this->db->fetchAll($sql);
        error_log("getAll() returned " . count($result) . " classes");
        return $result;
    }

    /**
     * Get upcoming classes
     */
    public function getUpcoming()
    {
        $sql = "SELECT c.*, COALESCE(s.name, c.tutor_id) as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.is_active = 1 AND c.date >= CURDATE()
                ORDER BY c.date ASC, c.start_time ASC";
        
        $result = $this->db->fetchAll($sql);
        error_log("getUpcoming() returned " . count($result) . " classes");
        return $result;
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

    /**
     * Get the next scheduled class
     */
    public function getNextScheduledClass()
    {
        $sql = "SELECT c.*, COALESCE(s.name, c.tutor_id) as tutor_name 
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                WHERE c.is_active = 1 AND c.date >= CURDATE()
                ORDER BY c.date ASC, c.start_time ASC 
                LIMIT 1";
        
        return $this->db->fetch($sql);
    }

    /**
     * Get enrolled students for a specific class
     */
    public function getEnrolledStudents($classId)
    {
        $sql = "SELECT a.*, 
                       COALESCE(s.name, a.student_name) as student_name,
                       COALESCE(s.email, a.student_email) as student_email,
                       COALESCE(s.phone, a.student_phone) as student_phone,
                       s.age
                FROM applications a 
                LEFT JOIN students s ON a.student_id = s.id 
                WHERE a.class_id = ? AND a.status = 'accepted' AND a.is_active = 1
                ORDER BY a.created_at ASC";
        
        return $this->db->fetchAll($sql, [$classId]);
    }

    /**
     * Get the next scheduled class with enrolled students
     */
    public function getNextClassWithStudents()
    {
        $nextClass = $this->getNextScheduledClass();
        
        if ($nextClass) {
            $nextClass['enrolled_students'] = $this->getEnrolledStudents($nextClass['id']);
        }
        
        return $nextClass;
    }

    /**
     * Get available classes for booking based on type and date
     */
    public function getAvailableClassesForBooking($classType, $startDate)
    {
        $sql = "SELECT c.*, 
                       COALESCE(s.name, c.tutor_id) as tutor_name,
                       DAYNAME(c.date) as day_of_week,
                       c.capacity - COALESCE(enrolled_count.count, 0) as available_slots
                FROM classes c 
                LEFT JOIN staff_users s ON c.tutor_id = s.id 
                LEFT JOIN (
                    SELECT class_id, COUNT(*) as count 
                    FROM applications 
                    WHERE status = 'accepted' AND is_active = 1 
                    GROUP BY class_id
                ) enrolled_count ON c.id = enrolled_count.class_id
                WHERE c.is_active = 1 
                AND c.type = ? 
                AND c.date = ?
                AND c.capacity > COALESCE(enrolled_count.count, 0)
                ORDER BY c.start_time ASC";
        
        $classes = $this->db->fetchAll($sql, [$classType, $startDate]);
        
        // Ensure available_slots is at least 0
        foreach ($classes as &$class) {
            $class['available_slots'] = max(0, (int)$class['available_slots']);
        }
        
        return $classes;
    }
}
