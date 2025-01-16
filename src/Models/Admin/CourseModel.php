<?php
namespace App\Models\Admin;

use App\Config\DatabaseConnection;
use PDO;
use PDOException;

class CourseModel {
    private $connection;
    
    public function __construct() {
        try {
            $db = new DatabaseConnection();
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
        } catch (PDOException $e) {
            throw new \Exception("Error deleting course: " . $e->getMessage());
        }
    }
}
