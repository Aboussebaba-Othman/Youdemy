<?php
namespace App\Models\Teacher;

use App\Config\DatabaseConnection;
use PDO;

class StatisticModel
{
    private $connection;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function getTotalCourses($teacherId): int
    {
        $sql = "SELECT COUNT(*) AS total_courses FROM courses WHERE teacher_id = :teacher_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_courses'] ?? 0;
    }

    public function getTotalStudents($teacherId): int
    {
        $sql = "SELECT COUNT(DISTINCT student_id) AS total_students 
                FROM enrollment e
                JOIN courses c ON e.course_id = c.id
                WHERE c.teacher_id = :teacher_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_students'] ?? 0;
    }

    public function getTop3Courses($teacherId): array
    {
        $sql = "SELECT 
                    c.title, 
                    COUNT(e.student_id) AS enrollments
                FROM courses c
                LEFT JOIN enrollment e ON c.id = e.course_id
                WHERE c.teacher_id = :teacher_id
                GROUP BY c.id, c.title
                ORDER BY enrollments DESC
                LIMIT 3";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTop3Students($teacherId): array
    {
        $sql = "SELECT 
                    u.name, 
                    COUNT(DISTINCT e.course_id) AS course_count
                FROM users u
                JOIN enrollment e ON u.id = e.student_id
                JOIN courses c ON e.course_id = c.id
                WHERE c.teacher_id = :teacher_id
                GROUP BY u.id, u.name
                ORDER BY course_count DESC
                LIMIT 3";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
}