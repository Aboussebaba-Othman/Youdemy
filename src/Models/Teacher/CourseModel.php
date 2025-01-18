<?php
namespace App\Models\Teacher;
use App\Config\DatabaseConnection;
use PDO;
use PDOException;

class CourseModel {
    private $connection;

    public function __construct() {
        $db = new DatabaseConnection();
        $this->connection = $db->connect();
    }

    public function addCourse($data) {
        try {
            $this->connection->beginTransaction();

            $sql = "INSERT INTO Courses (title, description, content, image, category_id, teacher_id) 
                    VALUES (:title, :description, :content, :image, :category_id, :teacher_id)";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'teacher_id' => 21 
            ]);

            $courseId = $this->connection->lastInsertId();

            if (!empty($data['tags'])) {
                $tagSql = "INSERT INTO Coursetag (course_id, tag_id) VALUES (:course_id, :tag_id)";
                $tagStmt = $this->connection->prepare($tagSql);

                foreach ($data['tags'] as $tagId) {
                    $tagStmt->execute([
                        'course_id' => $courseId,
                        'tag_id' => $tagId
                    ]);
                }
            }

            $this->connection->commit();
            return true;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new \Exception("Failed to add course: " . $e->getMessage());
        }
    }
    public function getTeacherCourses($teacherId) {
        try {
            $sql = "SELECT 
                    c.id,
                    c.title,
                    c.description,
                    c.image,
                    c.content,
                    cat.title as category_name,
                    COUNT(DISTINCT e.student_id) as student_count
                FROM Courses c
                LEFT JOIN Categories cat ON c.category_id = cat.id
                LEFT JOIN Enrollment e ON c.id = e.course_id
                WHERE c.teacher_id = :teacher_id
                GROUP BY c.id
                ORDER BY c.id DESC";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['teacher_id' => $teacherId]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Error fetching courses: " . $e->getMessage());
        }
    }

    public function deleteCourse($courseId, $teacherId) {
        try {
            $stmt = $this->connection->prepare(
                "SELECT id FROM Courses WHERE id = :id AND teacher_id = :teacher_id"
            );
            $stmt->execute(['id' => $courseId, 'teacher_id' => $teacherId]);
            
            if (!$stmt->fetch()) {
                throw new \Exception("Course not found or unauthorized");
            }

            $this->connection->beginTransaction();

            $stmt = $this->connection->prepare("DELETE FROM Coursetag WHERE course_id = :course_id");
            $stmt->execute(['course_id' => $courseId]);

            $stmt = $this->connection->prepare("DELETE FROM Enrollment WHERE course_id = :course_id");
            $stmt->execute(['course_id' => $courseId]);

            $stmt = $this->connection->prepare("DELETE FROM Courses WHERE id = :id AND teacher_id = :teacher_id");
            $stmt->execute(['id' => $courseId, 'teacher_id' => $teacherId]);

            $this->connection->commit();
            return true;

        } catch (PDOException $e) {
            $this->connection->rollBack();
            throw new \Exception("Error deleting course: " . $e->getMessage());
        }
    }
    public function getCourse($courseId, $teacherId) {
        try {
            $sql = "SELECT c.*, cat.title as category_name,
                    GROUP_CONCAT(t.id) as tag_ids
                    FROM Courses c
                    LEFT JOIN Categories cat ON c.category_id = cat.id
                    LEFT JOIN CourseTag ct ON c.id = ct.course_id
                    LEFT JOIN Tags t ON ct.tag_id = t.id
                    WHERE c.id = :id AND c.teacher_id = :teacher_id
                    GROUP BY c.id";
    
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
            
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($course) {
                $course['tags'] = $course['tag_ids'] ? explode(',', $course['tag_ids']) : [];
                unset($course['tag_ids']);
            }
            
            return $course;
    
        } catch (PDOException $e) {
            throw new \Exception("Error fetching course: " . $e->getMessage());
        }
    }
    
    public function updateCourse($courseId, $data, $teacherId) {
        try {
            $this->connection->beginTransaction();
    
            $stmt = $this->connection->prepare(
                "SELECT id FROM Courses WHERE id = :id AND teacher_id = :teacher_id"
            );
            $stmt->execute([
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
            
            if (!$stmt->fetch()) {
                throw new \Exception("Course not found or unauthorized");
            }
    
            $sql = "UPDATE Courses SET 
                    title = :title,
                    description = :description,
                    content = :content,
                    image = :image,
                    category_id = :category_id
                    WHERE id = :id AND teacher_id = :teacher_id";
    
            $stmt = $this->connection->prepare($sql);
            $result = $stmt->execute([
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'image' => $data['image'],
                'category_id' => $data['category_id'],
                'id' => $courseId,
                'teacher_id' => $teacherId
            ]);
    
            if (isset($data['tags'])) {
                $stmt = $this->connection->prepare("DELETE FROM CourseTag WHERE course_id = ?");
                $stmt->execute([$courseId]);
    
                if (!empty($data['tags'])) {
                    $tagSql = "INSERT INTO CourseTag (course_id, tag_id) VALUES (?, ?)";
                    $tagStmt = $this->connection->prepare($tagSql);
                    
                    foreach ($data['tags'] as $tagId) {
                        $tagStmt->execute([$courseId, $tagId]);
                    }
                }
            }
    
            $this->connection->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->connection->rollBack();
            error_log($e->getMessage());
            throw new \Exception("Error updating course: " . $e->getMessage());
        }
    }
}