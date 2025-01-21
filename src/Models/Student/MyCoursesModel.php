<?php
namespace App\Models\Student;

use App\Config\DatabaseConnection;
use PDO;

class MyCoursesModel {
    private $connection;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function getEnrolledCourses($userId) {
        try {
            $sql = "SELECT 
                    c.id,
                    c.title,
                    c.description,
                    c.image,
                    cat.title as category_name,
                    u.name as teacher_name,
                    e.enrollment_date
                FROM Courses c
                JOIN Enrollment e ON c.id = e.course_id
                JOIN Students s ON e.student_id = s.id
                JOIN Categories cat ON c.category_id = cat.id
                JOIN Teachers t ON c.teacher_id = t.id
                JOIN Users u ON t.user_id = u.id
                WHERE s.user_id = :user_id
                ORDER BY e.enrollment_date DESC";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching enrolled courses: " . $e->getMessage());
            return [];
        }
    }
}