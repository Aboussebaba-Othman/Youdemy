<?php
namespace App\Models\Student;

use App\Config\DatabaseConnection;
use PDO;

class CourseDetailModel {
    private $connection;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function getCourseById($courseId) {
        try {
            $sql = "SELECT c.*, cat.title as category_name 
                    FROM Courses c
                    LEFT JOIN Categories cat ON c.category_id = cat.id
                    WHERE c.id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['id' => $courseId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching course: " . $e->getMessage());
            return null;
        }
    }

    public function getTeacherByCourseId($courseId) {
        try {
            $sql = "SELECT u.name, u.email, t.specialty
                    FROM Users u
                    JOIN Teachers t ON u.id = t.user_id
                    JOIN Courses c ON t.id = c.teacher_id
                    WHERE c.id = :course_id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['course_id' => $courseId]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching teacher: " . $e->getMessage());
            return null;
        }
    }

    public function getCourseSkills($courseId) {
        try {
            $sql = "SELECT t.title
                    FROM Tags t
                    JOIN CourseTag ct ON t.id = ct.tag_id
                    WHERE ct.course_id = :course_id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['course_id' => $courseId]);
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (\PDOException $e) {
            error_log("Error fetching skills: " . $e->getMessage());
            return [];
        }
    }

    public function isUserEnrolled($courseId, $userId) {
        try {
            $sql = "SELECT 1 FROM Enrollment e
                    JOIN Students s ON e.student_id = s.id
                    WHERE e.course_id = :course_id 
                    AND s.user_id = :user_id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'course_id' => $courseId,
                'user_id' => $userId
            ]);
            
            return (bool)$stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Error checking enrollment: " . $e->getMessage());
            return false;
        }
    }
    public function enrollStudent($courseId, $userId) {
        try {
            $this->connection->beginTransaction();
    
            $sql = "SELECT id FROM Students WHERE user_id = :user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $studentId = $stmt->fetchColumn();
    
            if (!$studentId) {
                throw new \Exception("Student not found");
            }
    
            if ($this->isUserEnrolled($courseId, $userId)) {
                throw new \Exception("Already enrolled in this course");
            }
    
            $sql = "INSERT INTO Enrollment (student_id, course_id, enrollment_date) 
                    VALUES (:student_id, :course_id, NOW())";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
    
            $this->connection->commit();
            return true;
    
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            error_log("Database error in enrollStudent: " . $e->getMessage());
            throw new \Exception("Error enrolling in course");
        }
    }
}