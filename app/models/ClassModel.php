<?php

namespace App\Models;

require_once __DIR__ . '/../../includes/database.php';

class ClassModel
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    /**
     * Create a new class
     */
    public function create($classData)
    {
        try {
            // Generate unique ID
            $classData['id'] = uniqid('class_', true);

            // Insert class
            $sql = "INSERT INTO classes (id, name, description, capacity, start_date, end_date, schedule, status, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', ?, NOW())";
            
            return $this->db->execute($sql, [
                $classData['id'],
                $classData['name'],
                $classData['description'] ?? '',
                $classData['capacity'] ?? 20,
                $classData['start_date'],
                $classData['end_date'],
                $classData['schedule'] ?? '',
                $classData['created_by']
            ]);
        } catch (Exception $e) {
            error_log("Database error in create: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all classes
     */
    public function getAll($limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT c.*, CONCAT(s.name, ' (', s.role, ')') as created_by_name,
                           COUNT(e.id) as enrolled_students
                    FROM classes c 
                    LEFT JOIN staff_users s ON c.created_by = s.id
                    LEFT JOIN class_enrollments e ON c.id = e.class_id
                    GROUP BY c.id
                    ORDER BY c.start_date DESC";
            
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
     * Get class by ID
     */
    public function getById($id)
    {
        try {
            return $this->db->fetch("SELECT * FROM classes WHERE id = ?", [$id]);
        } catch (Exception $e) {
            error_log("Database error in getById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get classes by status
     */
    public function getByStatus($status, $limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT c.*, CONCAT(s.name, ' (', s.role, ')') as created_by_name,
                           COUNT(e.id) as enrolled_students
                    FROM classes c 
                    LEFT JOIN staff_users s ON c.created_by = s.id
                    LEFT JOIN class_enrollments e ON c.id = e.class_id
                    WHERE c.status = ?
                    GROUP BY c.id
                    ORDER BY c.start_date DESC";
            
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
     * Get active classes
     */
    public function getActiveClasses($limit = null, $offset = 0)
    {
        return $this->getByStatus('active', $limit, $offset);
    }

    /**
     * Update class
     */
    public function update($id, $classData)
    {
        try {
            $updates = [];
            $params = [];

            if (isset($classData['name'])) {
                $updates[] = "name = ?";
                $params[] = $classData['name'];
            }

            if (isset($classData['description'])) {
                $updates[] = "description = ?";
                $params[] = $classData['description'];
            }

            if (isset($classData['capacity'])) {
                $updates[] = "capacity = ?";
                $params[] = $classData['capacity'];
            }

            if (isset($classData['start_date'])) {
                $updates[] = "start_date = ?";
                $params[] = $classData['start_date'];
            }

            if (isset($classData['end_date'])) {
                $updates[] = "end_date = ?";
                $params[] = $classData['end_date'];
            }

            if (isset($classData['schedule'])) {
                $updates[] = "schedule = ?";
                $params[] = $classData['schedule'];
            }

            if (isset($classData['status'])) {
                $updates[] = "status = ?";
                $params[] = $classData['status'];
            }

            if (empty($updates)) {
                return false;
            }

            $updates[] = "updated_at = NOW()";
            $params[] = $id;

            $sql = "UPDATE classes SET " . implode(", ", $updates) . " WHERE id = ?";
            return $this->db->execute($sql, $params) > 0;
        } catch (Exception $e) {
            error_log("Database error in update: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update class status
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE classes SET status = ?, updated_at = NOW() WHERE id = ?";
            return $this->db->execute($sql, [$status, $id]) > 0;
        } catch (Exception $e) {
            error_log("Database error in updateStatus: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete class
     */
    public function delete($id)
    {
        try {
            return $this->db->execute("DELETE FROM classes WHERE id = ?", [$id]) > 0;
        } catch (Exception $e) {
            error_log("Database error in delete: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get total classes count
     */
    public function getTotalCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM classes");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getTotalCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get count by status
     */
    public function getCountByStatus($status)
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM classes WHERE status = ?", [$status]);
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getCountByStatus: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get active classes count
     */
    public function getActiveCount()
    {
        return $this->getCountByStatus('active');
    }

    /**
     * Get inactive classes count
     */
    public function getInactiveCount()
    {
        return $this->getCountByStatus('inactive');
    }

    /**
     * Get cancelled classes count
     */
    public function getCancelledCount()
    {
        return $this->getCountByStatus('cancelled');
    }

    /**
     * Get completed classes count
     */
    public function getCompletedCount()
    {
        return $this->getCountByStatus('completed');
    }

    /**
     * Search classes
     */
    public function search($query, $limit = null, $offset = 0)
    {
        try {
            $searchTerm = "%{$query}%";
            $sql = "SELECT c.*, CONCAT(s.name, ' (', s.role, ')') as created_by_name,
                           COUNT(e.id) as enrolled_students
                    FROM classes c 
                    LEFT JOIN staff_users s ON c.created_by = s.id
                    LEFT JOIN class_enrollments e ON c.id = e.class_id
                    WHERE c.name LIKE ? OR c.description LIKE ? OR c.schedule LIKE ?
                    GROUP BY c.id
                    ORDER BY c.start_date DESC";
            
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
     * Get classes by date range
     */
    public function getByDateRange($startDate, $endDate, $limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT c.*, CONCAT(s.name, ' (', s.role, ')') as created_by_name,
                           COUNT(e.id) as enrolled_students
                    FROM classes c 
                    LEFT JOIN staff_users s ON c.created_by = s.id
                    LEFT JOIN class_enrollments e ON c.id = e.class_id
                    WHERE c.start_date >= ? AND c.end_date <= ?
                    GROUP BY c.id
                    ORDER BY c.start_date ASC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->fetchAll($sql, [$startDate, $endDate, $limit, $offset]);
            }
            
            return $this->db->fetchAll($sql, [$startDate, $endDate]);
        } catch (Exception $e) {
            error_log("Database error in getByDateRange: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get classes by date
     */
    public function getByDate($date)
    {
        try {
            return $this->db->fetch("SELECT * FROM classes WHERE start_date = ? AND status = 'active'", [$date]);
        } catch (Exception $e) {
            error_log("Database error in getByDate: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get upcoming classes count
     */
    public function getUpcomingClassesCount()
    {
        try {
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM classes WHERE start_date >= CURDATE() AND status = 'active'");
            return $result['count'];
        } catch (Exception $e) {
            error_log("Database error in getUpcomingClassesCount: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get upcoming classes
     */
    public function getUpcomingClasses($limit = null, $offset = 0)
    {
        try {
            $sql = "SELECT c.*, CONCAT(s.name, ' (', s.role, ')') as created_by_name
                    FROM classes c 
                    LEFT JOIN staff_users s ON c.created_by = s.id
                    WHERE c.start_date >= CURDATE() AND c.status = 'active'
                    ORDER BY c.start_date ASC, c.start_time ASC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ? OFFSET ?";
                return $this->db->fetchAll($sql, [$limit, $offset]);
            }
            
            return $this->db->fetchAll($sql);
        } catch (Exception $e) {
            error_log("Database error in getUpcomingClasses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get class statistics
     */
    public function getStatistics()
    {
        try {
            $stats = $this->db->fetch("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN start_date >= CURDATE() THEN 1 ELSE 0 END) as upcoming,
                    SUM(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as last_30_days
                FROM classes
            ");
            
            return $stats;
        } catch (Exception $e) {
            error_log("Database error in getStatistics: " . $e->getMessage());
            return [
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'cancelled' => 0,
                'completed' => 0,
                'upcoming' => 0,
                'last_30_days' => 0
            ];
        }
    }

    /**
     * Get available classes for booking
     */
    public function getAvailableClasses($classType, $startDate)
    {
        try {
            return $this->db->fetchAll("
                SELECT c.*, s.name as tutor_name, 
                       (c.capacity - COALESCE(enrollment_count, 0)) as available_slots,
                       DAYNAME(c.date) as day_of_week
                FROM classes c
                LEFT JOIN staff_users s ON c.tutor_id = s.id
                LEFT JOIN (
                    SELECT class_id, COUNT(*) as enrollment_count
                    FROM applications 
                    WHERE status IN ('accepted', 'pending') AND is_active = 1
                    GROUP BY class_id
                ) e ON c.id = e.class_id
                WHERE c.type = ? 
                AND c.date >= ? 
                AND c.status = 'active'
                AND (c.capacity - COALESCE(enrollment_count, 0)) > 0
                ORDER BY c.date ASC, c.start_time ASC
            ", [$classType, $startDate]);
        } catch (Exception $e) {
            error_log("Database error in getAvailableClasses: " . $e->getMessage());
            return [];
        }
    }
} 