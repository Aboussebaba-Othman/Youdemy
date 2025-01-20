<?php
namespace App\Models\Teacher;

use App\Config\DatabaseConnection;
use PDO;

class StudentModel
{
    private $connection;

    public function __construct()
    {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function getAllStudents($teacherId)
{
    try {
        $sql = "SELECT DISTINCT
                u.id,
                u.name,
                u.email,
                r.name as role,
                e.course_id,
                e.enrollment_date,
                c.title as course_title
            FROM Users u
            JOIN Students s ON u.id = s.user_id
            JOIN Enrollment e ON s.id = e.student_id
            JOIN Courses c ON e.course_id = c.id
            JOIN Roles r ON u.role_id = r.id
            WHERE c.teacher_id = :teacher_id
            ORDER BY u.name ASC";
            
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['teacher_id' => $teacherId]);
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("SQL Query executed for teacher ID: " . $teacherId);
        error_log("Number of students found: " . count($results));
        
        return $results;
    } catch (\PDOException $e) {
        error_log("Error in getAllStudents: " . $e->getMessage());
        error_log("SQL State: " . $e->getCode());
        return [];
    }
}
}