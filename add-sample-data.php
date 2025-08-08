<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/models/ArtClass.php';
require_once __DIR__ . '/includes/database.php';

use App\Models\ArtClass;

echo "<h2>Adding Sample Data for Roster Testing</h2>";

try {
    $db = db();
    echo "✅ Database connection successful<br>";
    
    // First, let's create a sample class if none exists
    $artClass = new ArtClass();
    
    // Check if we have any classes
    $existingClasses = $artClass->getAll();
    
    if (empty($existingClasses)) {
        echo "<h3>Creating sample class...</h3>";
        
        $sampleClassData = [
            'name' => 'Foundation Art Class',
            'class_type' => 'Foundation',
            'start_date' => '2025-01-15',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'tutor_id' => 'agnes',
            'room' => 'Studio A',
            'capacity' => 20,
            'description' => 'Learn the fundamentals of art in this comprehensive foundation class.'
        ];
        
        $classResult = $artClass->create($sampleClassData);
        
        if ($classResult) {
            echo "✅ Sample class created successfully<br>";
            $classId = $classResult['id'];
        } else {
            echo "❌ Failed to create sample class<br>";
            exit;
        }
    } else {
        echo "✅ Found existing classes<br>";
        $classId = $existingClasses[0]['id'];
    }
    
    // Now let's add some sample applications (enrolled students)
    echo "<h3>Adding sample applications...</h3>";
    
    $sampleStudents = [
        [
            'student_name' => 'Sarah Johnson',
            'student_email' => 'sarah.johnson@email.com',
            'student_phone' => '555-0101',
            'experience_level' => 'beginner',
            'additional_notes' => 'Excited to learn art fundamentals!'
        ],
        [
            'student_name' => 'Michael Chen',
            'student_email' => 'michael.chen@email.com',
            'student_phone' => '555-0102',
            'experience_level' => 'intermediate',
            'additional_notes' => 'Looking to improve my technique.'
        ],
        [
            'student_name' => 'Emma Davis',
            'student_email' => 'emma.davis@email.com',
            'student_phone' => '555-0103',
            'experience_level' => 'beginner',
            'additional_notes' => 'First time taking an art class.'
        ],
        [
            'student_name' => 'James Wilson',
            'student_email' => 'james.wilson@email.com',
            'student_phone' => '555-0104',
            'experience_level' => 'beginner',
            'additional_notes' => 'Interested in exploring creativity.'
        ],
        [
            'student_name' => 'Lisa Brown',
            'student_email' => 'lisa.brown@email.com',
            'student_phone' => '555-0105',
            'experience_level' => 'intermediate',
            'additional_notes' => 'Want to refine my skills.'
        ]
    ];
    
    $applicationsAdded = 0;
    
    foreach ($sampleStudents as $student) {
        $applicationId = uniqid('app_');
        
        $sql = "INSERT INTO applications (id, class_id, student_name, student_email, student_phone, experience_level, additional_notes, status, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'accepted', 1)";
        
        $params = [
            $applicationId,
            $classId,
            $student['student_name'],
            $student['student_email'],
            $student['student_phone'],
            $student['experience_level'],
            $student['additional_notes']
        ];
        
        $result = $db->execute($sql, $params);
        
        if ($result) {
            echo "✅ Added application for {$student['student_name']}<br>";
            $applicationsAdded++;
        } else {
            echo "❌ Failed to add application for {$student['student_name']}<br>";
        }
    }
    
    echo "<h3>Summary:</h3>";
    echo "✅ Sample data added successfully!<br>";
    echo "📊 Applications added: $applicationsAdded<br>";
    echo "🎨 Class ID: $classId<br>";
    
    // Test the roster functionality
    echo "<h3>Testing Roster Functionality:</h3>";
    
    $nextClassData = $artClass->getNextClassWithStudents();
    
    if ($nextClassData) {
        echo "✅ Next class found: {$nextClassData['name']}<br>";
        echo "📅 Date: {$nextClassData['date']}<br>";
        echo "⏰ Time: {$nextClassData['start_time']} - {$nextClassData['end_time']}<br>";
        echo "👥 Enrolled students: " . count($nextClassData['enrolled_students']) . "<br>";
        
        echo "<h4>Enrolled Students:</h4>";
        foreach ($nextClassData['enrolled_students'] as $student) {
            echo "- {$student['student_name']} ({$student['student_email']})<br>";
        }
        
        echo "<br><a href='/fresit/roster.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View Roster Page</a>";
        
    } else {
        echo "❌ No upcoming classes found<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
?>
