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
                    cat.title AS category_name,
                    COUNT(DISTINCT e.student_id) as student_count
                FROM Courses c
                LEFT JOIN Teachers t ON c.teacher_id = t.id
                LEFT JOIN Users u ON t.user_id = u.id
                LEFT JOIN Categories cat ON c.category_id = cat.id
                LEFT JOIN Enrollment e ON c.id = e.course_id
                GROUP BY c.id, c.title, c.description, c.image, u.name, cat.title";
            
            $stmt = $this->connection->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Error fetching courses: " . $e->getMessage());
        }
    }

    public function deleteCourse($id) {
        try {
            $this->connection->beginTransaction();
    
            $stmt = $this->connection->prepare("DELETE FROM Enrollment WHERE course_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            $stmt = $this->connection->prepare("DELETE FROM Courses WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            $this->connection->commit();
    
            if ($stmt->rowCount() === 0) {
                throw new \Exception("No course found with the provided ID.");
            }
        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new \Exception("Error deleting course: " . $e->getMessage());
        }
    }

    public function getTotalCourses() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM courses");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getTotalUsers() {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM users where status = 'active'");
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
    public function getTopCategories()
{
    try {
        $stmt = $this->connection->prepare("
            SELECT c.title AS category_name, COUNT(co.id) AS total_courses
            FROM Categories c
            LEFT JOIN Courses co ON c.id = co.category_id
            GROUP BY c.id
            ORDER BY total_courses DESC
            LIMIT 3;
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new \Exception("Error fetching top categories: " . $e->getMessage());
    }
}
public function searchCourses($keyword = '', $categoryId = null, $page = 1) {
    $perPage = 6;
    $offset = ($page - 1) * $perPage;
    
    $sql = "SELECT c.*, cat.title as category_name, u.name as teacher_name,
            COUNT(DISTINCT e.student_id) as student_count
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.id
            LEFT JOIN teachers t ON c.teacher_id = t.id
            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN Enrollment e ON c.id = e.course_id
            WHERE 1=1";
    
    $params = [];
    
    if (!empty($keyword)) {
        $sql .= " AND c.title LIKE :keyword";
        $params[':keyword'] = "%$keyword%";
    }
    
    if (!empty($categoryId)) {
        $sql .= " AND c.category_id = :category_id";
        $params[':category_id'] = $categoryId;
    }
    
    $sql .= " GROUP BY c.id, c.title, c.description, c.image, cat.title, u.name";
    
    $countSql = "SELECT COUNT(DISTINCT c.id) as count FROM courses c WHERE 1=1";
    if (!empty($keyword)) {
        $countSql .= " AND c.title LIKE :keyword";
    }
    if (!empty($categoryId)) {
        $countSql .= " AND c.category_id = :category_id";
    }
    
    $totalStmt = $this->connection->prepare($countSql);
    $totalStmt->execute($params);
    $totalRecords = $totalStmt->fetch()['count'];
    
    $sql .= " LIMIT :limit OFFSET :offset";
    $stmt = $this->connection->prepare($sql);
    
    foreach($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return [
        'courses' => $stmt->fetchAll(PDO::FETCH_ASSOC),
        'currentPage' => $page,
        'totalPages' => ceil($totalRecords / $perPage),
        'keyword' => $keyword
    ];
}



public function getTotalCategories() {
    $sql = "SELECT COUNT(*) FROM Categories";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    return $stmt->fetchColumn();
}
}
