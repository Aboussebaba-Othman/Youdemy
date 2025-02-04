<?php
namespace App\Models\Student;

use App\Config\DatabaseConnection;
use App\Classes\Student;
use App\Classes\User;
use PDO;
use PDOException;
use Exception;


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

    public function getStudentByUserId($userId): ?Student
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT s.id, s.education_level, u.id as user_id, u.name, u.email
                FROM students s
                JOIN Users u ON s.user_id = u.id
                WHERE u.id = ? AND u.role_id = (SELECT id FROM Roles WHERE name = 'student')
            ");
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($row) {
                return new Student(
                    $row['id'],
                    $row['education_level'],
                    new User(
                        $row['user_id'],
                        $row['name'],
                        $row['email'],
                        null,
                        null,
                        null
                    )
                );
            }
        } catch (PDOException $e) {
            error_log("Error getting student: " . $e->getMessage());
        }
        return null;
    }
    public function unenrollCourse($courseId, $userId) {
        try {
            $this->connection->beginTransaction();
    
            $sql = "SELECT id FROM Students WHERE user_id = :user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['user_id' => $userId]);
            $studentId = $stmt->fetchColumn();
    
            if (!$studentId) {
                throw new \Exception("Étudiant non trouvé");
            }
    
            $sql = "DELETE FROM Enrollment 
                    WHERE student_id = :student_id 
                    AND course_id = :course_id";
                    
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute([
                'student_id' => $studentId,
                'course_id' => $courseId
            ]);
    
            $this->connection->commit();
            return true;
    
        } catch (\PDOException $e) {
            $this->connection->rollBack();
            error_log("Erreur lors de la désinscription : " . $e->getMessage());
            throw new \Exception("Impossible d'annuler l'inscription");
        }
    }
}