<?php
namespace App\Models\Admin;
use App\Config\DatabaseConnection;
use PDO;
use PDOException;

class CourseModel {
    private $connection;

    public function __construct() {
        $db = new DatabaseConnection();
        try {
            $this->connection = $db->connect();
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getCourses() {
        try {
            $query = "
                SELECT
                    c.id,
                    c.title,
                    c.description,
                    c.image,
                    u.name AS teacher_name,
                    cat.title AS category_name
                FROM Courses c
                LEFT JOIN Teachers t ON c.teacher_id = t.id
                LEFT JOIN Users u ON t.user_id = u.id
                LEFT JOIN Categories cat ON c.category_id = cat.id
            ";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Error fetching courses: " . $e->getMessage());
        }
    }

    public function deleteCourse($id) {
        try {
            $stmt = $this->connection->prepare("DELETE FROM Courses WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() === 0) {
                throw new \Exception("No course found with the provided ID.");
            }
        } catch (PDOException $e) {
            throw new \Exception("Error deleting course: " . $e->getMessage());
        }
    }


    public function getTotalCourses() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM courses");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalUsers() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalTeachers() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM teachers");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalStudents() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM students");
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    public function getTopTeachers() {
        $stmt = $this->connection->prepare("
            SELECT u.name AS teacher_name,COUNT(c.id) AS total_courses,SUM(e.total_students) AS total_students
            FROM Teachers t
            JOIN Users u ON t.user_id = u.id
            JOIN Courses c ON c.teacher_id = t.id
            LEFT JOIN (SELECT course_id, COUNT(student_id) AS total_students
            FROM Enrollment
            GROUP BY course_id) e ON c.id = e.course_id
            GROUP BY t.id, u.name
            ORDER BY total_students DESC
            LIMIT 3;"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    
}
